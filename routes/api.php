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
    Route::resource('quan', 'App\Http\Controllers\Api\V1\QuanController')->only(['index','show', 'destroy']);
    Route::resource('san', 'App\Http\Controllers\Api\V1\SanController')->only(['index','show']);
    Route::resource('datsans', 'App\Http\Controllers\Api\V1\DatSanController')->only(['store']);
    
    Route::get('getListDatSanByUserToken', 'App\Http\Controllers\Api\V1\DatSanController@getListDatSanByUserToken');
    Route::put('editUserByToken', 'App\Http\Controllers\Api\V1\UserController@editUserByToken');
    Route::post('registerUser', 'App\Http\Controllers\Api\V1\UserController@registerUser');
    
    Route::post('loginUser', 'App\Http\Controllers\Api\V1\UserController@loginUser')->name('loginUser');
    Route::get('checkTokenUser', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenUser');

    // chủ quán : quản lý các quán của mình 
    Route::get('checkTokenInnkeeper', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenInnkeeper');
    Route::post('loginInnkeeper', 'App\Http\Controllers\Api\V1\ChuQuanController@loginInnkeeper');
    Route::post('registerInnkeeper', 'App\Http\Controllers\Api\V1\ChuQuanController@registerInnkeeper');
    Route::put('editInnkeeperByToken', 'App\Http\Controllers\Api\V1\ChuQuanController@editInnkeeperByToken');

    Route::get('getListQuansByTokenInnkeeper', 'App\Http\Controllers\Api\V1\QuanController@getListQuansByTokenInnkeeper');
    Route::get('getListQuansByTokenInnkeeperChuaPheDuyet', 'App\Http\Controllers\Api\V1\QuanController@getListQuansByTokenInnkeeperChuaPheDuyet');

    Route::post('addQuanByInnkeeper', 'App\Http\Controllers\Api\V1\QuanController@addQuanByInnkeeper');
    Route::post('editQuanByTokenInnkeeper', 'App\Http\Controllers\Api\V1\QuanController@editQuanByTokenInnkeeper');
    Route::post('addSanByInnkeeper', 'App\Http\Controllers\Api\V1\SanController@addSanByInnkeeper');

    Route::post('checkTokenInnkeeperAndIdquan', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenInnkeeperAndIdquan');
    Route::delete('deleteQuanChuaduyetByInnkeeper', 'App\Http\Controllers\Api\V1\QuanController@deleteQuanChuaduyetByInnkeeper');
    Route::put('editSanByInnkeeper', 'App\Http\Controllers\Api\V1\SanController@editSanByInnkeeper');

    Route::post('checkTokenInnkeeperAndIdsan', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenInnkeeperAndIdsan');
    Route::post('getDanhThuByInnkeeper', 'App\Http\Controllers\Api\V1\DanhThuController@getDanhThuByInnkeeper');
    Route::post('thayDoiDatSanByInnkeeper', 'App\Http\Controllers\Api\V1\DatSanController@thayDoiDatSanByInnkeeper');
    Route::post('getListDatSanByInnkeeper', 'App\Http\Controllers\Api\V1\DatSanController@getListDatSanByInnkeeper');

    Route::post('thu', 'App\Http\Controllers\Api\V1\CheckTokenController@thu');
    
    // admin : quản lý các quán
    Route::get('checkTokenAdmin', 'App\Http\Controllers\Api\V1\CheckTokenController@checkTokenAdmin');
    Route::post('loginAdmin', 'App\Http\Controllers\Api\V1\AdminController@loginAdmin');
    Route::put('editAdminByToken', 'App\Http\Controllers\Api\V1\AdminController@editAdminByToken');
    Route::get('getListQuansDaPheDuyetByTokenAdmin', 'App\Http\Controllers\Api\V1\QuanController@getListQuansDaPheDuyetByTokenAdmin');
    Route::get('getListQuansChuaPheDuyetByTokenAdmin', 'App\Http\Controllers\Api\V1\QuanController@getListQuansChuaPheDuyetByTokenAdmin');
    Route::put('UpdateTrangThaiQuanTokenAdmin', 'App\Http\Controllers\Api\V1\QuanController@UpdateTrangThaiQuanTokenAdmin');
    Route::post('getDanhThuByAdmin', 'App\Http\Controllers\Api\V1\DanhThuController@getDanhThuByAdmin');
    Route::post('getDanhThuListQuanByAdmin', 'App\Http\Controllers\Api\V1\DanhThuController@getDanhThuListQuanByAdmin');
            
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
