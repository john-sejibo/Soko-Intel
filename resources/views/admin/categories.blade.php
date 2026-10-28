@extends('layouts.app')
@section('title', __('soko.admin.nav.categories'))
@section('content')
<h1 class="h2 mb-3">{{ __('soko.admin.categories_title') }}</h1>
@include('partials.admin-nav')
<form method="POST" action="{{ route('admin.categories.store') }}" class="row g-2 mb-4">@csrf
  <div class="col-md-6"><input class="form-control" name="name" placeholder="{{ __('soko.admin.category_name') }}" aria-label="{{ __('soko.admin.category_name') }}" required></div>
  <div class="col-auto"><button class="btn btn-dark">{{ __('soko.admin.act.add') }}</button></div>
</form>
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.name') }}</th><th class="text-end">{{ __('soko.admin.nav.products') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @foreach($categories as $c)
    <tr>
      <td>
        <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="d-flex gap-1">@csrf @method('PUT')
          <input class="form-control form-control-sm" name="name" value="{{ $c->name }}" aria-label="{{ __('soko.admin.category_name') }}">
          <button class="btn btn-outline-dark btn-sm">{{ __('soko.admin.act.save') }}</button>
        </form>
      </td>
      <td class="num text-end">{{ $c->products_count }}</td>
      <td><form method="POST" action="{{ route('admin.categories.destroy', $c) }}" onsubmit='return confirm(@json(__("soko.admin.act.confirm_delete")))'>@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm">{{ __('soko.admin.act.delete') }}</button></form></td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection
