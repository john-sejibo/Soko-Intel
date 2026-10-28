<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Soko Intel') - {{ __('soko.tagline') }}</title>
  <script>
    (function () {
      var t = null;
      try { t = localStorage.getItem('soko-theme'); } catch (e) {}
      if (!t) t = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      document.documentElement.setAttribute('data-bs-theme', t);
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('css/soko.css') }}" rel="stylesheet">
  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg soko-nav mb-4">
  <div class="container">
    <a class="brand navbar-brand" href="{{ route('home') }}">Soko Intel</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="{{ __('soko.nav.menu') }}">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto ms-lg-4">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('soko.nav.search') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">{{ __('soko.nav.dashboard') }}</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}">{{ __('soko.nav.map') }}</a></li>
        @auth
          <li class="nav-item"><a class="nav-link {{ request()->routeIs('report.*') ? 'active' : '' }}" href="{{ route('report.create') }}">{{ __('soko.nav.report') }}</a></li>
          @if(auth()->user()->role === 'seller')<li class="nav-item"><a class="nav-link {{ request()->routeIs('seller.*') ? 'active' : '' }}" href="{{ route('seller.dashboard') }}">{{ __('soko.nav.my_business') }}</a></li>@endif
          @if(auth()->user()->role === 'admin')<li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.overview') }}">{{ __('soko.nav.admin') }}</a></li>@endif
        @endauth
      </ul>
      <div class="d-flex gap-2 align-items-center flex-wrap">
        <div class="btn-group btn-group-sm" role="group" aria-label="{{ __('soko.nav.language') }}">
          <a class="btn btn-secondary {{ app()->getLocale() === 'sw' ? 'active' : '' }}" href="{{ route('lang', 'sw') }}">SW</a>
          <a class="btn btn-secondary {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{ route('lang', 'en') }}">EN</a>
        </div>
        <button type="button" id="themeToggle" class="btn btn-secondary btn-sm">{{ __('soko.nav.theme') }}</button>
        @auth
          <form method="POST" action="{{ route('logout') }}" class="m-0">@csrf<button class="btn btn-outline-light btn-sm">{{ __('soko.nav.logout') }}</button></form>
        @else
          <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">{{ __('soko.nav.login') }}</a>
          <a class="btn btn-primary btn-sm" href="{{ route('register') }}">{{ __('soko.nav.register') }}</a>
        @endauth
      </div>
    </div>
  </div>
</nav>
<main class="container pb-5">
  @if(session('ok'))<div class="alert alert-success">{{ session('ok') }}</div>@endif
  @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  @yield('content')
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('themeToggle').addEventListener('click', function () {
    var next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-bs-theme', next);
    try { localStorage.setItem('soko-theme', next); } catch (e) {}
  });
</script>
@stack('scripts')
</body>
</html>
