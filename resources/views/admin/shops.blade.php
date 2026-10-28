@extends('layouts.app')
@section('title', __('soko.admin.nav.shops'))
@section('content')
<h1 class="h2 mb-3">{{ __('soko.admin.shops_title') }}</h1>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.business') }}</th><th>{{ __('soko.admin.th.owner') }}</th><th>{{ __('soko.admin.th.market') }}</th><th>{{ __('soko.admin.th.location') }}</th><th>{{ __('soko.admin.th.verified') }}</th><th>{{ __('soko.admin.th.status') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @foreach($shops as $s)
    <tr>
      <td>{{ $s->business_name }}<br><small class="text-muted">{{ $s->phone }}</small></td>
      <td>{{ $s->user->name }}<br><small class="text-muted">{{ $s->user->email }}</small></td>
      <td>{{ $s->market->name }}</td>
      <td>@if($s->gmapsUrl())<a href="{{ $s->gmapsUrl() }}" target="_blank" rel="noopener">{{ __('soko.admin.map_open') }}</a>@else - @endif</td>
      <td><span class="badge text-bg-{{ $s->is_verified ? 'success' : 'warning' }}">{{ $s->is_verified ? __('soko.admin.th.verified') : __('soko.admin.status.pending') }}</span></td>
      <td><span class="badge text-bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ __('soko.admin.status.'.$s->status) }}</span></td>
      <td class="d-flex gap-1 flex-wrap">
        <form method="POST" action="{{ route('admin.shops.verify', $s) }}">@csrf<button class="btn btn-outline-dark btn-sm">{{ $s->is_verified ? __('soko.admin.act.unverify') : __('soko.admin.act.verify') }}</button></form>
        <form method="POST" action="{{ route('admin.shops.status', $s) }}">@csrf<button class="btn btn-outline-dark btn-sm">{{ $s->status === 'active' ? __('soko.admin.act.suspend') : __('soko.admin.act.activate') }}</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
{{ $shops->links() }}
@endsection
