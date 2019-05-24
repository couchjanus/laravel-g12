<?php

namespace App\Http\Controllers\Admin;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enums\StatusType;
use App\Category;
use App\Http\Requests\PostStoreFormRequest;
use App\Http\Requests\PostUpdateFormRequest;

use Gate;
use Auth;

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
        $user = \Auth::user();
        if ($user->can('create', Post::class)) {
            $categories = Category::all(); 
            $status = StatusType::toSelectArray(); 
            $tags = \App\Tag::all();//get()->pluck('name', 'id');
            return view('admin.posts.create')->withStatus($status)->withCategories($categories)->withTags($tags);
        } else {
            return redirect(route('posts.index'))->with('warning','You can not create post');
        }
        // if ($this->authorize('create', Post::class)) {
        //     echo 'Current logged in user is allowed to create new posts.';
        // } else {
        //     echo 'You can not create post';
        // }
        // exit;
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
        // Get the currently authenticated user's ID...
        // $id = Auth::id();
        $post = Post::firstOrCreate([
            'title' => $request->title, 
            'content'=>$request->content, 
            'status'=>$request->status, 'category_id'=>$request->category_id, 
            'user_id'=>Auth::id()]);
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
        $user = \Auth::user();
        if ($this->authorize('view', $post)) {
            return view('admin.posts.show',compact('post'));
        } else {
            return redirect(route('posts.index'))->with('warning','Not Allowed View Post');
        }
        // if ($user->can('view', $post)) {
        //   echo "Current logged in user is allowed to update the Post: {$post->title}";
        // } else {
        //   echo 'Not Authorized.';
        // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (Gate::allows('update-post', $post)) {
            $categories = Category::pluck('name', 'id'); 
            $status = StatusType::toSelectArray();
            $tags = \App\Tag::get()->pluck('name', 'id');
            return view('admin.posts.edit')->withPost($post)->withStatus($status)->withCategories($categories)->withTags($tags);
        } else {
            return redirect(route('posts.index'))->with('warning','Not Allowed Edit Post');
        }
        exit;
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
        // $user = \Auth::user();
        // if ($user->can('update', $post)) {
        //     $post->updateOrCreate([
        //         'title' => $request->title, 
        //         'content'=>$request->content, 
        //         'status'=>$request->status, 'category_id'=>$request->category_id, 
        //         'user_id'=>Auth::id()
        //         ]);
        //     $post->tags()->sync((array)$request->input('tag'));
        //     return redirect(route('posts.index'))->with('success','Post updated successfully');
        // } else {
        //     return redirect(route('posts.index'))->with('warning',"Current logged in user is allowed to update the Post: {$post->id}");
        // }
        
        // dd($request);
        
        $post->update([
                'title' => $request->title, 
                'content'=>$request->content, 
                'status'=>$request->status, 'category_id'=>$request->category_id, 
                'user_id'=>Auth::id()
                ]);
        $post->tags()->sync((array)$request->input('tag'));
        return redirect(route('posts.index'))->with('success','Post updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = Auth::user();
        
        if ($user->can('delete', $post)) {
            $post->tags()->detach();
            $post->delete();
            return redirect()->route('posts.index')->with('success','Post deleted successfully');
        } else {
            return redirect()->route('posts.index')->with('warning','Пользователь '.$user->name.' не может удалять статью...');
        }
        
        // if (Gate::forUser($user)->denies('destroy-post', $post)) {
        //     // Пользователь не может удалять статью...
        //     // dd('Пользователь '.$user->name.' не может удалять статью...');
        //     return redirect()->route('posts.index')->with('warning','Пользователь '.$user->name.' не может удалять статью...');
        // } else {
        // $post->tags()->detach();
        // $post->delete();
        // return redirect()->route('posts.index')->with('type','success')->with('message','Post deleted successfully');
        // }
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