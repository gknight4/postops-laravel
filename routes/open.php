<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Open Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
// //    Route::post('login', 'AuthController@login');
// //     Route::post('logout', 'AuthController@logout');
// //     Route::post('refresh', 'AuthController@refresh');
// //     Route::post('me', 'AuthController@me');
// });

Route::post('users', 'AuthController@register');
Route::get('authenticate/{useremail}/{password}', 'AuthController@login');
Route::options('/', 'AuthController@options');
Route::options('authenticate/{useremail}/{password}', 'AuthController@options');
Route::options('users/{useremail}/{password}', 'AuthController@options');
Route::options('users', 'AuthController@options');

// Route::post('recover', 'AuthController@recover');

// Route::group(['middleware' => ['jwt.auth']], function() {
//     Route::get('logout', 'AuthController@logout');
//     Route::get('test/{var}', function($var){
//     Log::debug($var);
//         return response()->json(['foo'=>'bar']);
//     });
// });
