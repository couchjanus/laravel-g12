<div class="post-preview">
    <a href="/blog/{{$post->slug}}">
    <h2 class="post-title">{{$post->title}} </h2>  
    </a>
    <p class="post-trunc">{{str_limit($post->content,50)}}</p>
    <p class="post-meta">Posted by <a href="#">Janus </a> {{ $post->created_at }}</p>
</div>
  