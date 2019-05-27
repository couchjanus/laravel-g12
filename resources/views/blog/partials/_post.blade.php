<div class="post-preview">
    <a href="/blog/{{$post->slug}}">
    <h2 class="post-title">{{$post->title}} </h2>  
    </a>
    <p class="post-trunc">{{str_limit($post->content,50)}}</p>
    {{-- <p class="post-meta">Posted by <a href="#">{{ $post->user->name }} </a> Published at: {{ $post->created_at }}</p> --}}

    <a href="{{ route('blog.category', $post->category_id) }}"><span data-feather="list"></span> {{ $post->category->name }}</a>

</div>
