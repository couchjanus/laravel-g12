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

    public function index()   
    {
        $posts = Post::withCount('comments')
            ->where([
            'status' => StatusType::Published, 
            ])
            ->with('category')
            ->orderBy('updated_at', 'desc')
            ->simplePaginate(5);
        return view('blog.index')->with(compact('posts'))->withTitle('Awesome Blog');
    }
    
    public function show($slug)
    {
        if (is_numeric($slug)) {
            $post = Post::findOrFail($slug);
            return Redirect::to(route('blog.show', $post->slug), 301);
        }
        
        $post = Post::whereSlug($slug)->firstOrFail();
        $post->update(['visited'=>$post->visited+1]);
        $hescomment = $post::has('comments')?true:false;
        return view('blog.show')->withPost($post)->withHescomment($hescomment);
    }

    public function getPostsByCategory($categoryId)   {
        $posts = Post::where([
                        'status' => StatusType::Published, 
                        'category_id' => $categoryId
                    ])
                    ->with('category')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(5);
        return view('blog.index')->with(compact('posts'))->withTitle('Awesome Blog');
    }
}