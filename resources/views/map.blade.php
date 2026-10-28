@extends('layouts.app')
@section('title', __('soko.nav.map'))
@section('content')
<header class="mb-4">
  <h1 class="h2 mb-1">{{ __('soko.map.title') }}</h1>
  <p class="section-sub">{{ __('soko.map.sub') }}</p>
  <form method="GET" class="row g-2 align-items-end">
    <div class="col-md-6">
      <label class="form-label" for="product">{{ __('soko.map.product_filter') }}</label>
      <select class="form-select" id="product" name="product">
        <option value="">{{ __('soko.map.all_products') }}</option>
        @foreach($products as $p)<option value="{{ $p->slug }}" @selected($product && $product->id === $p->id)>{{ $p->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-md-2"><button class="btn btn-dark w-100">{{ __('soko.map.show') }}</button></div>
  </form>
</header>

@if($markets->count() || $shops->count())
  <p class="board-meta mb-2"><span class="legend-dot" style="background:#0b7a75"></span> {{ __('soko.map.legend_markets') }} &nbsp; <span class="legend-dot" style="background:#e8b931"></span> {{ __('soko.map.legend_shops') }}</p>
  @include('partials.markets-map', ['mapId' => 'main-map', 'markets' => $markets, 'shops' => $shops])
@else
  <p>{{ __('soko.map.none') }}</p>
@endif
@endsection
