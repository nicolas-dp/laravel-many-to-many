@extends('layouts.admin')

@section('content')

<h2 class="py-4">Create a new Post</h2>
@include('partials.errors')
<form action="{{route('admin.posts.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Learn php article" aria-describedby="titleHelper" value="{{old('title')}}">
        <small id="titleHelper" class="text-muted">Type the post title, max: 150 carachters</small>
    </div>

    <div class="mb-4">
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control" placeholder="Learn php article" aria-describedby="cover_imageHelper">
            <small id="cover_imageHelper" class="text-muted">Path image</small>
        </div>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Categories</label>
        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id">
            <option value="">Select a category</option>
            @foreach($categories as $category)

            <option value="{{$category->id}}" {{$category->id == old('category_id' ? 'selected' : '')}}>{{$category->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="tags">Tags</label>
        <select multiple class="form-control" name="tags[]" id="tags">
            @if($tags)
            @foreach($tags as $tag)
            <option value="{{$tag->id}}">{{$tag->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
    @error('tags')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="mb-4">
        <label for="content">Content</label>
        <textarea class="form-control  @error('content') is-invalid @enderror" name="content" id="content" rows="4">
        {{old('content')}}
        </textarea>
    </div>

    <button type="submit" class="btn btn-primary">Add Post</button>

</form>

@endsection