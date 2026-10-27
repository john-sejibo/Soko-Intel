@extends('layouts.app')
@section('content')
<h3>Ripoti za bei zinazosubiri uhakiki</h3>
<table class="table bg-white shadow-sm">
  <thead><tr><th>Mtumiaji</th><th>Bidhaa</th><th>Soko</th><th class="text-end">Bei</th><th></th></tr></thead>
  <tbody>
  @forelse($reports as $r)
    <tr>
      <td>{{ $r->user->name }}</td><td>{{ $r->product->name }}</td><td>{{ $r->market->name }}</td>
      <td class="text-end">{{ number_format($r->price) }}</td>
      <td class="d-flex gap-1">
        <form method="POST" action="{{ route('admin.approve', $r) }}">@csrf<button class="btn btn-success btn-sm">Kubali</button></form>
        <form method="POST" action="{{ route('admin.reject', $r) }}">@csrf<button class="btn btn-outline-danger btn-sm">Kataa</button></form>
      </td>
    </tr>
  @empty<tr><td colspan="5" class="text-center">Hakuna ripoti zinazosubiri.</td></tr>@endforelse
  </tbody>
</table>
@endsection
