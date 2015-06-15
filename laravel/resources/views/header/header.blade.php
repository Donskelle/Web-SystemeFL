@section('header')
<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="/home" class="logo"><b>Doku</b>Mummy</a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">             
                <!-- User Account Menu -->
                @if(\Auth::check())
                <li class="dropdown user user-menu">			  
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ asset('/img/'.\Auth::user()->imagePath) }}" class="user-image" alt="User Image"/>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{\Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ asset('/img/'.\Auth::user()->imagePath) }}" class="img-circle" alt="User Image" />
                            <p>{{\Auth::user()->name}}<small>{{\Auth::user()->extra}}</small></p>
                        </li>                 
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="/settings/profile/{{\Auth::user()->username}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="/auth/logout" class="btn btn-default btn-flat">Auslogen</a>
                            </div>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-gears"></i>                 
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Einstellungen</li>
                        @if(\Auth::check())
                        @if(\Auth::user()->permission <2)
                        <li><a href="/settings/admin/"><i class="fa fa-gears"></i> Admin Einstellungen</a></li>
                        @endif
                        @endif
                        <li><a href="/news"><i class="fa fa-desktop"></i> News Anzeige</a></li>
                        <li><a href="/help"><i class="fa fa-info-circle"></i>  Hilfe</a></li>
                    </ul>
                </li>            
            </ul>
        </div>
    </nav>
</header>
@endsection