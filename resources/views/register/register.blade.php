@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('register') }}" method="post" novalidate>
                <fieldset>
                    <legend>Register</legend>
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="register-name" class="control-label required">Name</label>
                        <input type="text" name="name" id="register-name" class="form-control"
                               value="{{ old('name') }}" required>
                        @if($errors->has('name'))
                            <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="register-email" class="control-label required">Email</label>
                        <input type="email" name="email" id="register-email" class="form-control"
                               value="{{ old('email') }}" required>
                        @if($errors->has('email'))
                            <div class="help-block">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="register-password" class="control-label required">Password</label>
                        <input type="password" name="password" id="register-password" class="form-control" value="" required>
                        @if($errors->has('password'))
                            <div class="help-block">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirm') ? 'has-error' : '' }}">
                        <label for="register-password_confirm" class="control-label required">Confirm Password</label>
                        <input type="password" name="password_confirm" id="register-password_confirm" class="form-control"
                               value="{{ old('password_confirm') }}" required>
                        @if($errors->has('password_confirm'))
                            <div class="help-block">{{ $errors->first('password_confirm') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                    {{ csrf() }}
                </fieldset>
            </form>
        </div>
    </div>



@endsection
