@extends('layouts.app')
@section('title', __('soko.admin.nav.products'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h2 mb-0">{{ __('soko.admin.products_title') }}</h1>
  <a class="btn btn-dark" href="{{ route('admin.products.create') }}">{{ __('soko.pform.title_new') }}</a>
</div>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.name') }}</th><th>{{ __('soko.admin.th.category') }}</th><th>{{ __('soko.admin.th.brand') }}</th><th>{{ __('soko.admin.th.status') }}</th><th class="text-end">{{ __('soko.admin.th.records') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @foreach($products as $p)
    <tr>
      <td>{{ $p->name }}</td><td>{{ $p->category->name }}</td><td>{{ $p->brand }}</td>
      <td><span class="badge text-bg-{{ $p->status === 'pending' ? 'warning' : 'success' }}">{{ __('soko.admin.status.'.$p->status) }}</span></td>
      <td class="num text-end">{{ $p->price_records_count }}</td>
      <td class="d-flex gap-1 flex-wrap">
        @if($p->status === 'pending')<form method="POST" action="{{ route('admin.products.approve', $p) }}">@csrf<button class="btn btn-success btn-sm">{{ __('soko.admin.act.approve') }}</button></form>@endif
        <a class="btn btn-outline-dark btn-sm" href="{{ route('admin.products.edit', $p) }}">{{ __('soko.admin.act.edit') }}</a>
        <form method="POST" action="{{ route('admin.products.destroy', $p) }}" onsubmit='return confirm(@json(__("soko.admin.act.confirm_delete")))'>@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">{{ __('soko.admin.act.delete') }}</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
{{ $products->links() }}
@endsection
