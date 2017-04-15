@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form id="form-password" action="{{ route('account.password') }}" method="post" novalidate>
                <fieldset>
                    <legend>Change Password</legend>
                    <div class="form-group {{ $errors->has('current') ? 'has-error' : '' }}">
                        <label for="current-current" class="control-label required">Current Password</label>
                        <input type="password" name="current" id="password-current" class="form-control" value="" required>
                        @if($errors->has('current'))
                            <div class="help-block">{{ $errors->first('current') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                        <label for="password-password" class="control-label required">New Password</label>
                        <input type="password" name="password" id="password-password" class="form-control" value="" required>
                        @if($errors->has('password'))
                            <div class="help-block">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirm') ? 'has-error' : '' }}">
                        <label for="password-password_confirm" class="control-label required">Confirm Password</label>
                        <input type="password" name="password_confirm" id="password-password_confirm" class="form-control"
                               value="{{ old('password_confirm') }}" required>
                        @if($errors->has('password_confirm'))
                            <div class="help-block">{{ $errors->first('password_confirm') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                        <a class="btn btn-default" href="{{ route('account') }}">Back</a>
                    </div>
                    {{ csrf() }}
                </fieldset>
            </form>
        </div>
    </div>



@endsection
