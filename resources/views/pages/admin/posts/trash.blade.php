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
            All Trashed Posts
          </div>

          <div class="panel-body">

            @if(session()->has('success'))
              <div class="alert alert-success">
                {{ session()->get('success') }}
              </div>
            @endif

            @forelse($posts as $post)
              <div class="post">
                <h2 class="h4">{{ $post->title }}</h2>
                @if(strlen($post->body) > 200)
                  <p>{{ substr($post->body, 0, 200) . '...' }}</p>
                @else
                  <p>{{ $post->body }}</p>
                @endif
                <span style="font-size: 75%;">Published by: {{ $post->user->name }}</span><br>

                <a href="#" class="btn btn-default btn-xs" onclick="event.preventDefault();
                  document.getElementById('restore-post-form').submit();">Restore</a>

                <form id="restore-post-form" action="{{ route('post.restore', $post) }}" method="POST"
                      style="display: none;">
                  {{ csrf_field() }}
                  {{ method_field('PATCH') }}
                </form>

                <a href="#" class="btn btn-danger btn-xs" onclick="event.preventDefault();
                  document.getElementById('delete-post-forever-form').submit();">Delete forever</a>

                <form id="delete-post-forever-form" action="{{ route('post.force.delete', $post) }}" method="POST"
                      style="display: none;">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                </form>
              </div>
              <hr>
            @empty
              <div class="alert alert-info">
                There are not posts in trash.
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