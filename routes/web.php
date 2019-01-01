<?php
use barrilete\Sections;
//HOME INDEX
    Route::get('/', 'IndexController@home')->name('default');
//VIEW SECTIONS
    View::composer(['layouts.barrilete'], function($view) {
        $sections = Sections::all();
        $view->with('sections', $sections);
    });
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

//DASHBOARD ADMIN ARTICLES
    //DASHBOARD CREATE ARTICLE
        Route::get('/dashboard/forms/articles','DashboardController@formArticle')->middleware('auth')->name('formCreateArticle');
        Route::post('/dashboard/forms/articles/create','ArticlesController@createArticle')->middleware('auth')->name('createArticle');
    //DASHBOARD DELETE ARTICLE
        Route::get('/dashboard/delete/articles/{id}','ArticlesController@deleteArticle')->middleware('auth')->name('deleteArticle');
    //DASHBOARD UPDATE ARTICLE
        Route::get('/dashboard/forms/articles/update/{id}','DashboardController@formArticle')->middleware('auth')->name('formUpdateArticle');
        Route::post('/dashboard/update/articles/{id}','ArticlesController@updateArticle')->middleware('auth')->name('updateArticle');

//DASHBOARD ADMIN GALLERIES
    //DASHBOARD CREATE GALLERY
        Route::get('/dashboard/forms/galleries','DashboardController@formGallery')->middleware('auth')->name('formGallery');
        Route::post('/dashboard/forms/galleries/create','GalleriesController@createGallery')->middleware('auth')->name('createGallery');
        Route::post('/dashboard/forms/galleries/photos','GalleriesController@createPhotos')->middleware('auth')->name('createPhotos');
    //DASHBOARD DELETE GALLERY
        Route::get('/dashboard/delete/gallery/{id}','GalleriesController@deleteGallery')->middleware('auth')->name('deleteGallery');
    //DASHBOARD DELETE PHOTO GALLERY
        Route::post('/dashboard/delete/gallery/photo/{id}','GalleryPhotosController@deletePhotoGallery')->middleware('auth')->name('deletePhotoGallery');
    //DASHBOARD UPDATE GALLERY
        Route::get('/dashboard/forms/gallery/update/{id}','DashboardController@formUpdateGallery')->middleware('auth')->name('formUpdateGallery');
        Route::post('/dashboard/update/gallery/title/{id}','GalleriesController@updateTitleGallery')->middleware('auth')->name('updateTitleGallery');
        Route::post('/dashboard/update/gallery/article_desc/{id}','GalleriesController@updateArticleDescGallery')->middleware('auth')->name('updateArticleDescGallery');
        Route::post('/dashboard/update/gallery/photo/title/{id}','GalleryPhotosController@updateTitlePhotoGallery')->middleware('auth')->name('updateTitlePhotoGallery');     

//DASHBOARD ADMIN POLLS
    //DASHBOARD CREATE POLL
        Route::get('/dashboard/forms/polls','DashboardController@formPoll')->middleware('auth')->name('formPoll');
        Route::post('/dashboard/forms/polls/create','PollsController@createPoll')->middleware('auth')->name('createPoll');
        Route::post('/dashboard/forms/polls/options','PollsController@createOptions')->middleware('auth')->name('createOptions');
    //DASHBOARD DELETE POLL
        Route::get('/dashboard/delete/poll/{id}','PollsController@deletePoll')->middleware('auth')->name('deletePoll');

//DASHBOARD PREVIEWS
    Route::get('/dashboard/forms/articles/preview/{id}','ArticlesController@previewArticle')->middleware('auth')->name('previewArticle');
    Route::get('/dashboard/forms/gallery/preview/{id}','GalleriesController@previewGallery')->middleware('auth')->name('previewGallery');
    Route::get('/dashboard/forms/poll/preview/{id}','PollsController@previewPoll')->middleware('auth')->name('previewPoll');

//DASHBOARD CONTENT PUBLISH
    Route::get('/dashboard/publish/articles/{id}','ArticlesController@publishArticle')->middleware('auth')->name('publishArticle');
    Route::get('/dashboard/publish/gallery/{id}','GalleriesController@publishGallery')->middleware('auth')->name('publishGallery');
    Route::get('/dashboard/publish/poll/{id}','PollsController@publishPoll')->middleware('auth')->name('publishPoll');