<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PostController extends Controller
{
    public function index()
    {
        $posts = DB::select('select * from posts');
        return view('blog.index', ['posts' => $posts, 'title'=>'Awesome Blog']);
    }

    public function show($id)
    {
        $post = DB::select("select * from posts where id = :id", ['id' => $id]);
        return view('blog.show', ['post' => $post]);
    }

}
