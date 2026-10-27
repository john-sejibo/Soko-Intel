@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<h3>Ingia</h3>
<form method="POST" action="{{ url('/login') }}" class="card card-body shadow-sm">@csrf
  <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" value="{{ old('email') }}" required>
  <label class="form-label">Password</label><input class="form-control mb-3" type="password" name="password" required>
  <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remember" id="r"><label class="form-check-label" for="r">Nikumbuke</label></div>
  <button class="btn btn-dark">Ingia</button>
</form></div></div>
@endsection
