@extends('adminlte::page')

@section('title', 'Event Detail')

@section('content_header')
    <h1>{{$event_data->title}}</h1>
@stop

@section('content')

        <!-- /.col -->
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">

                @if($event_data->user_image_url == "")
                <img class="img-circle" src="{{asset('img/avatar.png')}}" alt="User Image">
                @else
                <img class="img-circle" src="{{asset($event_data->user_image_url)}}" alt="User Image">
                @endif

                <span class="username"><a href="<?php echo url('/admin/user/detail') ?>?user_id={{$event_data->posted_by}}" id="posted_by">{{$event_data->posted_by_name}}</a></span>
                <span class="description" id="schedule_date">Created at {{date('d M Y, H:i', strtotime($event_data->created_at))}}</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Delete">
                  <i class="fa fa-trash-o"></i></button>
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Edit">
                  <i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if($event_data->original_image_url == "")
                <img class="img-responsive pad" src="{{asset('img/photo2.png')}}" alt="Photo">
              @else
                <img class="img-responsive pad" src="{{asset($event_data->original_image_url)}}" alt="Photo">
              @endif

              <p>Schedule Date: <b>{{date('d M Y, H:i', strtotime($event_data->schedule_date))}}</b></p>
              <p>{!! $event_data->content !!}</p>
<!--               <button type="button" class="btn btn-default btn-xs" id="btn-like"><i class="fa fa-thumbs-o-up"></i> Like</button> -->
              <span class="pull-right text-muted">{{$event_data->post_like_count}} likes - {{$event_data->comment_count}} comments</span>
            </div>
            <!-- /.box-body -->
            <div class="box-footer box-comments">
              @foreach($comment_data as $comment)
              <div class="box-comment">
                <!-- User image -->

                @if($comment->user_image_url == "")
                <img class="img-circle img-sm" src="{{asset('img/avatar.png')}}" alt="User Image">
                @else
                <img class="img-circle img-sm" src="{{asset($comment->user_image_url)}}" alt="User Image">
                @endif

                <div class="comment-text" style="margin-bottom: 10px;">
                      <span class="username">
                        <a href="<?php echo url('/admin/user/detail') ?>?user_id={{$comment->commented_by}}">{{$comment->commented_by_name}}</a>
                        <span class="text-muted pull-right">{{date('d M Y, H:i', strtotime($comment->created_at))}}</span>
                      </span><!-- /.username -->
                      {{$comment->content}}
                      <a href="#" class="text-muted text-light-blue pull-right"><u>Reply</u></a>
                      <a href="#" class="text-muted text-light-blue pull-right" style="margin-right: 5px;"><u>Edit</u></a>
                </div>
                @foreach($comment->child as $comment_child)
                  <div class="box-comment" style="margin-left: 50px;">
                    @if($comment_child->user_image_url == "")
                    <img class="img-circle img-sm" src="{{asset('img/avatar.png')}}" alt="User Image">
                    @else
                    <img class="img-circle img-sm" src="{{asset($comment_child->user_image_url)}}" alt="User Image">
                    @endif
                    <div class="comment-text">
                          <span class="username">
                            <a href="<?php echo url('/admin/user/detail') ?>?user_id={{$comment_child->commented_by}}">{{$comment_child->commented_by_name}}</a>
                            <span class="text-muted pull-right">{{date('d M Y, H:i', strtotime($comment_child->created_at))}}</span>
                          </span><!-- /.username -->
                          {{$comment_child->content}}
                    </div>
                  </div>
                @endforeach
              </div>
              <!-- /.box-comment -->
              @endforeach
              <img class="img-responsive img-circle img-sm" src="{{asset($event_data->user_image_url)}}" alt="Alt Text">
                <div class="img-push">
                  <input type="text" id="add-comment-box" class="form-control input-sm" placeholder="Type new comment here">
                </div>
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
  
              <button type="button" class="btn btn-danger btn-flat btn-sm pull-right" id="btn-back">Back</button>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

@stop

@section('js')
  <script>
      // console.log('Hi!'); 
    	// $( "#add-comment-box" ).prop( "disabled", true );
      // var _backendData = JSON.parse('{!! json_encode($comment_data) !!}');
      // console.log(_backendData);
    $("#btn-back").on("click", function() {
      window.location.replace("{{ url('admin/event/list') }}");
    });
    // $("#btn-like").on("click", function() {
    //   alert("Do Like");
    // });
	</script>
    
@stop
