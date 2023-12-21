@extends('layout.master')

@section('title','Casino Create')

@section('content')

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Casino Create</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('casino.store')}}" method="Post" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                        @error('title') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Banner Title</label>
                        <input type="text" name="banner_title" class="form-control" value="{{ old('banner_title') }}">
                        @error('banner_title') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Rating</label>
                        <input type="number" step="0.1" max="10" name="rating" class="form-control"
                            value="{{ old('rating') }}">
                        @error('rating') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control">
                        @error('img') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="{{ old('url') }}">
                        @error('url') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Toprated</label>
                        <div class="checkbox">
                            <label><input name="top_rated" type="checkbox" value="1">Is Toprated</label>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{ ! is_null(old('status')) && old('status')==0 ? 'selected' : '' }} value="0">
                                Inactive</option>
                            <option {{ ! is_null(old('status')) && old('status')==1 ? 'selected' : '' }} value="1"
                                selected>Active</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        @error('description') <font color="red"> <small> {{$message}} </small></font> @enderror
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