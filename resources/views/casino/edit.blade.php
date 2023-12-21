@extends('layout.master')

@section('title','Casino Edit')

@section('content')

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Casino Edit</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('casino.update',$casino->id)}}" method="Post" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title',$casino->title) }}">
                        @error('title') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Banner Title</label>
                        <input type="text" name="banner_title" class="form-control"
                            value="{{ old('banner_title',$casino->banner_title) }}">
                        @error('banner_title') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Rating</label>
                        <input type="number" step="0.1" max="10" name="rating" class="form-control"
                            value="{{ old('rating',$casino->rating) }}">
                        @error('rating') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control">
                        @if ($casino->img)
                        <a target="_blank" href="{{ getImageUrl($casino->img) }}">View</a>
                        @endif
                        @error('img') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control" value="{{ old('url',$casino->url) }}">
                        @error('url') <font color="red"> <small> {{$message}} </small></font> @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Toprated</label>
                        <div class="checkbox">
                            <label><input name="top_rated" type="checkbox" value="1" {{ $casino->top_rated ? 'checked' :
                                '' }}>Is Toprated</label>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option {{ old('status',$casino->status)==0 ? 'selected' : '' }} value="0">
                                Inactive</option>
                            <option {{ old('status',$casino->status)==1 ? 'selected' : '' }} value="1"
                                selected>Active</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Description</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description',$casino->description) }}</textarea>
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