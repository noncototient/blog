<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('posts/all');
});

Route::get('/home', function () {
    return redirect()->route('post.index');
});

Auth::routes();

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function() {
    Route::get('posts', 'PostController@index')->name('post.index');
    Route::get('post/create', 'PostController@create')->name('post.create');
    Route::post('post/create', 'PostController@store')->name('post.store');
    Route::get('post/edit/{post}', 'PostController@edit')->name('post.edit');
    Route::patch('post/edit/{post}', 'PostController@update')->name('post.update');
    Route::delete('post/delete/{post}', 'PostController@destroy')->name('post.delete');
    Route::patch('trash/post/restore/{id}', 'PostController@restore')->name('post.restore');
    Route::delete('post/force-delete/{post}', 'PostController@forceDelete')->name('post.force.delete');
    Route::get('trash', 'PostController@trash')->name('post.trash');
});

Route::get('posts/all', 'PostController@all')->name('post.all');
Route::get('post/show/{post}', 'PostController@show')->name('post.show');
