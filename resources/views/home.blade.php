@extends('layouts.app')
@section('content')
<header class="mb-4">
  <h1 class="display-6 fw-bold mb-3" style="max-width: 18ch">Jua bei kabla ya kwenda sokoni.</h1>
  <form method="GET" class="row g-2">
    <div class="col-md-7"><input class="form-control hero-search" name="q" value="{{ request('q') }}" placeholder="Andika bidhaa, mfano RAM DDR4 16GB" aria-label="Tafuta bidhaa"></div>
    <div class="col-md-3">
      <select class="form-select hero-search" name="category" aria-label="Category">
        <option value="">Categories zote</option>
        @foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-md-2"><button class="btn btn-dark w-100 h-100">Tafuta</button></div>
  </form>
</header>

<section aria-label="Matokeo">
  @forelse($products as $p)
    <a class="result" href="{{ route('products.show', $p) }}">
      <div>
        <div class="result-name">{{ $p->name }}</div>
        <div class="board-meta">{{ $p->brand }}, {{ $p->category->name }}</div>
      </div>
      <div class="text-end">
        @if($p->min_price)
          <div class="board-price">TSh {{ number_format($p->min_price) }}</div>
          <div class="board-meta">bei ya chini kwa siku 30</div>
        @else
          <div class="board-meta">Bei haijapatikana</div>
        @endif
      </div>
    </a>
  @empty
    <p class="py-4">Hakuna bidhaa iliyopatikana. Jaribu neno lingine, au <a href="{{ route('report.create') }}">ripoti bei</a> ya bidhaa hiyo.</p>
  @endforelse
</section>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
