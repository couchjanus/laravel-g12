<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\StatusType;
use App\Category;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = Post::all();
        // dump($posts);
        $posts = Post::paginate();
        $status = StatusType::toSelectArray();
        $order = 'asc'; 
        return view('admin.posts.index', compact('posts', 'status', 'order'));
        
    }

    public function getPostsByStatus(Request $request)
    {
        // dump($request);
        static $statusPost;
        $status = StatusType::toSelectArray();
        // $posts = Post::whereStatus($request->status)->paginate();
        $statusPost = $request->status; 
        $posts = Post::status($statusPost)->paginate(5);
        // dump($posts);
        return view('admin.posts.status', compact('posts', 'status', 'statusPost'));
    }

    public function sortPostsByDate(Request $request)
    {
        $status = StatusType::toSelectArray();
        $order = isset($request->order)?$request->order:'desc'; 
        $posts = Post::orderBy('updated_at', $order)->paginate();
        // dump($posts);
    
        return view('admin.posts.index', compact('posts', 'status', 'order'));
    }


    public function getById($id)       {
        // Получение модели по её первичному ключу...
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
        return view('admin.posts.create')->withStatus($status)->withCategories($categories);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Получить post или создать, если не существует...
        $post = Post::firstOrCreate(['title' => $request->title, 'content'=>$request->content, 'status'=>$request->status, 'category_id'=>$request->category_id, 'user_id'=>1]);
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
        return view('admin.posts.edit')->withPost($post)->withStatus($status)->withCategories($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // $post->updated_at = '2019-01-01 10:00:00';
        // $post->save(['timestamps' => false]);

        // Если подходящей модели нет, создать новую.
        $post->updateOrCreate(['title' => $request->title, 'content'=>$request->content, 'status'=>$request->status, 'category_id'=>$request->category_id, 'user_id'=>1]);
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
        //
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

    public function testIds()
    {
        // $ids = [1, 2, 3];
        // $result = $this->getByIds($ids);

        // $result = Post::where('id', '>', 40)->take(10)->get();

        // $result = Post::where([
        //     ['id','>', 40],
        //     ['status', '=', 1],
        // ])->get();
        
        // $result = Post::where('category_id', 1)->get();
        // это:
        // $result = Post::whereCategoryId(3)->get();

        // $result = Post::whereDate('created_at', date('Y-m-d'));
        // $result = Post::whereDay('created_at', date('d'));
        // $result = Post::whereMonth('created_at', date('m'));
        // $result = Post::whereYear('created_at', date('2019'))->get();

        // $result = Post::where('id', 1);
        // $result = Post::orWhere('id', 2);
        // $result = Post::orWhere('id', 3);

        // Вы можете сделать это так:
        
        // $result = Post::where('id', 1)->orWhere(['id' => 2, 'id' => 3])->get();


        dump($result);
    }


}
