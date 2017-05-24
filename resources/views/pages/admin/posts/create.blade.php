@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">
            Create a new post
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

            <form action="{{ route('post.store') }}" method="post">
              {{ csrf_field() }}

              <div class="form-group @if($errors->has('title'))has-error @endif">
                <label class="control-label" for="title">Post Title</label>
                <input id="title" type="text" name="title" class="form-control" value="{{ old('title') }}">
              </div>
              
              <div class="form-group @if($errors->has('body'))has-error @endif">
                <label class="control-label" for="body">Post Body</label>
                <textarea name="body" id="body" rows="5" class="form-control">{{ old('body') }}</textarea>
              </div>

              <div class="form-group">
                <div class="checkbox">
                  <label>
                    <input name="active" type="checkbox"> Publish this post (mark active)
                  </label>
                </div>
              </div>
              <input type="submit" value="SAVE" class="btn btn-primary">
            </form>
          </div>

          <div class="panel-footer">

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection