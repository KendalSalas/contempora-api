<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PostUsuarios extends Controller
{
    function PostUsuarios(Request $request)
    {
        //Primero valido que el metodo sea post
        if ($request->method('post')) {

            //Valido si existe un error en el JSON recibido
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(['Error Formato' => 'Debe enviar los datos como JSON'], 400);
            }

            $nombre = $request->nombre; //Nombre del usuario a enviar
            $email  = $request->email; //Email del usuario a enviar
            $genero = strtolower($request->genero); //Genero del usuario a enviar (pasado a minusculas)
            $activo = $request->activo; //Estado del usuario a enviar, este valor será actualizado a active/inactive en base al valor que venga

            //Primero, valido que el valor de activo no sea un boolean
            if (!is_bool($activo)) {
                //Si es el caso, hago un return error, indicando que ese campo solo puede ser true o false
                //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores 
                return response()->json(['Error Activo' => 'Este campo solo puede ser true o false (boolean)'], 422);
            }

            //Luego, valido que el campo genero sea distinto a male o female, o que sea un string vacío
            if (($genero != 'male' && $genero != 'female') || trim($genero) == '') {
                //Si es el caso, hago un return error, indicando que ese campo solo puede ser male o female
                //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores 
                return response()->json(['Error Genero' => 'Este campo solo puede ser male o female'], 422);
            }

            //Finalmente, valido que uno de los valores restantes (nombre y email) sean un string vacío
            if (trim($nombre) == '' || trim($email) == '') {
                //Si es así, hago un return indicando que los campos no pueden ir en blanco
                //Retorno un código 422 porque era el que me devolvía la API cuando enviaba mal los valores
                return response()->json(['Error Campos' => 'Los campos no pueden ir en blanco'], 422);
            }

            //Si no se cumple ninguno de los if anteriores, continuo el flujo

            //Creo una variable status para pasarla con el valor correspondiente a la API
            //true => active / false => inactive
            $status = $activo ? 'active' : 'inactive';

            //Creo un arreglo asociativo con los datos del usuario a crear
            $arrDatosUsuario = array(
                'name' => $nombre,
                'email' => $email,
                'gender' => $genero,
                'status' => $status
            );

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json',
                'Authorization' => 'Bearer 533e1f952ece029296fe49d30afb96808a5429ff1e56d4af9d5a9571548f1058'
            ])->post(
                'https://gorest.co.in/public/v2/users',
                $arrDatosUsuario
            );

            //Guardo la variable pasada a json_decode
            $jsonResponse = json_decode($response);

            if (isset($jsonResponse->id)) { //Valido que, dentro de la respuesta, venga el campo ID
                //Si es el caso, es porque hubo éxito y empiezo a almacenar los valores a retornar
                $id     = $jsonResponse->id; //ID del usuario
                $name   = $jsonResponse->name; //Nombre del usuario
                $emailR = $jsonResponse->email; //Email del usuario
                $gender = $jsonResponse->gender; //Genero del usuario
                $status = ($jsonResponse->status == 'active') ? true : false; //Estado del usuario pasado a true o false en base a la respuesta

                //Creo un arreglo con los campos a retornar
                $datos_final = array(
                    'id' => $id,
                    'nombre' => $name,
                    'email' => $emailR,
                    'genero' => $gender,
                    'activo' => $status
                );

                //Lanzo la respuesta como json y un estado 201
                return response()->json($datos_final, 201);
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
