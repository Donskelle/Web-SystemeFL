<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap 3.3.2 -->
        <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="{{ asset('/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{{ asset('/css/ionicons.css') }}" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins-->
        <link href="{{ asset('/css/skins/_all-skins.css') }}" rel="stylesheet" type="text/css" />

        <!-- jQuery -->
        <script src="{{ asset('/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
        <script src="{{ asset('/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>


        <!-- ACE Editor -->
        <script src="{{ asset('/js/ace/ace.js') }}" type="text/javascript"></script>	
    </head>
    <body class="skin-red">
        <div class="wrapper">
            @yield('header')
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    @yield('searchNav')
                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu">
                        @yield('helpInfo')
                        @yield('privateNav') 
                        @yield('publicNav')       
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside> 
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">     
                <!-- Main content -->
                <section class="content">
                    @yield('content')
                </section><!-- /.content -->
            </div>
            <!-- Main Footer -->
            <footer class="main-footer">      
                <div class="pull-right hidden-xs">Jan Urbansky, Fabian Puszies, Mirko Dulfer, Peter Steensen</div>       
                <strong>WebSysteme SS2015 DokuMummy</strong>
            </footer>
        </div>
    </body>

    <script type="text/javascript">
        var my_skins = ["skin-blue", "skin-black", "skin-red", "skin-yellow", "skin-purple", "skin-green"];
        function change_skin(cls) {
            $.each(my_skins, function (i) {
                $("body").removeClass(my_skins[i]);
            });
            $("body").addClass(cls);
            return false;
        }
        function setup() {
        @if (Auth::check())
            var tmp = "{{\Auth::user()->browser_layout}}";
            @else
            var tmp = " ";
            @endif
            if (tmp && $.inArray(tmp, my_skins))
                change_skin(tmp);
        }
        setup();
    </script>
</html>