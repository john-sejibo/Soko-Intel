@extends('layouts.app')
@section('title', __('soko.admin.nav.records'))
@section('content')
<h1 class="h2 mb-1">{{ __('soko.admin.records_title') }}</h1>
<p class="section-sub">{{ __('soko.admin.records_note') }}</p>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.product') }}</th><th>{{ __('soko.admin.th.market') }}</th><th>{{ __('soko.admin.th.seller') }}</th><th class="text-end">{{ __('soko.admin.th.price') }}</th><th>{{ __('soko.admin.th.time') }}</th><th></th></tr></thead>
  <tbody>
  @foreach($records as $r)
    <tr>
      <td>{{ $r->product?->name }}</td><td>{{ $r->market?->name }}</td><td>{{ $r->seller?->business_name ?? $r->source }}</td>
      <td class="num text-end">{{ number_format($r->price) }}</td><td><small>{{ $r->recorded_at->format('d M Y H:i') }}</small></td>
      <td><form method="POST" action="{{ route('admin.records.destroy', $r) }}" onsubmit='return confirm(@json(__("soko.admin.act.confirm_delete")))'>@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">{{ __('soko.admin.act.delete') }}</button></form></td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
{{ $records->links() }}
@endsection
