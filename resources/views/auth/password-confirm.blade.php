@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('login.password.confirm', ['token' => $token]) }}" method="post" novalidate>
                <fieldset>
                    <legend>Reset Password</legend>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="login-email" class="control-label required">Email</label>
                        <input type="email" name="email" id="login-email" class="form-control"
                               value="{{ old('email', $email) }}" required>
                        @if($errors->has('email'))
                            <div class="help-block">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="login-password" class="control-label required">Password</label>
                        <input type="password" name="password" id="login-password" class="form-control" value="" required>
                        @if($errors->has('password'))
                            <div class="help-block">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirm') ? 'has-error' : '' }}">
                        <label for="login-password_confirm" class="control-label required">Confirm Password</label>
                        <input type="password" name="password_confirm" id="login-password_confirm" class="form-control"
                               value="{{ old('password_confirm') }}" required>
                        @if($errors->has('password_confirm'))
                            <div class="help-block">{{ $errors->first('password_confirm') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Password</button>
                    </div>
                    {{ csrf() }}
                </fieldset>
            </form>
        </div>
    </div>



@endsection
