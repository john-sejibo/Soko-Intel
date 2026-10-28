@extends('layouts.app')
@section('title', $market->exists ? __('soko.market.title_edit') : __('soko.market.title_new'))
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<h1 class="h3 mb-3">{{ $market->exists ? __('soko.market.title_edit') : __('soko.market.title_new') }}</h1>
<form method="POST" action="{{ $market->exists ? route('admin.markets.update', $market) : route('admin.markets.store') }}">
  @csrf
  @if($market->exists)@method('PUT')@endif
  <div class="mb-3"><label class="form-label" for="name">{{ __('soko.market.name') }}</label><input class="form-control" id="name" name="name" value="{{ old('name', $market->name) }}" required></div>
  <div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label" for="city">{{ __('soko.market.city') }}</label><input class="form-control" id="city" name="city" value="{{ old('city', $market->city) }}"></div>
    <div class="col-md-4"><label class="form-label" for="area">{{ __('soko.market.area') }}</label><input class="form-control" id="area" name="area" value="{{ old('area', $market->area) }}"></div>
    <div class="col-md-4"><label class="form-label" for="address">{{ __('soko.market.address') }}</label><input class="form-control" id="address" name="address" value="{{ old('address', $market->address) }}"></div>
  </div>
  <div class="mb-4"><label class="form-label" for="description">{{ __('soko.market.description') }}</label><textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $market->description) }}</textarea></div>
  <h2 class="section-title">{{ __('soko.market.location') }}</h2>
  @include('partials.map-picker', ['lat' => $market->latitude, 'lng' => $market->longitude])
  <div class="d-flex gap-2">
    <button class="btn btn-dark">{{ __('soko.market.save') }}</button>
    <a class="btn btn-outline-light" href="{{ route('admin.markets.index') }}">{{ __('soko.market.cancel') }}</a>
  </div>
</form>
</div></div>
@endsection
