<?php

Route::get('/', 'IndexController@home')->name('default');

Route::get('/sec/{name}', 'SectionsController@searchSection')->name('section');
Route::get('search', 'SearchController@search')->name('search');
Route::get('/article/{id}/{section}/{title}', 'ArticlesController@showArticle')->name('article');
Route::get('/galleries', 'GalleriesController@galleries')->name('galleries');
Route::get('/gallery/{id}/{titulo}', 'GalleriesController@showGallery')->name('gallery');
Route::get('/poll/{id}/{titulo}', 'PollsController@poll')->name('poll');
Route::post('/poll-vote', 'PollsController@pollVote')->name('poll-vote');

Route::get('resizeImgFirst/{image}', 'ImageController@showImageArticulosIndexFirst')->name('imgFirst');
Route::get('resizeImgSecond/{image}', 'ImageController@showImageArticulosIndexSecond')->name('imgSecond');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');