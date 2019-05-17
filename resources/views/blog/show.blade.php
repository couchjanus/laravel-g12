@extends('layouts.blog')

@section('title')
  {{$post->title}}
@endsection

@section('content')
    <div class="blog-main">
      @includeIf('blog.partials._single-post', ['post' => $post])
      @includeWhen($hescomment, 'blog.partials._comments', ['some' => 'data'])
    </div>
@endsection
