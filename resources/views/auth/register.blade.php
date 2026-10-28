@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<h1 class="h3">{{ __('soko.auth.register_title') }}</h1>
<form method="POST" action="{{ url('/register') }}" class="card card-body">@csrf
  <label class="form-label" for="name">{{ __('soko.auth.name') }}</label><input class="form-control mb-3" id="name" name="name" value="{{ old('name') }}" required>
  <label class="form-label" for="email">{{ __('soko.auth.email') }}</label><input class="form-control mb-3" id="email" type="email" name="email" value="{{ old('email') }}" required>
  <label class="form-label" for="password">{{ __('soko.auth.password_hint') }}</label><input class="form-control mb-3" id="password" type="password" name="password" required>
  <label class="form-label" for="pc">{{ __('soko.auth.password_confirm') }}</label><input class="form-control mb-3" id="pc" type="password" name="password_confirmation" required>
  <label class="form-label" for="role">{{ __('soko.auth.role_label') }}</label>
  <select class="form-select mb-3" id="role" name="role"><option value="buyer" @selected(old('role') === 'buyer')>{{ __('soko.auth.role_buyer') }}</option><option value="seller" @selected(old('role') === 'seller')>{{ __('soko.auth.role_seller') }}</option></select>
  <button class="btn btn-dark">{{ __('soko.auth.register_btn') }}</button>
</form></div></div>
@endsection
