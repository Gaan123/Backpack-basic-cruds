<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::middleware('optimizeImages')->group(function () {
        Route::post('/file/upload', 'AdminController@uploadFile')->name('backpack.upload');
    });
    Route::get('/medias', 'AdminController@getMedia')->name('backpack.media');
    Route::patch('/media/{media}', 'AdminController@postCaption')->name('backpack.media');
    Route::get('/article/trash', 'ArticleCrudController@getTrashedArticle')->name('backpack.article');
    Route::post('/medias', 'AdminController@deleteMedia')->name('backpack.media.delete');
    Route::crud('media', 'MediaCrudController');
}); // this should be the absolute last line of this file