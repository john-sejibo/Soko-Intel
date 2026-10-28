@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
<h1 class="h3">{{ __('soko.report.title') }}</h1>
<form method="POST" action="{{ route('report.store') }}" class="card card-body">@csrf
  <label class="form-label" for="product_id">{{ __('soko.report.product') }}</label>
  <select class="form-select mb-3" id="product_id" name="product_id" required>@foreach($products as $p)<option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->name }}</option>@endforeach</select>
  <label class="form-label" for="market_id">{{ __('soko.report.market') }}</label>
  <select class="form-select mb-3" id="market_id" name="market_id" required>@foreach($markets as $m)<option value="{{ $m->id }}" @selected(old('market_id') == $m->id)>{{ $m->name }}</option>@endforeach</select>
  <label class="form-label" for="price">{{ __('soko.report.price') }}</label><input class="form-control mb-3" id="price" type="number" name="price" value="{{ old('price') }}" required>
  <button class="btn btn-dark">{{ __('soko.report.send') }}</button>
</form></div></div>
@endsection
