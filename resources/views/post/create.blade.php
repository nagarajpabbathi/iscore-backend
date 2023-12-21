@extends('layout.master')

@section('title','Post Create')

@section('content')

<section class="content">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Post Create</h3>
		</div>
		<div class="box-body">
			<form action="{{ route('post.store')}}" method="Post" autocomplete="off" enctype="multipart/form-data">
				@csrf
				<div class="row">
					<div class="form-group col-md-6">
						<label>Category</label>
						<select class="form-control" name="category_id" id="category_id">
							<option value="" selected disabled>Select Category</option>
							@foreach ($category as $key => $cat)
								<option {{ old('category_id') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $cat }}</option>
							@endforeach
						</select>
						@error('category_id') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>
					<div class="form-group col-md-6">
						<label>Title</label>
						<input type="text" name="title" class="form-control" value="{{ old('title') }}">
						@error('title') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>
					<div class="form-group col-md-6">
						<label>Location</label>
						<input type="text" name="location" class="form-control" value="{{ old('location') }}">
						@error('location') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>

					<div class="form-group col-md-6">
						<label>Type</label>
						<select class="form-control" name="type" id="type">
							<option {{ ! is_null(old('type')) && old('type')==0 ? 'selected' : '' }} value="image">Image
							</option>
							<option {{ ! is_null(old('type')) && old('type')==1 ? 'selected' : '' }} value="video"
								selected>Video</option>
						</select>
						@error('type') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>


					<div class="form-group col-md-6">
						<label>Image or Video <font color="red">(Upload Portrait Rresolution)</font></label>
						<input type="file" name="file" class="form-control">
						@error('file') <font color="red"> <small> {{$message}} </small></font> @enderror
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
						<textarea name="description" class="form-control" rows="3"></textarea>
						@error('description') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>

					<div class="image form-group col-md-6" style="display: none">
						<label>News Banner <font color="red">(Landscape Resolution)</font></label>
						<input type="file" class="form-control" name="image">
						@error('image') <font color="red"> <small> {{$message}} </small></font> @enderror
					</div>

					<div class="editor form-group col-md-12" style="display: none">
						<label>Html</label>
						<textarea name="html" class="form-control" id="editor1" name="editor1" rows="5" cols="80"></textarea>
						@error('html') <font color="red"> <small> {{$message}} </small></font> @enderror
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

@section('script')
<script src="{{ asset('bower_components/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script>
	$(function () {
    CKEDITOR.replace('editor1')
    $('.textarea').wysihtml5()
})
$('#type').change(function(e){
	if ($(this).val() == 'image') {
		$('.editor').css('display','block')
		$('.image').css('display','block')
	}else{
		$('.image').css('display','none')
		$('.editor').css('display','none')
	}
})
</script>
@endsection