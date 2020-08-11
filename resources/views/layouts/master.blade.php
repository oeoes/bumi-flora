<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Dashboard | Basik - Bootstrap 4 Web Application</title>
        <meta name="description" content="Responsive, Bootstrap, BS4" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- style -->
        <!-- build:css ../assets/css/site.min.css -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css" />
        <!-- endbuild -->
        @yield('custom-css')
    </head>
    <body class="layout-row">
        <!-- ############ Aside START-->
        <div id="aside" class="page-sidenav no-shrink bg-light nav-dropdown fade" aria-hidden="true">
            <div class="sidenav h-100 bg-light modal-dialog">
                <!-- sidenav top -->
                <div class="navbar">
                    <!-- brand -->
                    <a href="index.html" class="navbar-brand ">
                        <svg width="32" height="32" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <g class="loading-spin" style="transform-origin: 256px 256px">
                                <path d="M200.043 106.067c-40.631 15.171-73.434 46.382-90.717 85.933H256l-55.957-85.933zM412.797 288A160.723 160.723 0 0 0 416 256c0-36.624-12.314-70.367-33.016-97.334L311 288h101.797zM359.973 134.395C332.007 110.461 295.694 96 256 96c-7.966 0-15.794.591-23.448 1.715L310.852 224l49.121-89.605zM99.204 224A160.65 160.65 0 0 0 96 256c0 36.639 12.324 70.394 33.041 97.366L201 224H99.204zM311.959 405.932c40.631-15.171 73.433-46.382 90.715-85.932H256l55.959 85.932zM152.046 377.621C180.009 401.545 216.314 416 256 416c7.969 0 15.799-.592 23.456-1.716L201.164 288l-49.118 89.621z"></path>
                            </g>
                        </svg>
                        <!-- <img src="../assets/img/logo.png" alt="..."> -->
                        <span class="hidden-folded d-inline l-s-n-1x ">Basik</span>
                    </a>
                    <!-- / brand -->
                </div>
                <!-- Flex nav content -->
                
                @include('layouts.sidebar')

                <!-- sidenav bottom -->
                
            </div>
        </div>
        <!-- ############ Aside END-->
        <div id="main" class="layout-column flex">
            <!-- ############ Header START-->
            
            @include('layouts.topbar')

            <!-- ############ Footer END-->
            <!-- ############ Content START-->
            <div id="content" class="flex ">
                <!-- ############ Main START-->
                <div>
                    <div class="page-hero page-container " id="page-hero">
                        <div class="padding d-flex">
                            <div class="page-title">
                                <h2 class="text-md text-highlight">@yield('page-title')</h2>
                                <small class="text-muted">@yield('page-description')</small>
                            </div>
                            <div class="flex"></div>
                            @yield('btn-custom')
                        </div>
                    </div>
                    
                    @yield('content')

                </div>
                <!-- ############ Main END-->
            </div>
            <!-- ############ Content END-->
            <!-- ############ Footer START-->
            <div id="footer" class="page-footer hide">
                <div class="d-flex p-3">
                    <span class="text-sm text-muted flex">&copy; Copyright. flatfull.com</span>
                    <div class="text-sm text-muted">Version 1.0.3</div>
                </div>
            </div>
            <!-- ############ Footer END-->
        </div>

        <!-- jQuery -->
        <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('libs/popper.js/dist/umd/popper.min.js') }}"></script>
        <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
        <!-- ajax page -->
        <script src="{{ asset('libs/pjax/pjax.min.js') }}"></script>
        <script src="{{ asset('assets/js/ajax.js') }}"></script>
        <!-- lazyload plugin -->
        <script src="{{ asset('assets/js/lazyload.config.js') }}"></script>
        <script src="{{ asset('assets/js/lazyload.js') }}"></script>
        <script src="{{ asset('assets/js/plugin.js') }}"></script>
        <!-- scrollreveal -->
        <script src="{{ asset('libs/scrollreveal/dist/scrollreveal.min.js') }}"></script>
        <!-- feathericon -->
        <script src="{{ asset('libs/feather-icons/dist/feather.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/feathericon.js') }}"></script>
        <!-- theme -->
        <script src="{{ asset('assets/js/theme.js') }}"></script>
        <script src="{{ asset('assets/js/utils.js') }}"></script>
        <!-- endbuild -->
    </body>
</html>