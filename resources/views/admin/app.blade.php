<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close" >

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
    <link rel="icon" href="/assets/images/icon.png" type="image/x-icon">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Main Theme Js -->
    <script src="/assets/js/main.js"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >

    <!-- Style Css -->
    <link href="/assets/css/styles.min.css" rel="stylesheet" >
    
    <!-- Icons Css -->
    <link href="/assets/css/icons.css" rel="stylesheet" >
    

    

    
    
    <link href="/assets/css/custom.css" rel="stylesheet" >
    <link href="/assets/css/constants.css" rel="stylesheet" >




        <!-- Scripts -->
        @routes
        @vite(['resources/js/app2.js', "resources/js/Pages/Admin/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia


  
        <script src="/assets/libs/@popperjs/core/umd/popper.min.js"></script>

        {{-- <!-- Bootstrap JS -->
        <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    
        <!-- Node Waves JS-->
        <script src="/assets/libs/node-waves/waves.min.js"></script>
    
        <!-- Sticky JS -->
        <script src="/assets/js/sticky.js"></script>
    
       
    
        <!-- Color Picker JS -->
        <script src="/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>
    
        <!-- Custom JS -->
        <script src="/assets/js/custom.js"></script> --}}
        
        <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
        <script src="/assets/js/show-password.js"></script>
        

    </body>
</html>
