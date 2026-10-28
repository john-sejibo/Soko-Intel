@extends('layouts.app')
@section('title', __('soko.admin.nav.reports'))
@section('content')
<h1 class="h2 mb-3">{{ __('soko.admin.reports_title') }}</h1>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.user') }}</th><th>{{ __('soko.admin.th.product') }}</th><th>{{ __('soko.admin.th.market') }}</th><th class="text-end">{{ __('soko.admin.th.price') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @forelse($reports as $r)
    <tr>
      <td>{{ $r->user->name }}</td><td>{{ $r->product->name }}</td><td>{{ $r->market->name }}</td>
      <td class="num text-end">{{ number_format($r->price) }}</td>
      <td class="d-flex gap-1">
        <form method="POST" action="{{ route('admin.approve', $r) }}">@csrf<button class="btn btn-success btn-sm">{{ __('soko.admin.act.approve') }}</button></form>
        <form method="POST" action="{{ route('admin.reject', $r) }}">@csrf<button class="btn btn-outline-dark btn-sm">{{ __('soko.admin.act.reject') }}</button></form>
      </td>
    </tr>
  @empty<tr><td colspan="5" class="text-center">{{ __('soko.admin.reports_empty') }}</td></tr>@endforelse
  </tbody>
</table>
</div>
@endsection
