@extends('layouts.blog')

@section('title')
  {{$post->title}}
@endsection

@section('content')
    @unless ($post)
      @alert(['type' => 'danger'])
        You are not allowed to access this resource now!
      @endalert
    @endunless

    <div class="blog-main">
      @includeIf('articles.partials._single-post', ['post' => $post])
      @includeWhen($hescomment, 'articles.partials._comments', ['some' => 'data'])
    </div>
@endsection