<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AddressSearchController;
use App\Http\Controllers\AddressController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/googlemap', 'App\Http\Controllers\AddressController@index');
Route::get('/getaddress', 'App\Http\Controllers\AddressController@GetAddress');

/***** Using for Ajax transfer data to controller (dropdown select) *****/
Route::post('/citylinkarea', 'App\Http\Controllers\AddressController@citylinkarea');
//Route::get('/arealinkroute', 'App\Http\Controllers\AddressController@arealinkroute');