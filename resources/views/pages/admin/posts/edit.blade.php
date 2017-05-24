@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">
            Update this post
            @if($post->isActive)
              <a class="pull-right" target="_blank" href="{{ route('post.show', $post) }}">View Post</a>
            @else
              <div class="text-muted pull-right">Mark this post active to view it</div>
            @endif
          </div>
          <div class="panel-body">

            @if($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <form action="{{ route('post.update', $post) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}

              <div class="form-group @if($errors->has('title'))has-error @endif">
                <label class="control-label" for="title">Post Title</label>
                <input id="title" type="text" name="title" class="form-control" value="{{ $post->title }}">
              </div>

              <div class="form-group @if($errors->has('body'))has-error @endif">
                <label class="control-label" for="body">Post Body</label>
                <textarea name="body" id="body" rows="5" class="form-control">{{ $post->body }}</textarea>
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input name="active" type="checkbox" @if($post->isActive) checked @endif> Publish this post (mark
                    active)
                  </label>
                </div>
              </div>
              <input type="submit" value="SAVE" class="btn btn-primary">
            </form>
          </div>

          <div class="panel-footer">
            <a href="{{ route('post.index') }}">Back to all posts</a>
            <a href="#" class="btn btn-xs btn-danger pull-right" onclick="event.preventDefault();
              document.getElementById('delete-post-form').submit();">
              Trash this post</a>

            <form id="delete-post-form" action="{{ route('post.delete', $post) }}" method="POST" style="display: none;">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection