@extends('layout.master')

@section('title','Category')

@section('content')
<section class="content">
    @if (session('category.success'))
    <div class="alert alert-success">{{ session('category.success') }}</div>
    @endif
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Category List</h3>
            <div class="pull-right">
                <a href="{{ route('category.create')}}" class="btn btn-info btn-xs btn-flat"><i
                        class="fa fa-plus-square" aria-hidden="true"></i> &nbsp; Add Category</a>
            </div>
        </div>
        <div class="box-body">

            <form action="{{ route('category') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" name="name" placeholder="Name"
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
                        <button type="submit" class="btn btn-success btn-flat">Search</button>
                        <a href="{{route('category')}}" class="btn btn-danger btn-flat">Clear</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Likes</th>
                        <th>Views</th>
                        <th>Image</th>
                        <th>Created At</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{!! $category->status == 1 ? '<span class="label label-success">Active</span>' : '<span
                                class="label label-danger">InActive</span>' !!}</td>
                        <td>{{ $category->likes }}</td>
                        <td>{{ $category->views }}</td>
                        <td>
                            <img width="100" height="50" src="{{ getImageUrl($category->img) }}" alt="img">
                        </td>
                        <td>
                            {{ dateFormat($category->created_at) }}
                        </td>
                        <td><a href="{{ route('category.edit',$category->id)}}"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a></td>
                        <td>
                            <a onclick="return confirm('Are you sure, once you confirm record is permanently deleted!')"
                                href="{{ route('category.delete',$category->id) }}">
                                <i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right paginate">
                {{ $categories->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection