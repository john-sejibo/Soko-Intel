@extends('layouts.app')
@section('title', $shop->exists ? __('soko.shop.edit_title') : __('soko.shop.create_title'))
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<h1 class="h3 mb-3">{{ $shop->exists ? __('soko.shop.edit_title') : __('soko.shop.create_title') }}</h1>
<form method="POST" action="{{ $shop->exists ? route('seller.shops.update', $shop) : route('seller.shops.store') }}">
  @csrf
  @if($shop->exists)@method('PUT')@endif

  <div class="mb-3">
    <label class="form-label" for="business_name">{{ __('soko.shop.name') }}</label>
    <input class="form-control" id="business_name" name="business_name" value="{{ old('business_name', $shop->business_name) }}" required>
  </div>

  <div class="mb-3">
    <label class="form-label" for="market_id">{{ __('soko.shop.market') }}</label>
    <select class="form-select" id="market_id" name="market_id" required>
      @foreach($markets as $m)
        <option value="{{ $m->id }}" @selected(old('market_id', $shop->market_id) == $m->id)>{{ $m->name }}@if($m->area), {{ $m->area }}@endif @if($m->status === 'pending') {{ __('soko.shop.market_pending') }}@endif</option>
      @endforeach
    </select>
    <div class="form-text">{{ __('soko.shop.market_missing') }} <a href="{{ route('seller.markets.create') }}">{{ __('soko.shop.add_market') }}</a></div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label" for="phone">{{ __('soko.shop.phone') }}</label><input class="form-control" id="phone" name="phone" value="{{ old('phone', $shop->phone) }}"></div>
    <div class="col-md-6"><label class="form-label" for="whatsapp">{{ __('soko.shop.whatsapp') }}</label><input class="form-control" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $shop->whatsapp) }}"></div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6"><label class="form-label" for="address">{{ __('soko.shop.address') }}</label><input class="form-control" id="address" name="address" value="{{ old('address', $shop->address) }}"></div>
    <div class="col-md-6"><label class="form-label" for="opening_hours">{{ __('soko.shop.hours') }}</label><input class="form-control" id="opening_hours" name="opening_hours" placeholder="{{ __('soko.shop.hours_ph') }}" value="{{ old('opening_hours', $shop->opening_hours) }}"></div>
  </div>

  <div class="mb-4">
    <label class="form-label" for="description">{{ __('soko.shop.description') }}</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $shop->description) }}</textarea>
  </div>

  <h2 class="section-title">{{ __('soko.shop.location') }}</h2>
  @include('partials.map-picker', ['lat' => $shop->latitude, 'lng' => $shop->longitude])

  <div class="d-flex gap-2">
    <button class="btn btn-dark">{{ __('soko.shop.save') }}</button>
    <a class="btn btn-outline-light" href="{{ route('seller.dashboard') }}">{{ __('soko.shop.cancel') }}</a>
  </div>
</form>
</div></div>
@endsection
