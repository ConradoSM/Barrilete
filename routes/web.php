<?php

Route::get('/', 'contenidoController@home')->name('default');

Route::get('/article/{id}/{section}/{title}', 'contenidoController@showArticle')->name('article');
Route::get('/sec/{name}', 'contenidoController@searchSection')->name('section');
Route::get('/galleries', 'contenidoController@gallery')->name('galleries');
Route::get('/gallery/{id}/{titulo}', 'contenidoController@showGallery')->name('gallery');
Route::get('/poll/{id}/{titulo}', 'contenidoController@poll')->name('poll');
Route::post('/poll-vote', 'contenidoController@pollVote')->name('poll-vote');

Route::get('resizeImgFirst/{image}', 'ImageController@showImageArticulosIndexFirst')->name('imgFirst');
Route::get('resizeImgSecond/{image}', 'ImageController@showImageArticulosIndexSecond')->name('imgSecond');

Route::get('search', 'contenidoController@search')->name('search');

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
