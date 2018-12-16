<?php

Route::get('/', 'contenidoController@index')->name('home');

Route::get('/article/{id}/{seccion}/{titulo}', 'contenidoController@show')->name('article');
Route::get('/sec/{seccion}', 'contenidoController@sec')->name('section');
Route::get('/galleries', 'contenidoController@galleries')->name('galleries');
Route::get('/gallery/{id}/{titulo}', 'contenidoController@gallery')->name('gallery');
Route::get('/poll/{id}/{titulo}', 'contenidoController@poll')->name('poll');
Route::post('/poll-vote', 'contenidoController@votar')->name('poll-vote');

Route::get('resizeImgFirst/{image}', 'ImageController@showImageArticulosIndexFirst')->name('imgFirst');
Route::get('resizeImgSecond/{image}', 'ImageController@showImageArticulosIndexSecond')->name('imgSecond');

Route::get('search', 'contenidoController@search')->name('search');
