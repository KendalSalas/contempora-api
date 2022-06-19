<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PutUsuariosID extends Controller
{
    function PutUsuariosID(Request $request)
    {

        //Primero valido que el metodo sea PUT
        if ($request->method('put')) {
            $id = $request->id; //ID del usuario a actualizar
            
            $bodyContent = json_decode($request->getContent()); //obtengo el cuerpo de la respuesta (JSON), pasado por un json_decode

            //Valido si existe un error en el JSON recibido
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(['Error Formato' => 'Debe enviar los datos como JSON'], 400);
            }

            $datos = array(); //Arreglo en el cual iré almacenando los parametros a enviar en el PUT

            if (isset($bodyContent->nombre)) {
                //Valido que en el cuerpo venga el campo nombre
                //Si es el caso, lo almaceno en una variable
                $nombre = $bodyContent->nombre;

                //Valido si nombre es un string vacío
                if (trim($nombre) == '') {
                    //Si es el caso, hago un return error, indicando que los campos no pueden ir en blanco
                    //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores 
                    return response()->json(['Error Campos' => 'Los campos no pueden ir en blanco'], 422);
                }

                //Si no se cumple el if, entonces agrego el campo name al arreglo como key, y nombre como su value
                $datos['name'] = $nombre;
            }

            if (isset($bodyContent->genero)) {
                //Valido que en el cuerpo venga el campo genero
                //Si es el caso, lo almaceno en una variable
                $genero = strtolower($bodyContent->genero);

                //Valido que el campo genero sea distinto de male/female o que sea un string vacío
                if (($genero != 'male' && $genero != 'female') || trim($genero) == '') {
                    //Si es el caso, hago un return error, indicando que ese campo solo puede ser male o female
                    //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores 
                    return response()->json(['Error Genero' => 'Este campo solo puede ser male o female'], 422);
                }

                //Si no se cumple el if, entonces agrego el campo gender al arreglo como key, y genero como su value
                $datos['gender'] = $genero;
            }

            if (isset($bodyContent->email)) {
                //Valido que en el cuerpo venga el campo email
                //Si es el caso, lo almaceno en una variable
                $email = $bodyContent->email;

                //Valido que el campo email sea un string vacío
                if (trim($email) == '') {
                    //Si es el caso, hago un return error, indicando que los campos no pueden ir en blanco
                    //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores 
                    return response()->json(['Error Campos' => 'Los campos no pueden ir en blanco'], 422);
                }

                //Si no se cumple el if, agrego el campo email al arreglo como key, y $email como su value
                $datos['email'] = $email;
            }

            if (isset($bodyContent->activo)) {
                //Valido que en el cuerpo venga el campo activo
                //Si es el caso, lo almaceno en una variable
                $activo = $bodyContent->activo;

                //Valido que el campo activo no sea un boolean
                if (!is_bool($activo)) {
                    return response()->json(['Error Activo' => 'Este campo solo puede ser true o false (boolean)'], 422);
                }

                //Si no se cumple el if, primero creo una variable que almacenará el estado (active/inactive) en base a si el valor activo es true o false
                $status = $activo ? 'active' : 'inactive';
                $datos['status'] = $status;
            }

            //Creo la petición a la API con los datos recibidos
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer 533e1f952ece029296fe49d30afb96808a5429ff1e56d4af9d5a9571548f1058'
            ])->put(
                'https://gorest.co.in/public/v2/users/' . $id,
                $datos
            );

            //Almaceno la respuesta pasada por un json_decode
            $jsonResponse = json_decode($response);

            //Valido que en la respuesta no venga el campo message
            if (isset($jsonResponse->message)) {
                //Si es el caso, hago un return error, indicando que no se encontró un usuario con ese ID
                //Retorno un 402 
                return response()->json(['Error ID' => 'No existe un usuario con ese ID'], 402);
            } else if (isset($jsonResponse->id)) { //Si dentro de la respuesta viene el campo id, es porque se actualizó con éxito
                $id     = $jsonResponse->id; //Id del usuario
                $name   = $jsonResponse->name; //Nombre del usuario
                $emailR = $jsonResponse->email; //Email del usuario 
                $gender = $jsonResponse->gender; //Genero del usuario
                $status = ($jsonResponse->status == 'active') ? true : false; //Estado del usuario pasado a true o false en base a la respuesta

                //Creo un arreglo en el cual almacenaré todos los datos
                $datos_final = array(
                    'id' => $id,
                    'nombre' => $name,
                    'email' => $emailR,
                    'genero' => $gender,
                    'activo' => $status
                );

                //Hago un return del arreglo con un código 200
                return response()->json($datos_final, 200);
            } else if ($jsonResponse[0]->field == 'email' && $jsonResponse[0]->message == 'has already been taken') { //Valido que dentro del arreglo venga un campo email y un campo message = 'has already been taken'
                //Si se cumple, es porque el email ya existe y no se pudo crear el usuario
                //Retorno un json indicando el error y con código 422
                return response()->json(['Error Email' => 'Ya existe un usuario con ese email'], 422);
            }
        } else { //Caso contrario, hago un return con un error
            return response()->json(['Error Metodo' => 'Metodo invalido'], 402);
        }
    }
}
