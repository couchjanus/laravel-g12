<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Post;
use Illuminate\Support\Facades\Date;
use App\Enums\StatusType;


class PostController extends Controller
{
    /**
     * Показать список всех posts.
     *
     * @return Response
     */

    public function index()    {
        $posts = DB::table('posts')
            ->where('status', StatusType::Published)
            ->orderBy('updated_at', 'desc')
            ->simplePaginate(5);
        return view('blog.index', ['posts' => $posts, 'title'=>'Awesome Blog']);
    }

    public function show($slug)
    {
        if (is_numeric($slug)) {
            // Get post for slug.
            $post = Post::findOrFail($slug);
            return Redirect::to(route('blog.show', $post->slug), 301);
            // 301 редирект со старой страницы, на новую.   
        }
        $post = DB::table('posts')->where('slug', $slug)->first();
        // $post = Post::whereSlug($slug)->firstOrFail();
        return view('blog.show', ['post' => $post, 'hescomment'=>true]);
    }

    public function getLatestPost()
    {
        $posts = DB::table('posts')->orderBy('id', 'desc')->get();
        return view('blog.index', ['posts' => $posts]);
    }

    public function oldestPost()
    {
        $post = DB::table('posts')
            ->oldest('created_at')
            ->first();
        return view('blog.show', ['post' => $post]);
    }

    public function getPostsByWere()
    {
        $posts = DB::table('posts')
            ->where('id', '>=', 100)
            ->get();

        // $posts = DB::table('posts')
        //     ->where('id', '<>', 100)
        //     ->get();

        // $posts = DB::table('posts')
        //     ->where('title', 'like', 'T%')
        //     ->get();

        // $posts = DB::table('posts')->where([
        //     ['status', '=', '1'],
        //     ['id', '>', '100'],
        // ])->get();

        // $posts = DB::table('posts')
        //     ->where('id', '>', 100)
        //     ->orWhere('status', true)
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereBetween('id', [1, 100])->get();

        // $posts = DB::table('posts')
        //     ->whereNotBetween('id', [1, 100])
        //     ->get();
        
        // $posts = DB::table('posts')
        //     ->whereIn('category_id', [1, 2, 3])
        //     ->get();
        
        // $posts = DB::table('posts')
        //     ->whereNotIn('category_id', [1, 2, 3])
        //     ->get();
        
        // $posts = DB::table('posts')
        //     ->whereNull('updated_at')
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereNotNull('updated_at')
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereDate('created_at', '2018-05-17')
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereMonth('created_at', '05')
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereDay('created_at', '18')
        //     ->get();
            
        // $posts = DB::table('posts')
        //     ->whereYear('created_at', '2018')
        //     ->get();

        // $posts = DB::table('posts')
        //     ->whereColumn('updated_at', '>', 'created_at')
        //     ->get();
        
        return view('blog.index', ['posts' => $posts]);
    }

    public function takeLatestPosts() {
        $posts = DB::table('posts')->orderBy('id', 'desc')->take(5)->get();

        return view('blog.index', ['posts' => $posts]);
    }

    public function skipAndGetLatestPosts() {
        $posts = DB::table('posts')->orderBy('id', 'desc')->skip(10)->take(5)->get();
        return view('blog.index', ['posts' => $posts]);
    }

    public function getLomitLatestPosts() {
        $posts = DB::table('posts')
                ->offset(10)
                ->limit(5)
                ->get();
        return view('blog.index', ['posts' => $posts]);
    }

}