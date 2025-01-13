<head>
    <!-- Character encoding for UTF-8 -->
    <meta charset="UTF-8" />
    
    <!-- Compatibility with Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    
    <!-- Responsive viewport settings -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Title of the page -->
    <title>Al Quran Academy</title>
    
    <!-- CSRF token for secure forms -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- Favicon (replace href with the path to your favicon.ico) -->
    <link rel="icon" href="" type="{{ asset('dashboard/assets/img/favicon.png') }}" sizes="16x16">
    
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

     <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('dashboard/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>

    <!-- CSS files -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/dataTables.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/dataTables.responsive.min.css') }}" />

</head>
