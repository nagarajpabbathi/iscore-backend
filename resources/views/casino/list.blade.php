@extends('layout.master')

@section('title','Casino List')

@section('content')
<section class="content">
    @if (session('casino.success'))
    <div class="alert alert-success">{{ session('casino.success') }}</div>
    @endif
    @if (session('casino.error'))
    <div class="alert alert-danger">{{ session('casino.error') }}</div>
    @endif
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Casino List</h3>
            <div class="pull-right">
                <a href="{{ route('casino.create')}}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-plus-square"
                        aria-hidden="true"></i> &nbsp; Add Casino</a>
            </div>
        </div>
        <div class="box-body">

            <form action="{{ route('casino') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" name="name" placeholder="Banner Title Or Title"
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
                        <input class="form-control" type="number" name="rating" placeholder="Rating"
                            value="{{ Request::get('rating') }}">
                    </div>

                    <div class="form-group col-md-3">
                        <select class="form-control" name="top_rated" id="top_rated">
                            <option value="" selected>All Rated</option>
                            <option {{ ! is_null(Request::get('top_rated')) && Request::get('top_rated')=='1'
                                ? 'selected' : '' }} value="1">Yes</option>
                            <option {{ ! is_null(Request::get('top_rated')) && Request::get('top_rated')=='0'
                                ? 'selected' : '' }} value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-flat">Search</button>
                        <a href="{{route('casino')}}" class="btn btn-danger btn-flat">Clear</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Banner Title</th>
                        <th>Title</th>
                        <th>Rating</th>
                        <th>URL</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Top Rated</th>
                        <th>Created At</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($casinos as $key => $casino)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $casino->banner_title }}</td>
                        <td>{{ $casino->title }}</td>
                        <td>{{ $casino->rating }}</td>
                        <td>
                            <a href="{{ $casino->url }}"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>
                            <img width="100" height="50" src="{{ getImageUrl($casino->img) }}" alt="img">
                        </td>
                        <td>{!! $casino->status == 1 ? '<span class="label label-success">Active</span>' : '<span
                                class="label label-danger">InActive</span>' !!}</td>
                        <td>
                            {{ $casino->top_rated ? 'Yes' : 'No' }}
                        </td>
                        <td>
                            {{ dateFormat($casino->created_at) }}
                        </td>
                        <td><a href="{{ route('casino.edit',$casino->id)}}"><i class="fa fa-pencil-square-o"
                                    aria-hidden="true"></i></a></td>
                        <td>
                            <a onclick="return confirm('Are you sure, once you confirm record is permanently deleted!')"
                                href="{{ route('casino.delete',$casino->id) }}">
                                <i class="fa fa-trash" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right paginate">
                {{ $casinos->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection