@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<h1 class="h3">{{ __('soko.auth.login_title') }}</h1>
<form method="POST" action="{{ url('/login') }}" class="card card-body">@csrf
  <label class="form-label" for="email">{{ __('soko.auth.email') }}</label><input class="form-control mb-3" id="email" type="email" name="email" value="{{ old('email') }}" required>
  <label class="form-label" for="password">{{ __('soko.auth.password') }}</label><input class="form-control mb-3" id="password" type="password" name="password" required>
  <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="r"><label class="form-check-label" for="r">{{ __('soko.auth.remember') }}</label></div>
  <button class="btn btn-dark">{{ __('soko.auth.login_btn') }}</button>
</form></div></div>
@endsection
