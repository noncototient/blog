<div class="panel panel-default">
  <div class="panel-body">
    <ul class="nav nav-pills nav-stacked nav-pills-stacked-example">
      <li role="presentation" class="@if(Request::is('admin/posts')) active @endif"><a href="{{ route('post.index') }}">All Posts</a></li>
      <li role="presentation" class="@if(Request::is('admin/trash')) active @endif"><a href="{{ route('post.trash') }}">Trash</a></li>
    </ul>
  </div>
</div>