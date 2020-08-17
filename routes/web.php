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
Route::get('autocomplete', 'SearchController@autocomplete')->name('autocomplete');
//USER MENU
Route::get('user-menu', 'UsersController@menu')->name('user-menu');
Route::get('notifications', 'UsersController@notifyReactions')->name('notifyReactions');
Route::get('inbox', 'UsersController@notifyMessages')->name('notifyMessages');
//VIEW ARTICLES
Route::get('article/{id}/{section}/{title}', 'ArticlesController@show')->name('article');
//VIEW GALLERIES
Route::get('gallery/{id}/{title}', 'GalleriesController@showGallery')->name('gallery');
//VIEW POLLS
Route::get('poll/{id}/{title}', 'PollsController@poll')->name('poll');
Route::post('poll-vote', 'PollsController@pollVote')->name('poll-vote');
//GET COMMENTS
Route::get('comments/articles/{article_id}/{section_id}', 'CommentController@get')->name('getComments');
//ARTICLES USER REACTION
Route::post('article/reaction/save', 'ArticlesReactionController@save')->name('articleReactionSave');
//NEWSLETTER SUBSCRIBE
Route::post('newsletters/subscribe', 'NewsletterController@create')->name('newslettersSubscribe');
Route::get('newsletter/delete/{email}', 'NewsletterController@delete')->name('NewsletterDelete');
//DASHBOARD
Route::group(['middleware' => ['auth']], function () {
    //USERS DASHBOARD
    Route::get('users/dashboard', 'UsersController@dashboard')->name('users.dashboard');
    //COMMENTS
    Route::post('comment/save', 'CommentController@save')->name('commentsSave');
    Route::post('comment/update', 'CommentController@update')->name('commentUpdate');
    Route::post('comment/delete', 'CommentController@delete')->name('deleteComment');
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    //COMMENTS USER REACTIONS
    Route::post('reaction/save', 'CommentsUserReactionsController@save')->name('commentReactionSave');
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
    Route::post('dashboard/myaccount/update', 'UsersController@myAccountUpdate')->name('myAccountUpdate');
    Route::get('dashboard/myaccount/configuration', 'UsersController@myAccountConfig')->name('MyAccountConfig');
    Route::get('dashboard/myaccount/password/edit', 'UsersController@editMyPassword')->name('editMyPassword');
    Route::post('dashboard/myaccount/password/update', 'UsersController@updatePassword')->name('updatePassword');
    Route::get('dashboard/myaccount/messages/box/{box}', 'MessagesController@myMessages')->name('myMessages');
    Route::get('dashboard/myaccount/messages/write', 'MessagesController@writeMessage')->name('writeMessage');
    Route::post('dashboard/myaccount/messages/save', 'MessagesController@save')->name('saveMessage');
    Route::get('dashboard/myaccount/messages/getUsers', 'UsersController@getUsers')->name('getUsers');
    Route::get('dashboard/myaccount/messages/message/{id}', 'MessagesController@getConversationById')->name('getConversation');
    Route::get('dashboard/myaccount/messages/message/delete/{id}', 'MessagesController@delete')->name('deleteMessage');
    Route::post('dashboard/users/update', 'UsersController@update')->name('updateUser');
    Route::get('dashboard/users/delete/{id}', 'UsersController@delete')->name('deleteUser');
    Route::post('dashboard/users/delete', 'UsersController@deleteOwnAccount')->name('deleteOwnAccount');
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
