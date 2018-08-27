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
                @if($user_data->original_image_url == "")
                <img class="img-circle" src="{{asset('img/avatar.png')}}" alt="User Image">
                @else
                <img class="img-circle" src="{{asset($user_data->original_image_url)}}" alt="User Image">
                @endif
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
              <h3 class="box-title">Events Created By This User</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table class="table table-hover" id="user-event-table">
                <thead>
                  <tr>
                    <th>CrID#</th>
                    <th>Title</th>
                    <th>Event Time</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($event_data as $event)
                  <tr>
                    <td>{{$event->id}}</td>
                    <td><a href="<?php echo url('/admin/event/detail') ?>?post_id={{$event->id}}">{{$event->title}}</a></td>
                    <td>{{$event->schedule_date}}</td>
                    @if ($event->is_completed == 0)
                        <td class="text-center"><span class="label label-primary">Waiting</span></td>
                    @else
                        <td class="text-center"><span class="label label-success">Completed</span></td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

@stop

@section('js')
    <script> 
      // console.log('Hi!'); 
      // var _backendData = JSON.parse('{!! json_encode($event_data) !!}');
      // console.log(_backendData);
      $(document).ready(function() {
          $('#user-event-table').DataTable();
      } );
	</script>
    
@stop
