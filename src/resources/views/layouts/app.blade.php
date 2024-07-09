<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>勤怠管理</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <div class="header__title">Atte</div>
      @yield('header')
    </div>
  </header>
  <main class="content">
    <div class="title">
      @yield('title')
    </div>
    @yield('content')
  </main>
  <footer class="footer">
    Atte,inc.
  </footer>
  @yield('scripts')
</body>

</html>