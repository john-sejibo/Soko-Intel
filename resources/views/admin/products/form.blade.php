@extends('layouts.app')
@section('title', $product->exists ? __('soko.pform.title_edit') : __('soko.pform.title_new'))
@section('content')
<div class="row justify-content-center"><div class="col-md-7">
<h1 class="h3 mb-3">{{ $product->exists ? __('soko.pform.title_edit') : __('soko.pform.title_new') }}</h1>
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="card card-body">
  @csrf
  @if($product->exists)@method('PUT')@endif
  <label class="form-label" for="category_id">{{ __('soko.pform.category') }}</label>
  <select class="form-select mb-3" id="category_id" name="category_id" required>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach</select>
  <label class="form-label" for="name">{{ __('soko.pform.name') }}</label><input class="form-control mb-3" id="name" name="name" value="{{ old('name', $product->name) }}" required>
  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label" for="brand">{{ __('soko.pform.brand') }}</label><input class="form-control" id="brand" name="brand" value="{{ old('brand', $product->brand) }}"></div>
    <div class="col-md-6"><label class="form-label" for="model">{{ __('soko.pform.model') }}</label><input class="form-control" id="model" name="model" value="{{ old('model', $product->model) }}"></div>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-dark">{{ __('soko.pform.save') }}</button>
    <a class="btn btn-outline-light" href="{{ route('admin.products.index') }}">{{ __('soko.market.cancel') }}</a>
  </div>
</form></div></div>
@endsection
