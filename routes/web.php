<?php

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
  //  return view('welcome');

//});

Route::get('/', 'Controller@index');
Route::post('/','Controller@newdata');

Route::get('/ajax', 'Controller@getdata');
Route::post('/ajax', 'Controller@getdata');

Route::get('/get_clasters', 'Controller@get_clasters' );
Route::post('/get_clasters', 'Controller@get_clasters' );

//Route::get('/debug');
Route::post('/get_areas','Controller@get_areas');
Route::post('/features_rel', 'Controller@features_rel');
Route::post('/get_groups', 'Controller@get_groups');
