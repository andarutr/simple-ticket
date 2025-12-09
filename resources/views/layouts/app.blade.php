<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="theme-color" content="#316AFF">
  <meta name="robots" content="index, follow">
  <title>@yield('title') Simple Ticket</title>
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
  <div class="page-layout">
    @include('partials.navbar')
    @include('partials.sidebar')

    <main class="app-wrapper">
      <div class="container">
        <div class="app-page-head">
          <h1 class="app-page-title">{{$menu ?? ''}}</h1>
        </div>
        <div class="row">
          @yield('content')
        </div>
      </div>
    </main>

    <footer class="footer-wrapper bg-body">
      <div class="container">
        <div class="row g-2">
          <div class="col-lg-6 col-md-7 text-center text-md-start">
            <p class="mb-0">© <span class="currentYear">2025</span> GXON. Proudly powered by <a href="javascript:void(0);">LayoutDrop</a>.</p>
          </div>
          <div class="col-lg-6 col-md-5">
            <ul class="d-flex list-inline mb-0 gap-3 flex-wrap justify-content-center justify-content-md-end">
              <li>
                <a class="text-body" href="index.html">Home</a>
              </li>
              <li>
                <a class="text-body" href="pages/faq.html">Faq's</a>
              </li>
              <li>
                <a class="text-body" href="pages/faq.html">Support</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
  </div>

  {{-- JS --}}
  <script src="/assets/libs/global/global.min.js"></script>
  <script src="/assets/js/appSettings.js"></script>
  <script src="/assets/js/main.js"></script>
  <script src="/assets/js/jquery-3.7.1.min.js"></script>
  <script src="/assets/js/sweetalert2.js"></script>
  @stack('scripts')
</body>
</html>