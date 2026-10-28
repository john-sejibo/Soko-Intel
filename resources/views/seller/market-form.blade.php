@extends('layouts.app')
@section('title', __('soko.market.propose_title'))
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<h1 class="h3 mb-1">{{ __('soko.market.propose_title') }}</h1>
<p class="section-sub">{{ __('soko.market.pending_note') }}</p>
<form method="POST" action="{{ route('seller.markets.store') }}">@csrf
  <div class="mb-3"><label class="form-label" for="name">{{ __('soko.market.name') }}</label><input class="form-control" id="name" name="name" value="{{ old('name') }}" required></div>
  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label" for="area">{{ __('soko.market.area') }}</label><input class="form-control" id="area" name="area" value="{{ old('area') }}"></div>
    <div class="col-md-6"><label class="form-label" for="address">{{ __('soko.market.address') }}</label><input class="form-control" id="address" name="address" value="{{ old('address') }}"></div>
  </div>
  <div class="mb-4"><label class="form-label" for="description">{{ __('soko.market.description') }}</label><textarea class="form-control" id="description" name="description" rows="2">{{ old('description') }}</textarea></div>
  <h2 class="section-title">{{ __('soko.market.location') }}</h2>
  @include('partials.map-picker', ['lat' => null, 'lng' => null])
  <div class="d-flex gap-2">
    <button class="btn btn-dark">{{ __('soko.market.save') }}</button>
    <a class="btn btn-outline-light" href="{{ route('seller.shops.create') }}">{{ __('soko.market.cancel') }}</a>
  </div>
</form>
</div></div>
@endsection
