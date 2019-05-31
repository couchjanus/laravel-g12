<?php
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Support\Facades\Redis;

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
Route::post('/submit', 'TestController@submit');

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
        Route::any('users/search',function(){
            $q = Input::get ( 'q' );
            $users = User::where('name','LIKE','%'.$q.'%')->orWhere('email','LIKE','%'.$q.'%')->paginate();
            if(count($users) > 0) {
                return view('admin.users.index')->withUsers($users)->withQuery($q);
            } else {
                  return redirect(route('users.index'))->withType('warning')->withMessage('No Details found. Try to search again !');
            }
          });
        
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

Route::get('articles', 'ArticleController@index')->name('articles.index');
Route::get('articles/{id}','ArticleController@show')->name('articles.show'); 


use \App\Repositories\ElasticsearchArticleRepositoryInterface;

Route::get('/search', function (ElasticsearchArticleRepositoryInterface $repository) {
   
   $articles = $repository->search((string) request('q'));

//    dump($articles);
   return view('articles.index', [
       'posts' => $articles,
       'title' => 'Awesome Blog'
   ]);
});

Route::get('/test-cache', function () {
    // if (Cache::has('key')) {
    //     $item = \Cache::get('key');
    //     dump($item);
    // } else {
    //     echo "Not Key yet...";
    // }

    // $value = Cache::get('key', function () {
    //     return \DB::table('posts')->get();
    // });

    $minutes = 1;

    // Cache::put('posts', \DB::table('posts')->get(), $minutes);

    $expiresAt = \Carbon\Carbon::now()->addMinutes(1);
    // Cache::put('posts', \DB::table('posts')->get(), $expiresAt);
    
    // Cache::add('posts', \DB::table('posts')->get(), $expiresAt);
    // Cache::forever('posts', \DB::table('posts')->get());

    // $value = Cache::pull('posts');

    // Cache::forget('posts');

    // Cache::flush();
    
    // $value = cache('posts');
    // cache(['posts' => \DB::table('posts')->get()], $minutes);

       
    // $value = Cache::remember('posts', $minutes, function () {
    //     return \DB::table('posts')->get();
    // });

    // $value = Cache::rememberForever('posts', function() {
    //     return \DB::table('posts')->get();
    // });
    
    dump($value);

});

  
 
Route::get('/test-redis', function () {

    return Cache::remember('posts.all', 60 * 60 * 24, function () {
        return \App\Post::all();
    });
    // redis has posts.all key exists 
    // posts found then it will return all post without touching the database
    // if ($posts = Redis::get('posts.all')) {
    //     return json_decode($posts);
    // }

    if ($posts = Redis::command('get',['posts.all'])) {
        return json_decode($posts);
    }
     
    // get all post
    $posts = \App\Post::all();
 
    // store into redis
    Redis::set('posts.all', $posts);
 
    // return all posts
    // dump($posts);
    // return $posts;
    
    
    // store data into redis for next 24 hours
    Redis::setex('posts.all', 60 * 60 * 24, $posts);

    // return all posts
    return $posts;
});


Route::get('redis-login', function() {
  return view('redis.login');
});

Route::post('redis-login', function(Request $request) {
  $redis = Redis::connection();
  $redis->hset('user', 'email', $request->get('email'));
  $redis->hset('user', 'name', $request->get('name'));
  return redirect(route('r-view'));
})->name('r-login');

Route::get('redis-view', function() {
  $redis = Redis::connection();
  $name = $redis->hget('user', 'name');
  $email = $redis->hget('user', 'email');
  echo 'Hello ' . $name . '. Your email is ' . $email;
  dump($redis->hgetall('user'));
})->name('r-view');


// Route::get('blog/{post}','PostController@showFromCache')->name('blog.show'); 

