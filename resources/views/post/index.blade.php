@extends('layout.master')

@section('title','Post')

@section('content')
<section class="content">

  <div class="box box-primary">
    <div class="box-header with-border">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      <h3 class="box-title">Post List</h3>
      <div class="pull-right">
        <a href="{{ route('post.create')}}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-square"
            aria-hidden="true"></i> &nbsp; Add Post</a>
      </div>
    </div>
    <div class="box-body">

      <form action="{{ route('post') }}">
        <div class="row">
            <div class="form-group col-md-3">
                <input class="form-control" type="text" name="name" placeholder="Title"
                    value="{{ Request::get('name') }}">
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="status" id="status">
                    <option value="" selected>All Status</option>
                    <option {{ ! is_null(Request::get('status')) && Request::get('status')==1 ? 'selected' : ''
                        }} value="1">Active</option>
                    <option {{ ! is_null(Request::get('status')) && Request::get('status')==0 ? 'selected' : ''
                        }} value="0">InActive</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="category" id="category">
                    <option value="" selected>All Category</option>
                    @foreach ($cats as $key => $cat)
                      <option {{ Request::get('category') == $key ? 'selected' : '' }} value="{{$key}}">{{$cat}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <select class="form-control" name="type" id="type">
                    <option value="" selected>All Type</option>
                    <option {{ ! is_null(Request::get('type')) && Request::get('type')=='image' ? 'selected' : '' }} value="image">Image</option>
                    <option {{ ! is_null(Request::get('type')) && Request::get('type')=='video' ? 'selected' : '' }} value="video">Video</option>
                </select>
            </div>
            <div class="form-group col-md-12 text-center">
                <button type="submit" class="btn btn-success btn-flat">Search</button>
                <a href="{{route('post')}}" class="btn btn-danger btn-flat">Clear</a>
            </div>
        </div>
    </form>

      <table class="table table-bordered table-striped text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Location</th>
            <th>Type</th>
            <th>Image Or Video</th>
            <th>Likes</th>
            <th>Views</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          @foreach($posts as $key => $post)
          <tr>
            <td>{{ ++$key }}</td>
            <td>{{ $post->title }}</td>
            <td>{{ $post->category->name }}</td>
            <td>{{ $post->location }}</td>
            <td>{{ Str::ucfirst($post->type) }}</td>
            <td>
              <a target="_blank" href="{{ getFile($post->filename) }}">
                <i class="fa fa-eye"></i>
              </a>
            </td>
            <td>
              {{ $post->likes }}
            </td>
            <td>
              {{ $post->views }}
            </td>
            <td>{!! $post->status == 1 ? '<span class="label label-success">Active</span>' : '<span
                class="label label-danger">InActive</span>' !!}</td>
            <td>
              {{ dateFormat($post->created_at) }}
            </td>
            <td>
              <a href="{{ route('post.edit',$post->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
            </td>
            <td>
              <a onclick="return confirm('Are you sure, once you confirm record is permanently deleted!')"
                href="{{ route('post.delete',$post->id) }}"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div class="text-right paginate">
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
    </div>
  </div>
</section>
@endsection