<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="robots" content="index, follow">
  <meta name="format-detection" content="telephone=no">
  <title>@yield('title') | Simple Ticket</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" href="/assets/images/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/libs/flaticon/css/all/all.css">
  <link rel="stylesheet" href="/assets/libs/lucide/lucide.css">
  <link rel="stylesheet" href="/assets/libs/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="/assets/libs/simplebar/simplebar.css">
  <link rel="stylesheet" href="/assets/libs/node-waves/waves.css">
  <link rel="stylesheet" href="/assets/libs/bootstrap-select/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="/assets/css/styles.css">
  @stack('styles')
</head>
<body>
  @yield('content')

  {{-- JS --}}
  <script src="/assets/libs/global/global.min.js"></script>
  <script src="/assets/js/appSettings.js"></script>
  <script src="/assets/js/main.js"></script>
  <script src="/assets/js/jquery-3.7.1.min.js"></script>
  <script src="/assets/js/sweetalert2.js"></script>
  @stack('scripts')
</body>
</html>