@extends('layouts.app')
@section('content')
<header class="mb-4">
  <h1 class="display-6 fw-bold mb-3" style="max-width: 18ch">{{ __('soko.home.headline') }}</h1>
  <form method="GET" class="row g-2">
    <div class="col-md-7"><input class="form-control hero-search" name="q" value="{{ request('q') }}" placeholder="{{ __('soko.home.search_placeholder') }}" aria-label="{{ __('soko.home.search_label') }}"></div>
    <div class="col-md-3">
      <select class="form-select hero-search" name="category" aria-label="Category">
        <option value="">{{ __('soko.home.all_categories') }}</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-md-2"><button class="btn btn-dark w-100 h-100">{{ __('soko.home.search_btn') }}</button></div>
  </form>
</header>

<section aria-label="{{ __('soko.home.search_label') }}">
  @forelse($products as $p)
    <a class="result" href="{{ route('products.show', $p) }}">
      <div>
        <div class="result-name">{{ $p->name }}</div>
        <div class="board-meta">{{ $p->brand }}, {{ $p->category->name }}</div>
      </div>
      <div class="text-end">
        @if($p->min_price)
          <div class="board-price">TSh {{ number_format($p->min_price) }}</div>
          <div class="board-meta">{{ __('soko.home.min_30d') }}</div>
        @else
          <div class="board-meta">{{ __('soko.home.no_price') }}</div>
        @endif
      </div>
    </a>
  @empty
    <p class="py-4">{{ __('soko.home.empty') }} @auth<a href="{{ route('report.create') }}">{{ __('soko.home.report_price') }}</a>@else<a href="{{ route('login') }}">{{ __('soko.home.report_price') }}</a>@endauth.</p>
  @endforelse
</section>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
