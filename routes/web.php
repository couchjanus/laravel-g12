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
    Route::get('invitations', 'Admin\InvitationsController@index')->name('showInvitations');

    Route::post('invite/{id}', 'Admin\InvitationsController@sendInvite')
    ->name('send.invite');
    
});

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/login/writer', 'Auth\LoginController@showWriterLoginForm');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');
Route::get('/register/writer', 'Auth\RegisterController@showWriterRegisterForm');




// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('hasInvitation');
// Route::get('register/request', 'Auth\RegisterController@requestInvitation')->name('requestInvitation');


Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/login/writer', 'Auth\LoginController@writerLogin');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin');
Route::post('/register/writer', 'Auth\RegisterController@createWriter');

Auth::routes();
// Auth::routes(['verify' => true]);

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


use Illuminate\Support\Facades\Log;

Route::get('/test-log', function () {    
    Log::info('This is an info message that someone has arrived at the welcome page.');
    // Log::channel('slack')->info('This is an informative Slack message.');

    // Log::stack(['single', 'stderr'])->critical('I need ice-cream!');

    // Log::alert('This page was loaded', ['user' => 3, 'previous_page' => 'www.google.com']);

    // Log::emergency($message);
    // Log::alert($message);
    // Log::critical($message);
    // Log::error($message);
    // Log::warning($message);
    // Log::notice($message);
    // Log::info($message);
    // Log::debug($message);

    return '<h1>Welcome back User</h1>';
});

Route::get('/reminder', function () {
    // return new App\Mail\Reminder();
    return new App\Mail\Reminder('Blahamuha');

});

use Illuminate\Support\Facades\Mail;
use App\Mail\Reminder;

Route::get('/send-test', function () {
    Mail::to('kuku@my.cat')->send(new Reminder('Blahamuha'));
    return 'Email was sent';
});

Route::get('register/request', 'Auth\RegisterController@requestInvitation')->name('requestInvitation');

Route::post('invitations', 'InvitationsController@store')->middleware('guest')->name('storeInvitation');

Route::get('/invite', function () {
    // $invoice = App\Order::find(1);
    // return (new App\Mail\InvitationMail())->render();
    // return (new App\Mail\InvitationMail('http://localhost:8000/register/writer?invitation_token=81c146559d06248a18c36c699e3efcd8'))->render();
    $url = App\Invitation::find(1)->getLink();
    return (new App\Mail\InvitationMail($url))->render();
});

