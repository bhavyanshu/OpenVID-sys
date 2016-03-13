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
    <body class="layout-top-nav skin-black">
    <div class="wrapper">
      <header class="main-header">
      <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="/" class="navbar-brand"><b>VID</b> - sys</a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/about">About</a></li>
          </ul>
          <ul class="nav navbar-nav">
            <li><a href="/public">Public Access</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
      </nav>
      </header>

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
