<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    /// user 
    Route::resource('quan', 'App\Http\Controllers\Api\V1\QuanController')->only(['index','show']);
    Route::resource('san', 'App\Http\Controllers\Api\V1\SanController')->only(['index']);
    Route::resource('datsans', 'App\Http\Controllers\Api\V1\DatSanController')->only(['store']);
    
    Route::get('getListDatSanByUserToken', 'App\Http\Controllers\Api\V1\DatSanController@getListDatSanByUserToken');
    Route::put('editUserByToken', 'App\Http\Controllers\Api\V1\UserController@editUserByToken');
    Route::post('registerUser', 'App\Http\Controllers\Api\V1\UserController@registerUser');
    
    Route::post('loginUser', 'App\Http\Controllers\Api\V1\UserController@loginUser')->name('loginUser');
    Route::get('checkTokenUser', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenUser');

    // chủ quán : quản lý các quán của mình 
    


    // admin : quản lý các quán 
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
