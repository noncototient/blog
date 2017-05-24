@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        @include('pages.admin.posts.partials.sidebar')
      </div>

      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">
            All Posts <a href="{{ route('post.create') }}" class="btn btn-xs btn-primary pull-right">Create Post</a>
          </div>

          <div class="panel-body">
            @if(session()->has('success'))
              <div class="alert alert-success">
                {{ session()->get('success') }}
              </div>
            @endif

            @forelse($posts as $post)
              <div class="post">
                <h2 class="h4"><a href="{{ route('post.edit', $post) }}">{{ $post->title }}</a></h2>
                @if(strlen($post->body) > 200)
                  <p>{{ substr($post->body, 0, 200) . '...' }}</p>
                @else
                  <p>{{ $post->body }}</p>
                @endif
                @if($post->isActive)
                  <span class="label label-info">Active</span>
                  <span class="label label-info">Published: {{ $post->publishedDate }}</span>
                @else
                  <span class="label label-default">Inactive</span>
                @endif
                <span class="pull-right" style="font-size: 75%;">Published by: {{ $post->user->name }}</span>
              </div>
              <hr>
            @empty
              <div class="alert alert-warning">
                There are not posts to display.
              </div>
            @endforelse
          </div>
          <div class="panel-footer">
            {{ $posts->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection