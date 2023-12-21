@extends('layout.master')

@section('title','Category Edit')

@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Category Edit</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('category.update',$category->id)}}" method="Post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name',$category->name) }}">
                        @error('name') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control">
                        @if ($category->img)
                        <a target="_blank" href="{{ getImageUrl($category->img) }}">View</a>
                        @endif
                        @error('file') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{ old('status',$category->status) == 0 ? 'selected' : '' }} value="0">
                                Inactive</option>
                            <option {{ old('status',$category->status) == 1 ? 'selected' : '' }} value="1"
                                selected>Active</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit" class="btn btn-primary btn-flat">
                </div>
            </form>
        </div>
    </div>
</section>

@endsection