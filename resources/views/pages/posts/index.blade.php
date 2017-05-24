@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">
            All Posts
          </div>

          <div class="panel-body">

            @forelse($posts as $post)
              <div class="post">
                <h2 class="h4"><a href="{{ route('post.show', $post) }}">{{ $post->title }}</a></h2>
                @if(strlen($post->body) > 200)
                  <p>{{ substr($post->body, 0, 200) . '...' }}</p>
                @else
                  <p>{{ $post->body }}</p>
                @endif
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