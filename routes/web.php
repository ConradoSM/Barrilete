<?php
//HOME INDEX
Route::get('/', 'IndexController@home')->name('default');
//SECTIONS
Route::get('/sec/{name}', 'SectionsController@searchSection')->name('section');
//SEARCH
Route::get('search', 'SearchController@search')->name('search');
//VIEW ARTICLES
Route::get('/article/{id}/{section}/{title}', 'ArticlesController@showArticle')->name('article');
//VIEW GALLERIES
Route::get('/galleries', 'GalleriesController@galleries')->name('galleries');
Route::get('/gallery/{id}/{titulo}', 'GalleriesController@showGallery')->name('gallery');
//VIEW POLLS
Route::get('/poll/{id}/{titulo}', 'PollsController@poll')->name('poll');
Route::post('/poll-vote', 'PollsController@pollVote')->name('poll-vote');
//AUTH ROUTES
Auth::routes();
//DASHBOARD
Route::get('/dashboard', 'DashboardController@index')->middleware('auth')->name('dashboard');
//DASHBOARD USER ARTICLES, GALLERIES, POLLS LIST
Route::get('/dashboard/view/articles/{id}', 'DashboardController@userArticles')->middleware('auth')->name('viewArticles');
Route::get('/dashboard/view/galleries/{id}', 'DashboardController@userGalleries')->middleware('auth')->name('viewGalleries');
Route::get('/dashboard/view/polls/{id}', 'DashboardController@userPolls')->middleware('auth')->name('viewPolls');
//DASHBOARD CREATE ARTICLE
Route::get('/dashboard/forms/articles','DashboardController@formArticle')->middleware('auth')->name('formArticle');
Route::post('/dashboard/forms/articles/create','ArticlesController@createArticle')->middleware('auth')->name('createArticle');
//PREVIEWS
Route::get('/dashboard/forms/articles/preview/{id}','ArticlesController@previewArticle')->middleware('auth')->name('previewArticle');
Route::get('/dashboard/forms/gallery/preview/{id}','GalleriesController@previewGallery')->middleware('auth')->name('previewGallery');
Route::get('/dashboard/forms/poll/preview/{id}','PollsController@previewPoll')->middleware('auth')->name('previewPoll');