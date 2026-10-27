@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-5">
<h3>Jisajili</h3>
<form method="POST" action="{{ url('/register') }}" class="card card-body shadow-sm">@csrf
  <label class="form-label">Jina</label><input class="form-control mb-3" name="name" value="{{ old('name') }}" required>
  <label class="form-label">Email</label><input class="form-control mb-3" type="email" name="email" value="{{ old('email') }}" required>
  <label class="form-label">Password (angalau herufi 8)</label><input class="form-control mb-3" type="password" name="password" required>
  <label class="form-label">Rudia password</label><input class="form-control mb-3" type="password" name="password_confirmation" required>
  <label class="form-label">Mimi ni</label>
  <select class="form-select mb-3" name="role"><option value="buyer">Mnunuzi</option><option value="seller">Muuzaji</option></select>
  <button class="btn btn-dark">Jisajili</button>
</form></div></div>
@endsection
