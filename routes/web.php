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
    return view('welcome');
});

Route::get('about', 'AboutController')->name('about');
Route::get('contact-us', 'ContactController@index')->name('contact');



// Blog
Route::get('/get-by-category', function () {
    $posts = App\Post::where('status', 2)
    ->with('category')
    ->get();
    dump($posts);
});

Route::prefix('blog')->group(function () {
    Route::get('', 'PostController@index');
    Route::get('{slug}', 'PostController@show')->name('blog.show');
    Route::get('category/{id}', 'PostController@getPostsByCategory')->name('blog.category');
});



// admin

Route::prefix('admin')->group(function () {
    Route::get('', 'Admin\DashboardController');
    Route::get('status', 'Admin\PostController@getPostsByStatus')->name('posts.status');
    Route::get('sort', 'Admin\PostController@sortPostsByDate')->name('posts.sort');
    Route::resource('posts', 'Admin\PostController');
    Route::resource('tags', 'Admin\TagController');
    Route::resource('categories', 'Admin\CategoryController');
    Route::resource('users', 'Admin\UserController');
    Route::get('trashed', 'Admin\UserController@trashed')->name('users.trashed');
    Route::delete('user-destroy/{id}', 'Admin\UserController@userDestroy')->name('user.force.destroy');
    Route::post('restore/{id}', 'Admin\UserController@restore')->name('users.restore');
});

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/login/writer', 'Auth\LoginController@showWriterLoginForm');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');
Route::get('/register/writer', 'Auth\RegisterController@showWriterRegisterForm');

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/login/writer', 'Auth\LoginController@writerLogin');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin');
Route::post('/register/writer', 'Auth\RegisterController@createWriter');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/session', 'HomeController@showRequest')->name('session');

Route::view('/writer', 'staff.writer')->middleware('auth');


Route::middleware('web')->group(function () {
    Route::middleware('auth')->prefix('profile')->group(function () {
        Route::get('', 'ProfileController@index')
            ->name('profile');
        Route::put('information', 'ProfileController@store')
            ->name('profile.info.store');
        Route::get('security', 'ProfileController@showPasswordForm')
            ->name('profile.security');
        Route::put('security', 'ProfileController@storePassword')
            ->name('profile.security.store');
        Route::get('delete-account', 'ProfileController@showDeleteAccountConfirmation')
            ->name('profile.delete.show');
        Route::delete('delete-account', 'ProfileController@deleteAccount')
            ->name('profile.remove');
    });
});