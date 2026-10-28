@extends('layouts.app')
@section('title', __('soko.pform.propose_title'))
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<h1 class="h3 mb-1">{{ __('soko.pform.propose_title') }}</h1>
<p class="section-sub">{{ __('soko.pform.note') }}</p>
<form method="POST" action="{{ route('seller.products.store') }}" class="card card-body">@csrf
  <label class="form-label" for="category_id">{{ __('soko.pform.category') }}</label>
  <select class="form-select mb-3" id="category_id" name="category_id" required>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>@endforeach</select>
  <label class="form-label" for="name">{{ __('soko.pform.name') }}</label><input class="form-control mb-3" id="name" name="name" value="{{ old('name') }}" required>
  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label" for="brand">{{ __('soko.pform.brand') }}</label><input class="form-control" id="brand" name="brand" value="{{ old('brand') }}"></div>
    <div class="col-md-6"><label class="form-label" for="model">{{ __('soko.pform.model') }}</label><input class="form-control" id="model" name="model" value="{{ old('model') }}"></div>
  </div>
  <button class="btn btn-dark">{{ __('soko.pform.save') }}</button>
</form></div></div>
@endsection
