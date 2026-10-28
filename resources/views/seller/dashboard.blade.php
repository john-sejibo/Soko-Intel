@extends('layouts.app')
@section('title', __('soko.seller.title'))
@section('content')
<h1 class="h2 mb-4">{{ __('soko.seller.title') }}</h1>
<div class="row g-5">
  <section class="col-lg-7" aria-labelledby="shops-h">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h2 id="shops-h" class="section-title mb-0">{{ __('soko.seller.my_shops') }}</h2>
      <a class="btn btn-dark btn-sm" href="{{ route('seller.shops.create') }}">{{ __('soko.seller.add_shop') }}</a>
    </div>
    @forelse($shops as $s)
      <div class="shop-row">
        <div>
          <div class="fw-semibold">{{ $s->business_name }}
            @if($s->status !== 'active')<span class="badge text-bg-secondary">{{ __('soko.seller.suspended') }}</span>@endif
            <span class="badge text-bg-{{ $s->is_verified ? 'success' : 'warning' }}">{{ $s->is_verified ? __('soko.seller.verified') : __('soko.seller.unverified') }}</span>
          </div>
          <div class="board-meta">{{ $s->market->name }}@if($s->address), {{ $s->address }}@endif @if($s->phone) &middot; {{ $s->phone }}@endif</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
          @if($s->gmapsUrl())<a class="small" href="{{ $s->gmapsUrl() }}" target="_blank" rel="noopener">{{ __('soko.admin.map_open') }}</a>@endif
          <a class="btn btn-outline-dark btn-sm" href="{{ route('seller.shops.edit', $s) }}">{{ __('soko.seller.edit') }}</a>
        </div>
      </div>
    @empty
      <p>{{ __('soko.seller.no_shops') }}</p>
    @endforelse

    <h2 class="section-title mt-5">{{ __('soko.seller.recent_title') }}</h2>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>{{ __('soko.seller.th_product') }}</th><th>{{ __('soko.seller.th_shop') }}</th><th class="text-end">{{ __('soko.seller.th_price') }}</th><th class="text-end">{{ __('soko.seller.th_stock') }}</th><th>{{ __('soko.seller.th_time') }}</th></tr></thead>
        <tbody>
        @forelse($records as $r)
          <tr><td>{{ $r->product->name }}</td><td>{{ $r->seller?->business_name }}</td><td class="num text-end">{{ number_format($r->price) }}</td><td class="num text-end">{{ $r->stock_qty ?? '-' }}</td><td><small>{{ $r->recorded_at->format('d M H:i') }}</small></td></tr>
        @empty
          <tr><td colspan="5" class="text-center">{{ __('soko.seller.no_recent') }}</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </section>

  <section class="col-lg-5" aria-labelledby="price-h">
    <div class="rule-top">
      <h2 id="price-h" class="section-title">{{ __('soko.seller.price_title') }}</h2>
      @if($shops->where('status', 'active')->count())
      <form method="POST" action="{{ route('seller.price') }}">@csrf
        <label class="form-label" for="shop_id">{{ __('soko.seller.shop') }}</label>
        <select class="form-select mb-2" id="shop_id" name="shop_id" required>
          @foreach($shops->where('status', 'active') as $s)<option value="{{ $s->id }}" @selected(old('shop_id') == $s->id)>{{ $s->business_name }} ({{ $s->market->name }})</option>@endforeach
        </select>
        <label class="form-label" for="product_id">{{ __('soko.seller.product') }}</label>
        <select class="form-select mb-1" id="product_id" name="product_id" required>
          @foreach($products as $p)<option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->name }}@if($p->status === 'pending') {{ __('soko.seller.pending_tag') }}@endif</option>@endforeach
        </select>
        <p class="small mb-2"><a href="{{ route('seller.products.create') }}">{{ __('soko.seller.propose_product') }}</a></p>
        <label class="form-label" for="price">{{ __('soko.seller.price_tsh') }}</label>
        <input class="form-control mb-2" id="price" type="number" name="price" value="{{ old('price') }}" required>
        <label class="form-label" for="stock_qty">{{ __('soko.seller.stock_opt') }}</label>
        <input class="form-control mb-3" id="stock_qty" type="number" name="stock_qty" value="{{ old('stock_qty') }}">
        <button class="btn btn-success">{{ __('soko.seller.save_price') }}</button>
      </form>
      @else
        <p>{{ __('soko.seller.need_shop') }}</p>
      @endif
    </div>
  </section>
</div>
@endsection
