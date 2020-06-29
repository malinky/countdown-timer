<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <title>00:00:00 | {{ config('app.name') }}</title>
    <meta name="description" content="{{ config('app.name') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="{{ config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:locale" content="en_GB">

    <link rel="apple-touch-icon" sizes="192x192" href="{{ url('images/pwa-icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="512x512" href="{{ url('images/pwa-icon-512x512.png') }}">
    <link rel="shortcut icon" href="{{ url('favicon.png') }}">
    <link rel="manifest" href="{{ url('site.webmanifest') }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <script>
      window.countdownTimer = {
        appName: '{{ config('app.name') }}',
      }
    </script>
  </head>
  <body>
    <div id="app" class="text-grey-800">
      <count-down/>
    </div>
  </body>
  <script src="{{ mix('/js/app.js') }}"></script>
</html>
