<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       


        <!--<link href="{{ asset('/css/app.css') }}" rel="stylesheet">-->

        <!-- Bootstrap 3.3.2 -->
        <link href="{{ asset('/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Font Awesome Icons -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{ asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
              page. However, you can choose any other skin. Make sure you
              apply the skin class to the body tag so the changes take effect.
        -->
        <link href="{{ asset('/css/skins/_all-skins.css') }}" rel="stylesheet" type="text/css" />


        <script src="{{ asset('/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
        <script src="{{ asset('/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/js/app.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('/js/ace/ace.js') }}" type="text/javascript"></script>
		
		 <script src="{{ asset('/js/demo.js') }}" type="text/javascript"></script>
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
</html>


<!-- 
@if (Auth::guest())
                                                <li><a href="{{ url('/auth/login') }}">Login</a></li>
                                                <li><a href="{{ url('/auth/register') }}">Register</a></li>
                                        @else
                                                <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                                                        <ul class="dropdown-menu" role="menu">
                                                                <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                                                        </ul>
                                                </li>
                                        @endif -->
