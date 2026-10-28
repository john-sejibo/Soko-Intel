@extends('layouts.app')
@section('title', __('soko.nav.admin'))
@section('content')
<h1 class="h2 mb-3">{{ __('soko.admin.overview_title') }}</h1>
@include('partials.admin-nav')

<div class="row g-3 mb-5">
  @foreach(['users', 'shops', 'markets', 'products', 'records'] as $k)
    <div class="col-6 col-md"><div class="stat"><div class="stat-n num">{{ number_format($counts[$k]) }}</div><div class="board-meta">{{ __('soko.admin.counts.'.$k) }}</div></div></div>
  @endforeach
</div>

<div class="row g-5">
  <section class="col-lg-6">
    <div class="rule-top">
      <h2 class="section-title">{{ __('soko.admin.pending_title') }}</h2>
      @php $any = array_sum($pending) > 0; @endphp
      @if($any)
      <ul class="feed">
        @if($pending['reports'])<li><a href="{{ route('admin.reports') }}"><strong>{{ $pending['reports'] }}</strong> {{ __('soko.admin.pending_reports') }}</a></li>@endif
        @if($pending['markets'])<li><a href="{{ route('admin.markets.index') }}"><strong>{{ $pending['markets'] }}</strong> {{ __('soko.admin.pending_markets') }}</a></li>@endif
        @if($pending['products'])<li><a href="{{ route('admin.products.index') }}"><strong>{{ $pending['products'] }}</strong> {{ __('soko.admin.pending_products') }}</a></li>@endif
        @if($pending['shops'])<li><a href="{{ route('admin.shops') }}"><strong>{{ $pending['shops'] }}</strong> {{ __('soko.admin.pending_shops') }}</a></li>@endif
      </ul>
      @else
        <p>{{ __('soko.admin.nothing_pending') }}</p>
      @endif
    </div>
  </section>
  <section class="col-lg-6">
    <div class="rule-top">
      <h2 class="section-title">{{ __('soko.admin.export_title') }}</h2>
      <p class="section-sub">{{ __('soko.admin.export_desc') }}</p>
      <a class="btn btn-dark" href="{{ route('admin.export.prices') }}">{{ __('soko.admin.export_btn') }}</a>
    </div>
  </section>
</div>
@endsection
