@extends('layouts.app')

@section('title')
Data Post 
@endsection

@section('content')
<div class="container">
  <script>
    function button_cancel() {
      location.replace("{{ asset('/') }}post");
    }
  </script>
  <div class="card">
    <h5 class="card-header text-bg-success"> Data Post</h5>
    <div class="card-body">
      @if($action == 'insert')
      <form class="form-horizontal" action="{{ asset('/') }}post" method="post" enctype="multipart/form-data">
        <div class="mb-3 row">
          <label for="cat_id" class="col-sm-2 col-form-label">Category</label>
          <div class="col-sm-10">
            {!! selectForm($categories,'id','category','cat_id','') !!}
          </div>
        </div>
        <div class="mb-3 row">
          <label for="title" class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="title" value="">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="content" class="col-sm-2 col-form-label">Content</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="content" id="content" rows="7"></textarea>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="image" class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-10">
            <input class="form-control" type="file" name="image" value="">
          </div>
        </div>
        <div class="mb-3">
          <div class="offset-sm-2 col-sm-10">
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <button type="submit" class="btn btn-primary">Insert</button>
            <button type="button" class="btn btn-secondary" onclick="button_cancel()">Cancel</button>
          </div>
        </div>
        {{ csrf_field() }}
      </form>
      @elseif($action == 'update')
      <form class="form-horizontal" action="{{ asset('/') }}post/{{ $row->id }}" method="post" enctype="multipart/form-data">
        <div class="mb-3 row">
          <label for="cat_id" class="col-sm-2 col-form-label">Category</label>
          <div class="col-sm-10">
            {!! selectForm($categories,'id','category','cat_id', $row->cat_id) !!}

          </div>
        </div>
        <div class="mb-3 row">
          <label for="title" class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="title" value="{{ $row->title }}">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="content" class="col-sm-2 col-form-label">Content</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="content" id="content" rows="7">{{ $row->content }}</textarea>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="image" class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-10">
            <input class="form-control" type="file" name="image" value="">
          </div>
        </div>
        <div class="mb-3 row">
          <div class="offset-sm-2 col-sm-10">
            @method("PATCH")
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="hidden" name="id" value="{{ $row->id }}">
            <button type="submit" class="btn btn-warning">Update</button>
            <button type="button" class="btn btn-secondary" onclick="button_cancel()">Cancel</button>
          </div>
        </div>
        {{ csrf_field() }}
      </form>
      @elseif($action == 'delete')
      <form class="form-horizontal" action="{{ asset('/') }}post/{{ $row->id }}" method="post">
        <div class="mb-3 row">
          <label for="user_id" class="col-sm-2 control-label">Username</label>
          <div class="col-sm-10">
            {{ $row->username }}
          </div>
        </div>
        <div class="mb-3 row">
          <label for="cat_id" class="col-sm-2 control-label">Category</label>
          <div class="col-sm-10">
            {{ $row->category }}
          </div>
        </div>
        <div class="mb-3 row">
          <label for="title" class="col-sm-2 control-label">Title</label>
          <div class="col-sm-10">
            {{ $row->title }}
          </div>
        </div>
        <div class="mb-3 row">
          <label for="content" class="col-sm-2 control-label">Content</label>
          <div class="col-sm-10">
            {{ $row->content }}
          </div>
        </div>
        <div class="mb-3 row">
          <label for="image" class="col-sm-2 control-label">Image</label>
          <div class="col-sm-10">
            <img src="{{ $row->image }}" width="150" class="img-fluid">
          </div>
        </div>
        <div class="mb-3 row">
          <label for="updated_at" class="col-sm-2 control-label">Updated At</label>
          <div class="col-sm-10">
            {{ $row->updated_at }}
          </div>
        </div>
        <div class="mb-3 row">
          <div class="offset-sm-2 col-sm-10">
            @method("DELETE")
            <input type="hidden" name="action" value="{{ $action }}">
            <input type="hidden" name="id" value="{{ $row->id }}">
            <button type="submit" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-secondary" onclick="button_cancel()">Cancel</button>
          </div>
        </div>
        {{ csrf_field() }}
      </form>
      @elseif($action == 'detail')
      <div class="mb-3 row">
        <label for="user_id" class="col-sm-2 control-label">Username</label>
        <div class="col-sm-10">
          {{ $row->username }}
        </div>
      </div>
      <div class="mb-3 row">
        <label for="cat_id" class="col-sm-2 control-label">Category</label>
        <div class="col-sm-10">
          {{ $row->category }}
        </div>
      </div>
      <div class="mb-3 row">
        <label for="title" class="col-sm-2 control-label">Title</label>
        <div class="col-sm-10">
          {{ $row->title }}
        </div>
      </div>
      <div class="mb-3 row">
        <label for="content" class="col-sm-2 control-label">Content</label>
        <div class="col-sm-10">
          {{ $row->content }}
        </div>
      </div>
      <div class="mb-3 row">
        <label for="image" class="col-sm-2 control-label">Image</label>
        <div class="col-sm-10">
          <img src="{{ $row->image }}" width="150" class="img-fluid">
        </div>
      </div>
      <div class="mb-3 row">
        <label for="updated_at" class="col-sm-2 control-label">Updated At</label>
        <div class="col-sm-10">
          {{ $row->updated_at }}
        </div>
      </div>
      <div class="mb-3 row">
        <div class="offset-sm-2 col-sm-10">
          <button type="button" class="btn btn-secondary" onclick="button_cancel()">Back</button>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection