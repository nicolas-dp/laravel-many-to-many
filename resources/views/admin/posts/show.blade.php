@extends('layouts.admin')


@section('content')

<div class="posts d-flex py-4">
    <img class="img-fluid" src="{{asset('storage/' . $post->cover_image)}}" alt="{{$post->title}}">

    <div class="post-data px-4">
        <h1>{{$post->title}}</h1>
        <div class="metadata">
            <strong>Category: </strong>{{$post->category ? $post->category->name : 'No Category'}}
        </div>
        <div class="tags">
            <strong>Tags:</strong>
            @if (count($post->tags) > 0)
            @foreach($post->tags as $tag)
            #{{$tag->name}}
            @endforeach
            @else
            NO TAG
            @endif
        </div>
        <div class="content">
            {{$post->content}}
        </div>
    </div>
</div>


@endsection