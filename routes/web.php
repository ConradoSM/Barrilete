<?php

Route::get('/','contenidoController@index')->name('home');
Route::get('/article/{id}/{seccion}/{titulo}','contenidoController@show')->name('article');
Route::get('/sec/{seccion}','contenidoController@sec')->name('section');
Route::get('/galleries','contenidoController@galleries')->name('galleries');
Route::get('/gallery/{id}/{titulo}','contenidoController@gallery')->name('gallery');

Route::get('/admin', function () {
    return view('adminlte');
});
Route::get('resizeImgFirst/{image}','ImageController@showImageArticulosIndexFirst')->name('imgFirst');
Route::get('resizeImgSecond/{image}','ImageController@showImageArticulosIndexSecond')->name('imgSecond');