@extends('layouts.app')
@section('title', __('soko.admin.nav.markets'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h2 mb-0">{{ __('soko.admin.markets_title') }}</h1>
  <a class="btn btn-dark" href="{{ route('admin.markets.create') }}">{{ __('soko.market.title_new') }}</a>
</div>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.name') }}</th><th>{{ __('soko.admin.th.area') }}</th><th>{{ __('soko.admin.th.location') }}</th><th>{{ __('soko.admin.th.status') }}</th><th class="text-end">{{ __('soko.admin.th.shops') }}</th><th class="text-end">{{ __('soko.admin.th.records') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @foreach($markets as $m)
    <tr>
      <td>{{ $m->name }}</td>
      <td>{{ $m->area }}</td>
      <td>@if($m->gmapsUrl())<a href="{{ $m->gmapsUrl() }}" target="_blank" rel="noopener">{{ __('soko.admin.map_open') }}</a>@else - @endif</td>
      <td><span class="badge text-bg-{{ $m->status === 'pending' ? 'warning' : 'success' }}">{{ __('soko.admin.status.'.$m->status) }}</span></td>
      <td class="num text-end">{{ $m->sellers_count }}</td>
      <td class="num text-end">{{ $m->price_records_count }}</td>
      <td class="d-flex gap-1 flex-wrap">
        @if($m->status === 'pending')<form method="POST" action="{{ route('admin.markets.approve', $m) }}">@csrf<button class="btn btn-success btn-sm">{{ __('soko.admin.act.approve') }}</button></form>@endif
        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.markets.edit', $m) }}">{{ __('soko.admin.act.edit') }}</a>
        <form method="POST" action="{{ route('admin.markets.destroy', $m) }}" onsubmit='return confirm(@json(__("soko.admin.act.confirm_delete")))'>@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">{{ __('soko.admin.act.delete') }}</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection
