<!DOCTYPE html>
<html lang="zxx"dir="{{ $currentLanguageInfo->direction == 1 ? 'rtl' : '' }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="KreativDev">
  <meta name="keywords" content="@yield('metaKeywords')">
  <meta name="description" content="@yield('metaDescription')">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta property="og:title" content="@yield('ogTitle')">
  {{-- title --}}
  <title>@yield('pageHeading') {{ '| ' . $websiteInfo->website_title }}</title>
  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
  <link rel="apple-touch-icon" href="{{ asset('assets/img/' . $websiteInfo->favicon) }}">
 <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="{{ asset('stayzio/js/auth_redesign.js') }}"></script>
<link rel="stylesheet" href="{{ asset('stayzio/css/auth_redesign.css') }}">

</head>
<body>
  
   @yield('content')
  

  @include('frontend.partials.scripts')
</body>

</html>
