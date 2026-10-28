@extends('layouts.app')
@section('title', __('soko.admin.nav.users'))
@section('content')
<h1 class="h2 mb-3">{{ __('soko.admin.users_title') }}</h1>
@include('partials.admin-nav')
<div class="table-responsive">
<table class="table align-middle">
  <thead><tr><th>{{ __('soko.admin.th.name') }}</th><th>{{ __('soko.admin.th.email') }}</th><th>{{ __('soko.admin.th.role') }}</th><th>{{ __('soko.admin.th.active') }}</th><th>{{ __('soko.admin.th.actions') }}</th></tr></thead>
  <tbody>
  @foreach($users as $u)
    <tr>
      <td>{{ $u->name }}</td>
      <td>{{ $u->email }}</td>
      <td>
        @if($u->id === auth()->id())
          <span class="badge text-bg-dark">{{ __('soko.admin.role.'.$u->role) }}</span>
        @else
        <form method="POST" action="{{ route('admin.users.role', $u) }}" class="d-flex gap-1">@csrf
          <select class="form-select form-select-sm" name="role" aria-label="{{ __('soko.admin.th.role') }}">
            @foreach(['buyer', 'seller', 'admin'] as $role)<option value="{{ $role }}" @selected($u->role === $role)>{{ __('soko.admin.role.'.$role) }}</option>@endforeach
          </select>
          <button class="btn btn-outline-dark btn-sm">{{ __('soko.admin.act.save') }}</button>
        </form>
        @endif
      </td>
      <td><span class="badge text-bg-{{ $u->is_active ? 'success' : 'secondary' }}">{{ $u->is_active ? __('soko.admin.status.enabled') : __('soko.admin.status.disabled') }}</span></td>
      <td>
        @if($u->id !== auth()->id())
        <form method="POST" action="{{ route('admin.users.active', $u) }}">@csrf<button class="btn btn-outline-dark btn-sm">{{ $u->is_active ? __('soko.admin.act.disable') : __('soko.admin.act.enable') }}</button></form>
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>
{{ $users->links() }}
@endsection
