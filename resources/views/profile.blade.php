@extends('layout.master')

@section('title','Profile')

@section('content')

<section class="content-header">
    <h1>
        Profile
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Profile</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @if (session('profile.success'))
            <div class="alert alert-success">
                {{ session('profile.success') }}
            </div>
            @endif
            <div class="nav-tabs-custom">
                <div class="tab-content">
                    <div id="settings">
                        <form class="form-horizontal" action="{{ route('profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                        value="{{ $user->name }}">
                                    <span style="color: red">
                                        @error('name')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                        value="{{ $user->email }}">
                                    <span style="color: red">
                                        @error('email')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password">
                                    <span style="color: red">
                                        @error('password')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password" class="col-sm-2 control-label">Confirm
                                    Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="confirm_password"
                                        id="confirm_password" placeholder="Confirm Password">
                                    <span style="color: red">
                                        @error('confirm_password')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profile_image" class="col-sm-2 control-label">Profile Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="profile_image" id="profile_image">
                                    <span style="color: red">
                                        @error('profile_image')
                                        {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profile_image" class="col-sm-2 control-label">Profile Image</label>
                                <div class="col-sm-10">
                                    <img width="250px" height="250px" src="{{ $user->profile_image }}"
                                        alt="profile image">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection