<?php

use Illuminate\Http\Request;
//routes work
Route::get('getRoles','RolesController@index');
Route::post('storeRole','RolesController@store');
Route::post('updateRole/{id}','RolesController@update');
Route::get('showRole/{id}','RolesController@show');
Route::post('deleteRole/{id}','RolesController@destroy');
//routes work
Route::get('getUsers','UsersController@index');
Route::post('SignIn','UsersController@SignIn');
Route::post('SignUp','UsersController@SignUp');
Route::post('deleteUser/{id}','UsersController@destroy');
//routes work
Route::post('storeOrder','OrdersController@store');
Route::get('showOrder/{id}','OrdersController@show');
Route::post('deleteOrder/{id}','OrdersController@destroy');

Route::post('storeProduct','ProductsController@store');
Route::post('updateProduct/{id}','ProductsController@update');
Route::get('showProduct/{id}','ProductsController@show');
Route::post('deleteProduct/{id}','ProductsController@destroy');

Route::any('{path?}', 'MainController@index')->where("path", ".+");
