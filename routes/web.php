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


Route::get('test', function () {
    $sampleArray = ['one', 'two', 'three'];
    dump($sampleArray);
    dump(url()->current());
    dump(url()->full());
    dump(url()->previous());

    return 'Test Dump Server';
});

Route::get('test-dd', function () {
    $sampleArray = ['one', 'two', 'three'];
    Debugbar::info($sampleArray);

    Debugbar::info(url()->current());
    Debugbar::info(url()->full());
    Debugbar::info(url()->previous());

    Debugbar::error('Error!');
    Debugbar::warning('Watch out…');
    Debugbar::addMessage('Another message', 'mylabel');
    
    Debugbar::startMeasure('render','Time for rendering');

    Debugbar::stopMeasure('render');

    Debugbar::addMeasure('Count Time', LARAVEL_START, microtime(true));
    Debugbar::startMeasure('Count Time','Time for Laravel start');
    Debugbar::stopMeasure('Count Time');

    Debugbar::measure('My long operation', function() {
        // Do something…
        $total = 0;
        for ($i=0; $i<10000; $i++) {
            $total +=$i;
        }
        dump($total);
    });
    
    try {
        throw new Exception('foobar Exception');
    } catch (Exception $e) {
        Debugbar::addException($e);
    }
    return 'Test Debugbar Tools';
});


Route::get('about', 'AboutController')->name('about');
Route::get('contact-us', 'ContactController@index')->name('contact');

Route::get('dashboard', 'Admin\DashboardController@index')->name('admin');

Route::get('blog', ['uses' => 'PostController@index', 'as' => 'blog']);

Route::get('blog/create', ['uses' => 'PostController@create', 'as' => 'create']);

Route::post('blog/create', ['uses' => 'PostController@store', 'as' => 'store']);

Route::get('blog/{id}', 
['uses' => 'PostController@show', 'as' => 'show']);

Route::get('categories', function () {
    // $categories = DB::table('categories')->pluck('name');
    $categories = DB::table('categories')->pluck('name', 'id');
    return $categories;
});

// Метод chunk
Route::get('chunk', function () {
    \DB::table('posts')->orderBy('id')->chunk(10, function ($posts) {
        foreach ($posts as $post) {
            dump($post);
            
        }
        // Вы можете остановить обработку последующих "кусков" вернув false из Closure:
        return false;
    });
});


// Агрегатные функции

Route::get('agr', function () {
    dump(DB::table('users')->count());
    dump(DB::table('posts')->max('updated_at'));
});


Route::get('blog-test', 'PostController@latestPost');

Route::get('username', function () {
    $users = \DB::table('profiles')
        ->whereColumn([
            ['first_name', '=', 'last_name'],
            ['updated_at', '>', 'created_at']
        ])->get();
    dump($users);
});
// having
Route::get('having', function () {
$users = DB::table('users')
        ->groupBy('id')
        ->having('id', '>', 5)
    ->get();
    dump($users);
});

// when

Route::get('when', function () {
    $category = 1;
    $posts = DB::table('posts')
    ->when($category, function ($query) use ($category) {
        return $query->where('category_id', $category);
    })
    ->get();

    dump($posts);
});


// 

Route::get('sort-by', function () {
    $sortBy = null;
    $users = DB::table('users')
        ->when($sortBy, function ($query) use ($sortBy) {
            return $query->orderBy($sortBy);
            }, function ($query) {
                return $query->orderBy('name');
            })
            ->get();
    dump($users);
});
            



// use Carbon\Carbon;

// Route::get('carbon', function () {
//     $date = Carbon::now();
//     dump($date);
//     $date->addDays(3);
//     dump($date);
// });


use Illuminate\Support\Facades\Date;

Route::get('carbon', function () {
    $date = Date::now();
    dump($date);
    $newDate = $date->copy()->addDays(7);
    dump($newDate);
});


Route::get('join', function () {
    $categories = DB::table('categories')
    ->join('posts', 'categories.id', '=', 'posts.category_id')
    ->select('categories.*', 'posts.title')
    ->get();
    dump($categories);
});

Route::get('leftjoin', function () {
    $posts = DB::table('posts')
    ->leftJoin('categories', 'categories.id', '=', 'posts.category_id')
    ->get();
    dump($posts);
});

// crossJoin

Route::get('crossjoin', function () {
    $posts = DB::table('posts')
    ->crossJoin('categories')
    ->get();
    dump($posts);
});

Route::get('union', function () {
    $first = DB::table('posts')
                ->whereNull('created_at');

    $posts = DB::table('posts')
                ->whereNull('updated_at')
                ->union($first)
                ->get();
    dump($posts);
});