<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- title -->
    <title>BEYOND</title>

    <!-- favicon -->
    <link href="{{ favicon() }}" type="image/png" rel="icon" />

    <!-- all css here -->
    <link rel="stylesheet" href="/home/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/home/css/swiper-bundle.min.css" />
    @if(locale()=="ar")
    <link rel="stylesheet" href="/home/rtl/css/style.css" />
    @else
    <link rel="stylesheet" href="/home/css/style.css" />
    @endif

    <link rel="stylesheet" href="/home/css/custom.css" />


    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    {!! general_settings()->where('key','google tage manager header')->first()->value??null !!}
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="" >
        {!! general_settings()->where('key','google tage manager body')->first()->value??null !!}

        @inertia

        <script src="/home/js/bootstrap.min.js"></script>
        <script src="/home/js/swiper-bundle.min.js"></script>
        <script src="/home/js/hero-slider.js"></script>
        <script src="/home/js/product-slider.js"></script>
        <script src="/home/js/main.js"></script>




    </body>
</html>
