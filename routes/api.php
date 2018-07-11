<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
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

Route::post('register', 'AuthController@register');
Route::post('authenticate', 'AuthController@login');
// Route::post('recover', 'AuthController@recover');
    Route::options('check', 'AuthController@options');
    Route::options('stringstore', 'AuthController@options');

Route::group(['middleware' => ['jwt.auth']], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('check', 'AuthController@check');
    Route::post('stringstore', 'AuthController@addStringStore');
    Route::get('stringstore', 'AuthController@getStringStore');
    Route::get('test', 'AuthController@check');
//     Route::get('test', function(){
//         return response()->json(['foo'=>'bar']);
//     });
});