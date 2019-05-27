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

Route::get('test', 'GadgetTestController@index');

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

Route::middleware('web')->group(function () {
    Route::middleware('auth:admin')->prefix('admin')->group(function () {
        Route::get('', 'Admin\DashboardController');
        Route::get('status', 'Admin\PostController@getPostsByStatus')->name('posts.status');
        Route::get('sort', 'Admin\PostController@sortPostsByDate')->name('posts.sort');
        Route::resource('posts', 'Admin\PostController');
        Route::resource('tags', 'Admin\TagController');
        Route::resource('categories', 'Admin\CategoryController');
        Route::resource('users', 'Admin\UserController');
        Route::resource('admins', 'Admin\AdminsController');
        Route::resource('writers', 'Admin\WritersController');

        Route::resource('permissions', 'Admin\PermissionController');
        Route::resource('roles', 'Admin\RoleController');

        Route::get('trashed', 'Admin\UserController@trashed')->name('users.trashed');
        Route::get('trashed-admins', 'Admin\AdminsController@trashed')->name('admins.trashed');
        Route::get('trashed-writers', 'Admin\WritersController@trashed')->name('writers.trashed');
        Route::delete('user-destroy/{id}', 'Admin\UserController@userDestroy')->name('user.force.destroy');
        
        Route::delete('admin-destroy/{id}', 'Admin\AdminsController@userDestroy')->name('admin.force.destroy');
        Route::delete('writer-destroy/{id}', 'Admin\WritersController@userDestroy')->name('writer.force.destroy');

        Route::post('restore/{id}', 'Admin\UserController@restore')->name('users.restore');
        Route::post('restore-admin/{id}', 'Admin\AdminsController@restore')->name('admins.restore');
        Route::post('restore-writer/{id}', 'Admin\WritersController@restore')->name('writers.restore');
        
        Route::get('invitations', 'Admin\InvitationsController@index')->name('showInvitations');
        Route::post('invite/{id}', 'Admin\InvitationsController@sendInvite')
        ->name('send.invite');
        Route::get('feedbacks', 'Admin\FeedbackController@index')->name('feedbacks.index');
        Route::get('feedbacks/delete/{id}', 'Admin\FeedbackController@destroy');
        
    });
});

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm')->name('login.admin');
Route::get('/login/writer', 'Auth\LoginController@showWriterLoginForm');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');
Route::get('/register/writer', 'Auth\RegisterController@showWriterRegisterForm');

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/login/writer', 'Auth\LoginController@writerLogin');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin');
Route::post('/register/writer', 'Auth\RegisterController@createWriter');

// Auth::routes();
Auth::routes(['verify' => true]);

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

Route::get('register/request', 'Auth\RegisterController@requestInvitation')->name('requestInvitation');

Route::post('invitations', 'InvitationsController@store')->middleware('guest')->name('storeInvitation');

// Socialite Register Routes

Route::get('social/{provider}', 'Auth\SocialController@redirect')->name('social.redirect');
Route::get('social/{provider}/callback', 'Auth\SocialController@callback')->name('social.callback');

// Feedback
Route::get('/feedback', 'FeedbackController@create');
Route::post('/feedback/create', 'FeedbackController@store');

