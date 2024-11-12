<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
	<meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    
    <!-- Favicon -->
    <link rel="icon" href="/assets/images/brand-logos/favicon.ico" type="image/x-icon">
    
    <!-- Choices JS -->
    <script src="/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Main Theme Js -->
    <script src="/assets/js/main.js"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <!-- Style Css -->
    <link href="/assets/css/styles.min.css" rel="stylesheet" >
    
    <!-- Icons Css -->
    <link href="/assets/css/icons.css" rel="stylesheet" >
    
    <!-- Node Waves Css -->
    <link href="/assets/libs/node-waves/waves.min.css" rel="stylesheet" > 
    
    <!-- Simplebar Css -->
    <link href="/assets/libs/simplebar/simplebar.min.css" rel="stylesheet" >
    
    <!-- Color Picker Css -->
    <link rel="stylesheet" href="/assets/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/assets/libs/@simonwep/pickr/themes/nano.min.css">
    
    <!-- Choices Css -->
    <link rel="stylesheet" href="/assets/libs/choices.js/public/assets/styles/choices.min.css">
    
    <link href="/assets/css/custom.css" rel="stylesheet" >

    <link rel="stylesheet" href="/assets/libs/jsvectormap/css/jsvectormap.min.css">

    <link rel="stylesheet" href="/assets/libs/swiper/swiper-bundle.min.css">

       
    </head>
    <body class="font-sans antialiased" >
        <div class="container">
        <div class="row justify-content-center mt-3">
        <div class="col-md-6">
        <div class="card card-body">

        <div class="simplebar-content" style="padding: 24px;">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                <div>
                    <p class="fs-20 fw-semibold mb-0"> {!! $subject !!} </p>
                </div>
                <div class="float-end">
                    <span class="me-2 fs-12 text-muted">{{now()->format('Y-m-d H:i')}}</span>
                </div>
            </div>
            <div class="main-mail-content mb-4">
                <p class="fs-14 fw-semibold mb-4"> {!! $title !!}</p>
                <p>  {!! $desc !!} </p>
                <p class="mb-0 mt-4">
                    <span class="d-block">Regards,</span>
                    <span class="d-block">Michael Jeremy</span>
                </p>
            </div>
           
            
        </div>

        </div>
        </div>
        </div>
        </div>
        
        <script src="/assets/libs/@popperjs/core/umd/popper.min.js"></script>
        <script src="/assets/js/show-password.js"></script>
    </body>
</html>




