@extends('layout.app')
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form id="form-profile" action="{{ route('account.profile') }}" method="post" novalidate>
                <fieldset>
                    <legend>Update Profile</legend>
                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="profile-name" class="control-label required">Name</label>
                        <input type="text" name="name" id="profile-name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                        @if($errors->has('name'))
                            <div class="help-block">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label for="profile-email" class="control-label required">Email</label>
                        <input type="email" name="email" id="profile-email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                        @if($errors->has('email'))
                            <div class="help-block">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-default" href="{{ route('account') }}">Back</a>
                    </div>
                    {{ csrf() }}
                </fieldset>
            </form>
        </div>
    </div>



@endsection
