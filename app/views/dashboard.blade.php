<!DOCTYPE html>
<html>
<head lang="en">
    {{$tpl_head}}
    <style>
    .title-center{
        text-align: center;
        padding-top: 130px;
    }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      {{$tpl_header}}

      {{$tpl_sidebar}}

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> {{ lang('home') }}</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Main row -->
          <div class="row">
              <h1 class="title-center">ELIPS PHP FRAMEWORK</h1>
              <div style="text-align: center">Page rendered in {{$execution_time}} seconds</div>
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
              <div class="pull-right hidden-xs">
                <b>Template by <a href="http://almsaeedstudio.com">Almsaeed Studio</a></b>
              </div>
              <strong>Copyright &copy; 2016 Joko Purnomo A</strong>
            </footer>


      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    {{$tpl_footer}}
  </body>
</html>