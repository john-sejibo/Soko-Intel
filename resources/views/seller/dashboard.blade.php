@extends('layouts.app')
@section('content')
<h3>Dashboard ya muuzaji</h3>
<div class="row g-4">
  <div class="col-md-5">
    <div class="card card-body shadow-sm mb-3">
      <h5>Profile ya biashara</h5>
      <form method="POST" action="{{ route('seller.profile') }}">@csrf
        <label class="form-label">Jina la biashara</label><input class="form-control mb-2" name="business_name" value="{{ old('business_name', $seller?->business_name) }}" required>
        <label class="form-label">Soko</label>
        <select class="form-select mb-2" name="market_id" required>
          @foreach($markets as $m)<option value="{{ $m->id }}" @selected($seller?->market_id == $m->id)>{{ $m->name }}</option>@endforeach
        </select>
        <label class="form-label">Simu</label><input class="form-control mb-3" name="phone" value="{{ old('phone', $seller?->phone) }}">
        <button class="btn btn-dark">Hifadhi</button>
      </form>
    </div>
    @if($seller)
    <div class="card card-body shadow-sm">
      <h5>Weka / sasisha bei</h5>
      <form method="POST" action="{{ route('seller.price') }}">@csrf
        <select class="form-select mb-2" name="product_id" required>
          @foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach
        </select>
        <input class="form-control mb-2" type="number" name="price" placeholder="Bei (TSh)" required>
        <input class="form-control mb-3" type="number" name="stock_qty" placeholder="Stock (hiari)">
        <button class="btn btn-success">Hifadhi bei</button>
      </form>
    </div>
    @endif
  </div>
  <div class="col-md-7">
    <div class="card shadow-sm"><div class="card-header">Bei ulizoweka hivi karibuni</div>
      <table class="table mb-0"><thead><tr><th>Bidhaa</th><th class="text-end">Bei</th><th>Stock</th><th>Lini</th></tr></thead><tbody>
      @forelse($records as $r)
        <tr><td>{{ $r->product->name }}</td><td class="text-end">{{ number_format($r->price) }}</td><td>{{ $r->stock_qty ?? '-' }}</td><td><small>{{ $r->recorded_at->format('d M H:i') }}</small></td></tr>
      @empty<tr><td colspan="4" class="text-center">Bado hujaweka bei.</td></tr>@endforelse
      </tbody></table>
    </div>
  </div>
</div>
@endsection
