<?php
use barrilete\Sections;
use Spatie\Sitemap\SitemapGenerator;
Auth::routes();
//SITEMAP
Route::get('sitemap', function () {
    SitemapGenerator::create('/')->writeToFile('sitemap.xml');
    return redirect('sitemap.xml');
});
//VIEW SECTIONS
View::composer(['layouts.barrilete'], function ($view) {
    $sections = Sections::where('name', '!=', 'Encuestas')->get();
    $view->with('sections', $sections);
});
//LOGOUT
Route::get('/logout', 'DashboardController@logout')->name('logout');
//HOME INDEX
Route::get('/', 'IndexController@home')->name('default');
//SECTIONS
Route::get('sec/{name}', 'SectionsController@searchSection')->name('section');
//SEARCH
Route::get('search', 'SearchController@search')->name('search');
//VIEW ARTICLES
Route::get('article/{id}/{section}/{title}', 'ArticlesController@show')->name('article');
//VIEW GALLERIES
Route::get('gallery/{id}/{title}', 'GalleriesController@showGallery')->name('gallery');
//VIEW POLLS
Route::get('poll/{id}/{title}', 'PollsController@poll')->name('poll');
Route::post('poll-vote', 'PollsController@pollVote')->name('poll-vote');
//GET COMMENTS
Route::get('comments/articles/{article_id}/{section_id}', 'CommentController@get')->name('getComments');
//DASHBOARD
Route::group(['middleware' => ['auth']], function () {
    //COMMENTS
    Route::post('comment/save', 'CommentController@save')->name('commentsSave');
    Route::post('comment/delete', 'CommentController@delete')->name('deleteComment');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    //DASHBOARD USER ARTICLES, GALLERIES, POLLS LIST
    Route::get('dashboard/view/articles/{id}', 'DashboardController@userArticles')->name('viewArticles');
    Route::get('dashboard/view/galleries/{id}', 'DashboardController@userGalleries')->name('viewGalleries');
    Route::get('dashboard/view/polls/{id}', 'DashboardController@userPolls')->name('viewPolls');
    //DASHBOARD SEARCH
    Route::get('search-auth', 'SearchController@searchAuth')->name('searchAuth');
    //DASHBOARD CREATE ARTICLE
    Route::get('dashboard/forms/articles', 'DashboardController@formArticle')->name('formCreateArticle');
    Route::post('dashboard/forms/articles/create', 'ArticlesController@create')->name('createArticle');
    //DASHBOARD DELETE ARTICLE
    Route::get('dashboard/delete/articles/{id}', 'ArticlesController@delete')->name('deleteArticle');
    //DASHBOARD UPDATE ARTICLE
    Route::get('dashboard/forms/articles/update/{id}', 'DashboardController@formArticle')->name('formUpdateArticle');
    Route::post('dashboard/update/articles/{id}', 'ArticlesController@update')->name('updateArticle');
    //DASHBOARD CREATE GALLERY
    Route::get('dashboard/forms/galleries', 'DashboardController@formGallery')->name('formGallery');
    Route::post('dashboard/forms/galleries/create', 'GalleriesController@createGallery')->name('createGallery');
    Route::post('dashboard/forms/galleries/photos', 'GalleriesController@createPhotos')->name('createPhotos');
    //DASHBOARD DELETE GALLERY
    Route::get('dashboard/delete/gallery/{id}', 'GalleriesController@delete')->name('deleteGallery');
    //DASHBOARD DELETE PHOTO GALLERY
    Route::get('dashboard/delete/gallery/photo/{id}', 'GalleryPhotosController@deletePhoto')->name('deletePhoto');
    //DASHBOARD UPDATE GALLERY
    Route::get('dashboard/forms/gallery/update/{id}', 'DashboardController@formUpdateGallery')->name('formUpdateGallery');
    Route::post('dashboard/update/gallery', 'GalleriesController@update')->name('updateGallery');
    Route::post('dashboard/update/gallery/photo/title', 'GalleryPhotosController@updateTitlePhotoGallery')->name('updateTitlePhotoGallery');
    Route::post('dashboard/update/gallery/photo', 'GalleryPhotosController@updatePhoto')->name('updatePhoto');
    Route::get('dashboard/update/gallery/more/photos/{id}', 'GalleriesController@morePhotos')->name('morePhotos');

    //SECTIONS ADMIN
    Route::get('dashboard/sections/list', 'SectionsController@index')->name('sectionsIndex');
    Route::get('dashboard/sections/edit/{id}', 'SectionsController@edit')->name('editSection');
    Route::post('dashboard/sections/update/{id}', 'SectionsController@update')->name('updateSection');
    Route::get('dashboard/sections/delete/{id}', 'SectionsController@delete')->name('deleteSection');
    Route::get('dashboard/sections/new', 'SectionsController@newSection')->name('newSection');
    Route::post('dashboard/sections/create', 'SectionsController@create')->name('createSection');

    //DASHBOARD USERS ADMIN
    Route::get('dashboard/users/options', 'UsersController@options')->name('options');
    Route::get('dashboard/users/account/{id}', 'UsersController@account')->name('account');
    Route::get('dashboard/users/list', 'UsersController@users')->name('users');
    Route::get('dashboard/users/show/{id}', 'UsersController@show')->name('showUser');
    Route::get('dashboard/users/edit/{id}', 'UsersController@edit')->name('editUser');
    Route::post('dashboard/users/update', 'UsersController@update')->name('updateUser');
    Route::get('dashboard/users/delete/{id}', 'UsersController@delete')->name('deleteUser');
    Route::get('dashboard/users/make-admin/{id}', 'UsersController@makeAdmin')->name('makeAdmin');
    Route::get('dashboard/users/delete-admin/{id}', 'UsersController@deleteAdmin')->name('deleteAdmin');
    //DASHBOARD CREATE POLL
    Route::get('dashboard/forms/polls', 'DashboardController@formPoll')->name('formPoll');
    Route::post('dashboard/forms/polls/create', 'PollsController@createPoll')->name('createPoll');
    Route::post('dashboard/forms/polls/options', 'PollsController@createOptions')->name('createOptions');
    //DASHBOARD DELETE POLL
    Route::get('dashboard/delete/poll/{id}', 'PollsController@delete')->name('deletePoll');
    //DASHBOARD UPDATE POLL
    Route::get('dashboard/forms/polls/update/{id}', 'PollsController@formUpdatePoll')->name('formUpdatePoll');
    Route::post('dashboard/update/polls', 'PollsController@update')->name('updatePoll');
    Route::post('dashboard/update/polls/options', 'PollsController@updateOption')->name('updatePollOption');
    Route::get('dashboard/delete/polls/option/{id}', 'PollsController@deleteOption')->name('deleteOption');
    Route::get('dashboard/more/polls/options/{id}', 'PollsController@addOptions')->name('addOptions');

    //DASHBOARD PREVIEWS
    Route::get('dashboard/forms/articles/preview/{id}', 'ArticlesController@preview')->name('previewArticle');
    Route::get('dashboard/forms/gallery/preview/{id}', 'GalleriesController@preview')->name('previewGallery');
    Route::get('dashboard/forms/poll/preview/{id}', 'PollsController@preview')->name('previewPoll');

    //DASHBOARD CONTENT PUBLISH
    Route::get('dashboard/publish/articles/{id}', 'ArticlesController@publish')->name('publishArticle');
    Route::get('dashboard/publish/gallery/{id}', 'GalleriesController@publishGallery')->name('publishGallery');
    Route::get('dashboard/publish/poll/{id}', 'PollsController@publishPoll')->name('publishPoll');

    //DASHBOARD CONTENT UNPUBLISHED
    Route::get('dashboard/unpublished/articles','ArticlesController@unpublished')->name('unpublishedArticles');
    Route::get('dashboard/unpublished/galleries','GalleriesController@unpublishedGalleries')->name('unpublishedGalleries');
    Route::get('dashboard/unpublished/polls','PollsController@unpublishedPolls')->name('unpublishedPolls');
});
