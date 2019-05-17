<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\StatusType;
use App\Category;
use App\Http\Requests\PostStoreFormRequest;
use App\Http\Requests\PostUpdateFormRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate();
        $status = StatusType::toSelectArray();
        $order = 'asc'; 
        return view('admin.posts.index', compact('posts', 'status', 'order'));
    }

    public function getPostsByStatus(Request $request)
    {
        static $statusPost;
        $status = StatusType::toSelectArray();
        $statusPost = $request->status; 
        $posts = Post::status($statusPost)->paginate();
        return view('admin.posts.status', compact('posts', 'status', 'statusPost'));
    }

    public function sortPostsByDate(Request $request)
    {
        $status = StatusType::toSelectArray();
        $order = isset($request->order)?$request->order:'desc'; 
        $posts = Post::orderBy('updated_at', $order)->paginate();
        return view('admin.posts.index', compact('posts', 'status', 'order'));
    }


    public function getById($id)       {
        return  \App\Post::find($id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(); 
        $status = StatusType::toSelectArray(); 
        $tags = \App\Tag::all();//get()->pluck('name', 'id');
        return view('admin.posts.create')->withStatus($status)->withCategories($categories)->withTags($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostStoreFormRequest $request)
    {
        // Получить post или создать, если не существует...
        $post = Post::firstOrCreate([
            'title' => $request->title, 
            'content'=>$request->content, 
            'status'=>$request->status, 'category_id'=>$request->category_id, 
            'user_id'=>1]);
        $post->tags()->sync((array)$request->input('tag'));    
        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        // dump($post);
        return view('admin.posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id'); 
        $status = StatusType::toSelectArray();
        $tags = \App\Tag::get()->pluck('name', 'id');
        return view('admin.posts.edit')->withPost($post)->withStatus($status)->withCategories($categories)->withTags($tags);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateFormRequest $request, Post $post)
    {
        // Если подходящей модели нет, создать новую.
        $post->updateOrCreate([
            'title' => $request->title, 
            'content'=>$request->content, 
            'status'=>$request->status, 'category_id'=>$request->category_id, 
            'user_id'=>1
            ]);
        $post->tags()->sync((array)$request->input('tag'));
        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->tags()->detach();
        $post->delete();
        return redirect()->route('posts.index')->with('type','success')->with('message','Post deleted successfully');
    }

    public function getByIds($ids)
    {
        // Можно вызвать метод find с массивом первичных ключей,
        // который вернет коллекцию подходящих записей:
        return Post::find($ids);
        // Можно вызвать метод findMany с массивом первичных ключей,
        // который вернет коллекцию подходящих записей:
        // return Post::findMany($ids);
        // Можно вызвать метод whereIn с массивом первичных ключей,
        // который вернет коллекцию подходящих записей:
        // return Post::whereIn('id', $ids)->get();
    }

    public function getFirstPublished()
    {
        // Получение первой модели, удовлетворяющей условиям...
        dump(Post::where('status', 2)->first());
        // return Post::where('status', 2)->first();
    }

    public function getFirstOrFail($id)
    {
        dump(Post::findOrFail($id));
        dump(Post::where('status', '>', 2)->firstOrFail());
    }

}
