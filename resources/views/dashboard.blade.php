@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<header class="mb-4">
  <h1 class="h2 mb-1">Bei sokoni wiki hii</h1>
  <p class="section-sub mb-0">
    Tunafuatilia bidhaa {{ $stats['products'] }} katika masoko {{ $stats['markets'] }}.
    Bei {{ $stats['today'] }} ziliongezwa saa 24 zilizopita na wauzaji {{ $stats['sellers'] }} waliothibitishwa.
  </p>
</header>

<section class="mb-5" aria-labelledby="movers-h">
  <h2 id="movers-h" class="section-title">Bidhaa zilizobadilika zaidi</h2>
  <p class="section-sub">Wastani wa siku 7 za mwisho ukilinganishwa na siku 7 zilizotangulia. Nyekundu ni bei imepanda, kijani ni imeshuka.</p>
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
        <div class="board-price">TSh {{ number_format($m->price) }}<br><small>wastani wiki hii</small></div>
        <div class="chg">
          @if(is_null($m->change)) bado @else {{ $dir === 'up' ? '▲' : ($dir === 'down' ? '▼' : '=') }} {{ abs($m->change) }}% @endif
        </div>
      </a>
    @empty
      <p class="p-4 mb-0">Hakuna bei za siku 7 zilizopita. Wauzaji wakiweka bei, zitaonekana hapa.</p>
    @endforelse
  </div>
</section>

<div class="row g-5">
  <section class="col-lg-7" aria-labelledby="levels-h">
    <div class="rule-top">
      <h2 id="levels-h" class="section-title">Soko lipi ni nafuu?</h2>
      <p class="section-sub">Kiwango cha bei cha kila soko (siku 14). Mstari mweusi ni wastani wa masoko yote (100). Chini ya mstari ni nafuu.</p>
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
        <p>Data haitoshi bado kulinganisha masoko.</p>
      @endforelse
      @if($levels->count() > 1)
        <p class="mt-3 mb-0"><strong>{{ $levels->first()->name }}</strong> ndilo soko lenye bei za chini zaidi kwenye data tuliyokusanya. Bei zinategemea muuzaji na stock.</p>
      @endif
    </div>
  </section>

  <section class="col-lg-5" aria-labelledby="recent-h">
    <div class="rule-top">
      <h2 id="recent-h" class="section-title">Bei mpya zilizoingia</h2>
      <p class="section-sub">Sasisho za hivi karibuni kutoka sokoni.</p>
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
          <li>Hakuna sasisho bado.</li>
        @endforelse
      </ul>
    </div>
  </section>
</div>
@endsection
