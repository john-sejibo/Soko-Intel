@extends('layouts.app')
@section('title', $product->name)
@section('content')
<a href="{{ route('home') }}" class="small">&larr; Rudi kwenye utafutaji</a>
<header class="d-flex flex-wrap align-items-baseline gap-3 mt-2 mb-4">
  <h1 class="h2 mb-0">{{ $product->name }}</h1>
  <span class="board-meta">{{ $product->brand }}</span>
  @if(!is_null($change))
    <span class="pill {{ $change > 0 ? 'up' : ($change < 0 ? 'down' : 'flat') }}">
      {{ $change > 0 ? '▲' : ($change < 0 ? '▼' : '=') }} {{ abs($change) }}% wiki hii
    </span>
  @endif
</header>

<div class="row g-5">
  <section class="col-lg-6" aria-labelledby="mk-h">
    <h2 id="mk-h" class="section-title">Bei kwa kila soko</h2>
    @if($latest->count())
      <p class="section-sub">Soko nafuu zaidi kwa sasa: <strong>{{ $latest->first()->market->name }}</strong>, TSh {{ number_format($latest->first()->price) }}.</p>
    @endif
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>Soko</th><th>Muuzaji</th><th class="text-end">Bei (TSh)</th><th class="text-end">Stock</th><th>Lini</th></tr></thead>
        <tbody>
        @forelse($latest as $i => $r)
          <tr class="{{ $i === 0 ? 'row-cheapest' : '' }}">
            <td>{{ $r->market->name }}<br><small class="text-muted">{{ $r->market->area }}</small></td>
            <td>{{ $r->seller?->business_name ?? 'Ripoti ya mtumiaji' }} @if($r->seller?->is_verified)<span class="badge text-bg-success">Amethibitishwa</span>@endif</td>
            <td class="num text-end fw-bold">{{ number_format($r->price) }}</td>
            <td class="num text-end">{{ $r->stock_qty ?? '-' }}</td>
            <td><small>{{ $r->recorded_at->diffForHumans() }}</small></td>
          </tr>
        @empty
          <tr><td colspan="5">Hakuna bei bado. <a href="{{ route('report.create') }}">Ripoti bei ya bidhaa hii</a>.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <p class="small text-muted">Bei zinabadilika kulingana na muuzaji na stock. Angalia muda wa kila bei kabla ya kwenda.</p>
  </section>

  <section class="col-lg-6" aria-labelledby="hist-h">
    <h2 id="hist-h" class="section-title">Historia ya bei, siku 30</h2>
    <p class="section-sub">Wastani wa kila siku na bei ya chini zaidi.</p>
    <canvas id="priceChart" height="240" role="img" aria-label="Grafu ya historia ya bei"></canvas>
  </section>
</div>
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
        { label: 'Wastani', data: @json($history->pluck('avg_price')->map(fn($v) => round($v))), borderColor: css.getPropertyValue('--soko-teal').trim(), backgroundColor: 'transparent', tension: .3, pointRadius: 2 },
        { label: 'Ya chini zaidi', data: @json($history->pluck('min_price')), borderColor: css.getPropertyValue('--soko-sand').trim(), backgroundColor: 'transparent', tension: .3, pointRadius: 2 }
      ]
    },
    options: { scales: { y: { ticks: { callback: function (v) { return v.toLocaleString(); } } } } }
  });
</script>
@endpush
