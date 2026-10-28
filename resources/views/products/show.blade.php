@extends('layouts.app')
@section('title', $product->name)
@section('content')
<a href="{{ route('home') }}" class="small">&larr; {{ __('soko.product.back') }}</a>
<header class="d-flex flex-wrap align-items-baseline gap-3 mt-2 mb-4">
  <h1 class="h2 mb-0">{{ $product->name }}</h1>
  <span class="board-meta">{{ $product->brand }}</span>
  @if(!is_null($change))
    <span class="pill {{ $change > 0 ? 'up' : ($change < 0 ? 'down' : 'flat') }}">
      {{ $change > 0 ? '▲' : ($change < 0 ? '▼' : '=') }} {{ __('soko.product.week', ['pct' => abs($change)]) }}
    </span>
  @endif
</header>

<div class="row g-5">
  <section class="col-lg-7" aria-labelledby="mk-h">
    <h2 id="mk-h" class="section-title">{{ __('soko.product.per_market') }}</h2>
    @if($latest->count())
      <p class="section-sub">{{ __('soko.product.cheapest', ['market' => $latest->first()->market->name, 'price' => number_format($latest->first()->price)]) }}</p>
    @endif
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>{{ __('soko.product.th_market') }}</th><th>{{ __('soko.product.th_seller') }}</th><th class="text-end">{{ __('soko.product.th_price') }}</th><th class="text-end">{{ __('soko.product.th_stock') }}</th><th>{{ __('soko.product.th_when') }}</th><th>{{ __('soko.product.th_map') }}</th></tr></thead>
        <tbody>
        @forelse($latest as $i => $r)
          <tr class="{{ $i === 0 ? 'row-cheapest' : '' }}">
            <td>{{ $r->market->name }}<br><small class="text-muted">{{ $r->market->area }}</small></td>
            <td>{{ $r->seller?->business_name ?? __('soko.product.user_report') }} @if($r->seller?->is_verified)<span class="badge text-bg-success">{{ __('soko.product.verified') }}</span>@endif</td>
            <td class="num text-end fw-bold">{{ number_format($r->price) }}</td>
            <td class="num text-end">{{ $r->stock_qty ?? '-' }}</td>
            <td><small>{{ $r->recorded_at->diffForHumans() }}</small></td>
            <td>@if($r->market->gmapsUrl())<a href="{{ $r->market->gmapsUrl() }}" target="_blank" rel="noopener">{{ __('soko.product.directions') }}</a>@endif</td>
          </tr>
        @empty
          <tr><td colspan="6">{{ __('soko.product.no_prices') }} <a href="{{ route('report.create') }}">{{ __('soko.product.report_link') }}</a></td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <p class="small text-muted">{{ __('soko.product.disclaimer') }}</p>
  </section>

  <section class="col-lg-5" aria-labelledby="hist-h">
    <h2 id="hist-h" class="section-title">{{ __('soko.product.history_title') }}</h2>
    <p class="section-sub">{{ __('soko.product.history_sub') }}</p>
    <canvas id="priceChart" height="240" role="img" aria-label="{{ __('soko.product.chart_aria') }}"></canvas>
  </section>
</div>

@if($points->count())
<section class="mt-5" aria-labelledby="where-h">
  <h2 id="where-h" class="section-title">{{ __('soko.product.map_title') }}</h2>
  @include('partials.markets-map', ['mapId' => 'product-map', 'markets' => $points])
</section>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
  var css = getComputedStyle(document.documentElement);
  new Chart(document.getElementById('priceChart'), {
    type: 'line',
    data: {
      labels: @json($history->pluck('d')),
      datasets: [
        { label: @json(__('soko.product.chart_avg')), data: @json($history->pluck('avg_price')->map(fn($v) => round($v))), borderColor: css.getPropertyValue('--soko-teal').trim(), backgroundColor: 'transparent', tension: .3, pointRadius: 2 },
        { label: @json(__('soko.product.chart_min')), data: @json($history->pluck('min_price')), borderColor: css.getPropertyValue('--soko-sand').trim(), backgroundColor: 'transparent', tension: .3, pointRadius: 2 }
      ]
    },
    options: { scales: { y: { ticks: { callback: function (v) { return v.toLocaleString(); } } } } }
  });
</script>
@endpush
