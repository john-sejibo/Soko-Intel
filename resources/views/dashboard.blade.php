@extends('layouts.app')
@section('title', __('soko.nav.dashboard'))
@section('content')
<header class="mb-4">
  <h1 class="h2 mb-1">{{ __('soko.dash.title') }}</h1>
  <p class="section-sub mb-0">{{ __('soko.dash.subtitle', ['products' => $stats['products'], 'markets' => $stats['markets'], 'today' => $stats['today'], 'sellers' => $stats['sellers']]) }}</p>
</header>

<section class="mb-5" aria-labelledby="movers-h">
  <h2 id="movers-h" class="section-title">{{ __('soko.dash.movers_title') }}</h2>
  <p class="section-sub">{{ __('soko.dash.movers_sub') }}</p>
  <div class="board">
    @forelse($movers as $m)
      @php $dir = is_null($m->change) || $m->change == 0 ? 'flat' : ($m->change > 0 ? 'up' : 'down'); @endphp
      <a class="board-row is-{{ $dir }}" href="{{ route('products.show', $m->product) }}">
        <div>
          <div class="board-name">{{ $m->product->name }}</div>
          <div class="board-meta">{{ $m->product->brand }}</div>
        </div>
        <div class="spark-wrap" aria-hidden="true">
          @if($m->spark)<svg class="spark" viewBox="0 0 100 28" preserveAspectRatio="none"><polyline points="{{ $m->spark }}" fill="none" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke" stroke-linejoin="round" stroke-linecap="round"/></svg>@endif
        </div>
        <div class="board-price">TSh {{ number_format($m->price) }}<br><small>{{ __('soko.dash.wk_avg') }}</small></div>
        <div class="chg">
          @if(is_null($m->change)) {{ __('soko.dash.soon') }} @else {{ $dir === 'up' ? '▲' : ($dir === 'down' ? '▼' : '=') }} {{ abs($m->change) }}% @endif
        </div>
      </a>
    @empty
      <p class="p-4 mb-0">{{ __('soko.dash.no_movers') }}</p>
    @endforelse
  </div>
</section>

<div class="row g-5">
  <section class="col-lg-7" aria-labelledby="levels-h">
    <div class="rule-top">
      <h2 id="levels-h" class="section-title">{{ __('soko.dash.levels_title') }}</h2>
      <p class="section-sub">{{ __('soko.dash.levels_sub') }}</p>
      @php $max = max(110, ($levels->max('index') ?? 100) + 5); @endphp
      @forelse($levels as $i => $l)
        <div class="level {{ $i === 0 ? 'is-cheapest' : '' }}">
          <div>{{ $l->name }}</div>
          <div class="level-track" role="img" aria-label="{{ $l->name }}: {{ $l->index }}">
            <div class="level-bar" style="width: {{ min(100, $l->index / $max * 100) }}%"></div>
            <div class="level-base" style="left: {{ 100 / $max * 100 }}%"></div>
          </div>
          <div class="num text-end fw-bold">{{ $l->index }}</div>
        </div>
      @empty
        <p>{{ __('soko.dash.levels_empty') }}</p>
      @endforelse
      @if($levels->count() > 1)
        <p class="mt-3 mb-0">{{ __('soko.dash.levels_note', ['market' => $levels->first()->name]) }}</p>
      @endif
    </div>
  </section>

  <section class="col-lg-5" aria-labelledby="recent-h">
    <div class="rule-top">
      <h2 id="recent-h" class="section-title">{{ __('soko.dash.recent_title') }}</h2>
      <p class="section-sub">{{ __('soko.dash.recent_sub') }}</p>
      <ul class="feed">
        @forelse($recent as $r)
          <li>
            <div class="d-flex justify-content-between gap-2">
              <a href="{{ route('products.show', $r->product) }}" class="fw-semibold">{{ $r->product->name }}</a>
              <span class="num fw-bold">TSh {{ number_format($r->price) }}</span>
            </div>
            <div class="board-meta">{{ $r->market->name }}, {{ $r->recorded_at->diffForHumans() }}</div>
          </li>
        @empty
          <li>{{ __('soko.dash.recent_empty') }}</li>
        @endforelse
      </ul>
    </div>
  </section>
</div>
@endsection
