<!DOCTYPE html>
<html>
  <head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
      <title>@yield('title')</title>
      {!! Html::style('css/app.css') !!}
      {!! Html::style('css/dropzone.min.css') !!}
      {!! Html::style('adminlte/dist/css/AdminLTE.min.css') !!}
      {!! Html::style('adminlte/dist/css/skins/skin-black.min.css') !!}
      {!! Html::style('css/adminlte-custom.css') !!}
      {!! Html::style('css/font-awesome.min.css') !!}
      {!! Html::style('css/ionicons.min.css') !!}
      {!! Html::style('css/dataTables.bootstrap.min.css') !!}

      {!! Html::script('js/jquery.min.js') !!}
      {!! Html::script('js/jquery-ui.min.js') !!}
      {!! Html::script('js/jquery.form.min.js') !!}
      {!! Html::script('js/bootstrap.min.js') !!}
      {!! Html::script('js/dropzone.min.js') !!}
      {!! Html::script('adminlte/dist/js/app.min.js') !!}
      {!! Html::script('js/parsley.min.js') !!}
      {!! Html::script('js/jquery.dataTables.min.js') !!}
      {!! Html::script('js/dataTables.bootstrap.min.js') !!}


  </head>
  <body class="hold-transition skin-black sidebar-mini">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="/user/dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>OpenVID</b> - sys</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>OpenVID</b> - sys</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" id="navbar-black" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

            <!-- notification item -->
            <li class="dropdown notifications-menu">
              <a href="/user/notifications/view">
                <i class="fa fa-bell-o"></i>
                <?php $getcount = Auth::user()->countNotificationsNotRead(); ?>
                <span class="label label-danger">{{ ($getcount>0) ? "$getcount" : "" }}</span>
              </a>
            </li>

            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                @if(is_null($profile->profpic) OR empty($profile->profpic))
                <img class="user-image" alt="User Image" src="/images/defaults/avatar.jpg">
                @else
                <img class="user-image" alt="User Image" src="/user/uploads/{{ Auth::user()->username.'/'.$profile->profpic}}">
                @endif
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{{Auth::user()->username}}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  @if(is_null($profile->profpic) OR empty($profile->profpic))
                  <img class="img-circle" alt="User Image" src="/images/defaults/avatar.jpg">
                  @else
                  <img class="img-circle" alt="User Image" id="img-thumb" src="/user/uploads/{{ Auth::user()->username.'/'.$profile->profpic}}">
                  @endif

                  <p>
                    {{$profile->first_name}} {{$profile->last_name}}
                    <br>
                    {{Auth::user()->username}}
                    <small>Joined on {{ date('F d, Y', strtotime(Auth::user()->created_at)) }}</small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    {!! HTML::link('user/'.Auth::user()->username, 'View Profile',['class'=>'btn btn-success']) !!}
                  </div>
                  <div class="pull-right">
                    {!!Html::linkRoute('logout', 'Logout',null,['class'=>'btn btn-danger']) !!}
                  </div>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="min-height:70px;">
          <div class="info" style="left:0px">
            <br><p>{{$profile->first_name}} {{$profile->last_name}} @if(!is_null($profile->display_name)) <br> {{$profile->display_name}}@endif</p><br>
          </div>
        </div>

        <!-- search form-->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
          </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">Welcome</li>
          <li><a href="/user/dashboard"><i class="fa fa-home"></i> <span>Home</span></a></li>
          <li class="treeview">
            <a href="#"><i class="fa fa-user"></i><span>Profile</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="/user/{{Auth::user()->username}}">View Profile</a></li>
              <li><a href="/user/profile/edit">Edit Information</a></li>
              <li><a href="/user/settings/password">Security Settings</a></li>
            </ul>
          </li>
          @if(Auth::user()->blocked == 0)
          <li class="treeview">
            <a href="#"><i class="fa fa-search"></i><span>Search</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="/search/products"><span>Search Products</span></a></li>
              <li><a href="/search/vulns"><span>Search Vulnerabilities</span></a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="/user/vuln/tracker"><i class="fa fa-bug"></i><span>Vulnerability Tracker</span></a>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-list"></i><span>Product Manager</span><i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="/product/new/create"><span>Register New Product</span></a></li>
            </ul>
          </li>
          @endif
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>
    @yield('content')
    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <div class="pull-right hidden-xs">
        <a href="/about">About OpenVID-sys</a>
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; 2016 <a href="https://bhavyanshu.me">Bhavyanshu Parasher</a></strong>
    </footer>
</div>
<script type="text/javascript">
$(function () {
    setNavigation();
});

function setNavigation() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);
    $(".sidebar-menu a").each(function () {
        var href = $(this).attr('href');
        var tempath = path.substring(0, href.length);
        if (tempath === href) {
        //if(path.substring(0, href.length).toLowerCase().indexOf(href) >= 0) {
          if($(this).closest('.treeview').length) {
            $(this).closest('.treeview').addClass('active');
            $(this).closest('.treeview li').addClass('active');
          }
          else {
            $(this).closest('li').addClass('active');
          }
        }
    });
}
</script>
</body>
</html>
