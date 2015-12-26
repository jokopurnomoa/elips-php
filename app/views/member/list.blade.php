<!DOCTYPE html>
<html>
<head lang="en">
    @include('slices.head')
    <style>
    .title-center{
        text-align: center;
        padding-top: 130px;
    }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      @include('slices.header')
      @include('slices.sidebar')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <!-- Main row -->
          <div class="row">
              <div class="col-md-12">
                  <div class="box">
                                  <div class="box-header">
                                    <h3 class="box-title">Data Member</h3>

                                    <a href="{{base_url()}}member/add" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Member</a>
                                  </div><!-- /.box-header -->
                                  <div class="box-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          <th>No</th>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Phone</th>
                                          <th>Register Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @if($member_list != null)
                                        {{-- */$no=1;/* --}}
                                        @foreach($member_list as $row)
                                        <tr>
                                          <td>{{$no}}</td>
                                          <td>{{$row->name}}</td>
                                          <td>{{$row->email}}</td>
                                          <td>{{$row->phone}}</td>
                                          <td>{{$row->registerdate}}</td>
                                        </tr>
                                        {{-- */$no++;/* --}}
                                        @endforeach
                                        @endif
                                      </tbody>
                                    </table>
                                  </div><!-- /.box-body -->
                                </div><!-- /.box -->
              </div>
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

    @include('slices.footer')

    <script>
          $(function () {
            $('#example1').DataTable({
              "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ]
            });
          });
        </script>
  </body>
</html>