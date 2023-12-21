@extends('layout.master')

@section('title','User List')

@section('content')
<section class="content">
    @if (session('user.success'))
    <div class="alert alert-success">{{ session('user.success') }}</div>
    @endif
    @if (session('user.error'))
    <div class="alert alert-danger">{{ session('user.error') }}</div>
    @endif
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">User List</h3>
        </div>
        <div class="box-body">

            <form action="{{ route('user') }}">
                <div class="row">
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" name="name" placeholder="Name"
                            value="{{ Request::get('name') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" name="email" placeholder="Email Or Username"
                            value="{{ Request::get('email') }}">
                    </div>
                    <div class="form-group col-md-3">
                        <input class="form-control" type="text" name="mobile" placeholder="Mobile"
                            value="{{ Request::get('mobile') }}">
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

                    <div class="form-group col-md-12 text-center">
                        <button type="submit" class="btn btn-success btn-flat">Search</button>
                        <a href="{{route('user')}}" class="btn btn-danger btn-flat">Clear</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Register Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{!! $user->status == 'Active' ? '<span class="label label-success">Active</span>' : '<span
                                class="label label-danger">InActive</span>' !!}</td>
                        <td>
                            {{ dateFormat($user->created_at) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right paginate">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</section>
@endsection