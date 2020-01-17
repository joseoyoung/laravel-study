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


 



Route::get('/', [
    
    'as' => 'root',
    'uses' => 'WelcomeController@index'
]);

Route::get('home', [
    'as' => 'home',
    'uses' => 'WelcomeController@home'
]);

// Route::get('/home', function(){
//     return redirect('/');
// });

Auth::routes(); //로그인 관련

Route::get('locale', [
    'as' => 'locale',
    'uses' => 'WelcomeController@locale'
]); // 언어팩 관련




// /* Documents */
// Route::get('lessons/{image}', [
//     'as'   => 'lessons.image',
//     'uses' => 'LessonsController@image',
// ]);

// Route::get('lessons/{file?}', [
//     'as'   => 'lessons.show',
//     'uses' => 'LessonsController@show',
// ]);

/* Forum */
Route::get('tags/{slug}/articles', [
    'as'   => 'tags.articles.index',
    'uses' => 'ArticlesController@index'
]);

Route::resource('articles', 'ArticlesController');

Route::put('articles/{articles}/pick', [
    'as'   => 'articles.pick-best-comment',
    'uses' => 'ArticlesController@pickBest'
]);

/* Attachments */

Route::resource('files', 'AttachmentsController', ['only' => ['store', 'destroy']]);

// Route::post('upload', 'AttachmentsController@store2');

// /* Comments */
// Route::post('comments/{id}/vote', 'CommentsController@vote');
Route::resource('comments', 'CommentsController', ['only' => ['store', 'update', 'destroy']]);



// /* Social Login */
// Route::get('social/{provider}', [
//     'as'   => 'social.login',
//     'uses' => 'SocialController@execute',
// ]);






// Route::get('/home', [
//     'as' => 'home',
//     'uses' => 'WelcomeController@home'
// ]);

/* User Registration */
// Route::group(['prefix' => 'auth', 'as' => 'user.'], function(){
//     Route::get('register', [
//         'as' => 'create',
//         'uses' => 'Auth\AuthController@getRegister'
//     ]);
//     Route::post('register', [
//         'as'   => 'store',
//         'uses' => 'Auth\AuthController@postRegister'
//     ]);
// });

// /* Session */
// Route::group(['prefix' => 'auth', 'as' => 'session.'], function () {
//     Route::get('login', [
//         'as'   => 'create',
//         'uses' => 'Auth\AuthController@getLogin'
//     ]);
//     Route::post('login', [
//         'as'   => 'store',
//         'uses' => 'Auth\AuthController@postLogin'
//     ]);
//     Route::get('logout', [
//         'as'   => 'destroy',
//         'uses' => 'Auth\AuthController@getLogout'
//     ]);
// });

// /* Password Reminder */
// Route::group(['prefix' => 'password'], function () {
//     Route::get('remind', [
//         'as'   => 'reminder.create',
//         'uses' => 'Auth\PasswordController@getEmail'
//     ]);
//     Route::post('remind', [
//         'as'   => 'reminder.store',
//         'uses' => 'Auth\PasswordController@postEmail'
//     ]);
//     Route::get('reset/{token}', [
//         'as'   => 'reset.create',
//         'uses' => 'Auth\PasswordController@getReset'
//     ]);
//     Route::post('reset', [
//         'as'   => 'reset.store',
//         'uses' => 'Auth\PasswordController@postReset'
//     ]);
// });


// Route::get('/', function () {
//     //
// })->middleware('permission:edit.articles');

// Route::get('/home', 'HomeController@index')->name('home');


// Route::group(['prefix' => 'auth', 'as' => 'session.'], function () {
//     // Other route definitions...

//     /* Social Login */
//     Route::get('github', [
//         'as'   => 'github.login',
//         'uses' => 'Auth\LoginController@redirectToProvider'
//     ]);
//     Route::get('github/callback', [
//         'as'   => 'github.callback',
//         'uses' => 'Auth\LoginController@handleProviderCallback'
//     ]);
// });





