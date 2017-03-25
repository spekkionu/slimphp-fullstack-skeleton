You are receiving this email because we received a password reset request for your account.

Visit the url below to complete your password reset.
{{ route('login.password.confirm', ['token' => $token], ['email' => $user->email], true) }}

If you did not request a password reset, no further action is required.
