<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class GetUsuarios extends Controller
{
    public function GetUsuarios(Request $request)
    {
        $method = $request->method();

        //Primero valido que el metodo sea get
        if ($method === 'GET') {
            //Declaro 2 variables en blanco, para almacenar el parametro y el valor de este, en caso de existir, y así mandarlo
            //Lo utilizaré para obtener usuarios por nombre, email o su estado (activo => true / inactivo => false)
            $param  = ''; //Nombre del parametro para enviar a la API y hacer el filtro de los usuarios (EJ: name, email, status)
            $valueP = ''; //Valor del parametro a enviar 

            if ($request->has('nombre')) { //Primero evaluo que la petición tenga algún parametro extra para filtrar
                //Si viene el nombre, lo almaceno en una variable
                $nombre = $request->query('nombre');

                //Valido que nombre no sea un string vacio o con espacios
                if (trim($nombre) != '') {
                    //si es el caso, almaceno los datos en las variables correspondientes
                    $param = 'name';
                    $valueP = $nombre;
                }
            } else if ($request->has('email')) { //Si viene el email, lo almaceno en una variable
                $email = $request->query('email');

                //Valido que email no sea un string vacío
                if (trim($email) != '') {
                    //Si es el caso, almaceno los datos en las variables correspondientes
                    $param = 'email';
                    $valueP = $email;
                }
            } else if ($request->has('activos')) { //Si viene activos, lo almaceno en una variable
                $activo = strtolower($request->query('activos'));

                //Valido que el campo activos sea distinto de true/false (en este caso debo validarlo como string y no como bool)
                if ($activo != 'true' && $activo != 'false') {
                    //Si es el caso, hago un return error, indicando que ese campo solo puede ser true o false
                    return response()->json(['Error Activo' => 'Este parametro solo puede ser true o false'], 402);
                }
                //Si llegó a este punto, entonces paso las validaciones anteriores y solo me queda guardar los parametros
                $param  = 'status';
                $valueP = $activo == 'true' ? 'active' : 'inactive';
            }

            if ($param != '') { //Antes de enviar la petición a la API, valido que $param no sea un string vacío
                //Si tiene datos, significa que debo enviar parametros para filtrar dentro de la petición
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                    'access-token' => '533e1f952ece029296fe49d30afb96808a5429ff1e56d4af9d5a9571548f1058'
                ])->get('https://gorest.co.in/public/v2/users', [
                    $param => $valueP
                ]);
            } else { //Caso contrario, traigo la respuesta sin filtrar
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json',
                    'access-token' => '533e1f952ece029296fe49d30afb96808a5429ff1e56d4af9d5a9571548f1058'
                ])->get('https://gorest.co.in/public/v2/users');
            }

            //Guardo la variable pasada a json_decode
            $jsonResponse = json_decode($response);

            if ($param != '' && count($jsonResponse) == 0) { //En caso de que se hayan enviado parametros para filtrar y no obtenga respuesta
                return response()->json(['Sin resultados' => "No hubo resultados para $valueP"], 404);
            }

            //Creo un arreglo en el cual almacenaré la respuesta procesada
            $datos_final = [];

            //Itero la respuesta para obtener los datos que necesito
            foreach ($jsonResponse as $user) {
                $idUser     = $user->id; //Id del usuario
                $nameUser   = $user->name; //Nombre del usuario
                $emailUser  = $user->email; //Email del usuario
                $genderUser = $user->gender; //Genero del usuario
                $statusUser = $user->status; //Estado del usuario (active /inactive);

                //Variable para convertir el estado del usuario a true o false dependiendo del valor de la respuesta
                $statusTrueFalse = $statusUser == 'active' ? true : false;

                //Creo un arreglo asociativo con los datos procesados
                $data = array(
                    'id' => $idUser,
                    'nombre' => $nameUser,
                    'email' => $emailUser,
                    'genero' => $genderUser,
                    'activo' => $statusTrueFalse,
                );

                //Lo inyecto en el arreglo datos_final
                array_push($datos_final, $data);
            }

            //Hago un return a datos_final
            return $datos_final;
        } else { //Caso contrario, hago un return con un error
            return response()->json(['Error Metodo' => 'Metodo invalido'], 402);
        }
    }
}
