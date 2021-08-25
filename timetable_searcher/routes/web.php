<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::post( 'confirm', 'App\Http\Controllers\SearchController@post' );

Route::get( 'submit', function() {
    return view( 'submit' );
});

Route::get( '/', function() {
    return redirect( '/submit' );
});

Route::get( '/{depr_poll}/{dest_poll}/{line_num}/{holiday?}', 'App\Http\Controllers\SearchController@index' );

Route::get('timetable', function() {
    return view('app');
});