@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-body">
            <h1 class="h3">{{ $post->title }}</h1>
            <div class="label label-info">Published on: {{ $post->publishedDate }}</div>
            <div class="label label-info">By: {{ $post->user->name }}</div>
            <br><br>
            <p>{{ $post->body }}</p>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection