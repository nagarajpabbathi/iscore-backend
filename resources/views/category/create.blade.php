@extends('layout.master')

@section('title','Category Create')

@section('content')
<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Category Create</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('category.store')}}" method="Post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="form-group col-md-4">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        @error('name') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control">
                        @error('file') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-4">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{ ! is_null(old('status')) && old('status')==0 ? 'selected' : '' }} value="0">
                                Inactive</option>
                            <option {{ ! is_null(old('status')) && old('status')==1 ? 'selected' : '' }} value="1"
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