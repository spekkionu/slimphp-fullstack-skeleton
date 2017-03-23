@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form action="{{ route('login.password') }}" method="post" novalidate>
                <fieldset>
                    <legend>Forgot Password</legend>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="login-email" class="control-label required">Email</label>
                        <input type="email" name="email" id="login-email" class="form-control"
                               value="{{ old('email') }}" required>
                        @if($errors->has('email'))
                            <div class="help-block">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send Password Reset</button>
                        <a class="btn btn-default" href="{{ route('login') }}">Back</a>
                    </div>
                    {{ csrf() }}
                </fieldset>
            </form>
        </div>
    </div>



@endsection
