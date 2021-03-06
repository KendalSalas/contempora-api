<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetUsuarios;
use App\Http\Controllers\PostUsuarios;
use App\Http\Controllers\PutUsuariosID;
use App\Http\Controllers\PutUsuariosEmail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/usuarios', [GetUsuarios::class, 'GetUsuarios']);
Route::post('/usuarios', [PostUsuarios::class, 'PostUsuarios']);
Route::put('/usuarios/{id}', [PutUsuariosID::class, 'PutUsuariosID']);
Route::put('/usuarios', [PutUsuariosEmail::class, 'PutUsuariosEmail']);