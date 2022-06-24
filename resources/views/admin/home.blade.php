@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }} {{ __('You are logged in!') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


                    <div>
                        <a href="{{route('admin.posts.create')}}" class="btn btn-primary">Create Post</a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
