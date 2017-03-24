@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <div class="panel panel-default">
                <div class="panel-heading">
                    My Profile
                </div>
                <table class="table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                </table>
                <div class="panel-footer">
                    <a class="btn btn-info" href="{{ route('account.profile') }}">
                        <i class="fa fa-pencil"></i>
                        Edit
                    </a>
                    <a class="btn btn-warning" href="{{ route('account.password') }}">
                        <i class="fa fa-lock"></i>
                        Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>



@endsection
