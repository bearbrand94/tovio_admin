@extends('adminlte::page')

@section('title', 'User Detail')

@section('content_header')
    <h1>User Information</h1>
@stop

@section('content')

        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->

            <div class="widget-user-header bg-aqua-active" style="background: url('{{asset('img/photo1.png')}}') center center;">
              <h3 class="widget-user-username">{{$user_data->first_name}} {{$user_data->last_name}}</h3>
              <h5 class="widget-user-desc pull-right">Email: {{$user_data->email}}</h5>
              <h5 class="widget-user-desc">{{$user_data->company}} Company</h5>
              <h5 class="widget-user-desc pull-right">Website: {{$user_data->website}}</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="{{asset('img/avatar.png')}}" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-3 border-right">
                  <div class="description-block">
                    <h5 class="description-header">{{$user_data->events_created}}</h5>
                    <span class="description-text">EVENTS CREATED</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <div class="col-sm-3 border-right">
                  <div class="description-block">
                    <h5 class="description-header">{{$user_data->events_completed}}</h5>
                    <span class="description-text">EVENTS COMPLETED</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                  <div class="description-block">
                    <h5 class="description-header">{{$user_data->follower_count}}</h5>
                    <span class="description-text">FOLLOWERS</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3">
                  <div class="description-block">
                    <h5 class="description-header">{{$user_data->following_count}}</h5>
                    <span class="description-text">FOLLOWING</span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box-widget -->
        </div>
        <!-- /.col -->

        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Events Created By Dis User</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tr>
                  <th>CrID#</th>
                  <th>Title</th>
                  <th>Event Time</th>
                  <th>Status</th>
                </tr>
                <tr>
                  <td>183</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Completed</span></td>
                </tr>
                <tr>
                  <td>219</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Completed</span></td>
                </tr>
                <tr>
                  <td>657</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-primary">Ongoing</span></td>
                </tr>
                <tr>
                  <td>175</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-danger">Cancelled</span></td>
                </tr>
                <tr>
                  <td>183</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Completed</span></td>
                </tr>
                <tr>
                  <td>219</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-success">Completed</span></td>
                </tr>
                <tr>
                  <td>657</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-primary">Ongoing</span></td>
                </tr>
                <tr>
                  <td>175</td>
                  <td>ipsum dolor sit amet salami venison</td>
                  <td>11-7-2014</td>
                  <td><span class="label label-danger">Cancelled</span></td>
                </tr>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

@stop

@section('js')
    <script> console.log('Hi!'); 
    $("tr").click(function() {
      window.location = "{{ url('/admin/event/detail') }}";
    });
      var _backendData = JSON.parse('{!! json_encode($event_data) !!}');
      console.log(_backendData);
	</script>
    
@stop
