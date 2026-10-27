@extends('layouts.app')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
<h3>Ripoti bei uliyoiona / uliyonunulia</h3>
<form method="POST" action="{{ route('report.store') }}" class="card card-body shadow-sm">@csrf
  <label class="form-label">Bidhaa</label>
  <select class="form-select mb-3" name="product_id" required>@foreach($products as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select>
  <label class="form-label">Soko</label>
  <select class="form-select mb-3" name="market_id" required>@foreach($markets as $m)<option value="{{ $m->id }}">{{ $m->name }}</option>@endforeach</select>
  <label class="form-label">Bei (TSh)</label><input class="form-control mb-3" type="number" name="price" required>
  <button class="btn btn-dark">Tuma ripoti</button>
</form></div></div>
@endsection
