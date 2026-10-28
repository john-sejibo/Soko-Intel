@php
  $items = [
    ['admin.overview', 'overview', 'admin.overview'],
    ['admin.reports', 'reports', 'admin.reports'],
    ['admin.markets.index', 'markets', 'admin.markets.*'],
    ['admin.products.index', 'products', 'admin.products.*'],
    ['admin.categories.index', 'categories', 'admin.categories.*'],
    ['admin.shops', 'shops', 'admin.shops*'],
    ['admin.users', 'users', 'admin.users*'],
    ['admin.records', 'records', 'admin.records*'],
  ];
@endphp
<ul class="nav admin-tabs mb-4">
  @foreach($items as $i)
    <li class="nav-item"><a class="nav-link {{ request()->routeIs($i[2]) ? 'active' : '' }}" href="{{ route($i[0]) }}">{{ __('soko.admin.nav.'.$i[1]) }}</a></li>
  @endforeach
</ul>
