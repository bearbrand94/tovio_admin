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
                <img class="img-circle" src="{{asset('storage/'.$event_data->user_image_url)}}" alt="User Image">
                @endif

                <span class="username"><a href="<?php echo url('/admin/user/detail') ?>?user_id={{$event_data->posted_by}}" id="posted_by">{{$event_data->posted_by_name}}</a></span>
                <span class="description" id="schedule_date">Created at {{date('d M Y, H:i', strtotime($event_data->created_at))}}</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
<!--                 <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                  <i class="fa fa-circle-o"></i></button> -->
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              @if($event_data->original_image_url == "")
                <img class="img-responsive pad" src="{{asset('img/photo2.png')}}" alt="Photo">
              @else
                <img class="img-responsive pad" src="{{asset('storage/'.$event_data->original_image_url)}}" alt="Photo">
              @endif

              <p>Schedule Date: <b>{{date('d M Y, H:i', strtotime($event_data->schedule_date))}}</b></p>
              <p>{{$event_data->content}}</p>
<!--               <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button> -->
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
                <img class="img-circle img-sm" src="{{asset('storage/'.$comment->user_image_url)}}" alt="User Image">
                @endif

                <div class="comment-text">
                      <span class="username">
                        <a href="<?php echo url('/admin/user/detail') ?>?user_id={{$comment->commented_by}}">{{$comment->commented_by_name}}</a>
                        <span class="text-muted pull-right">{{date('d M Y, H:i', strtotime($comment->created_at))}}</span>
                      </span><!-- /.username -->
                      qwerty{{$comment->content}}
                      {{$comment->child}}
                </div>
                @foreach($comment->child as $comment_child)
                  <div class="box-comment" style="margin-left: 50px;">
                    @if($comment_child->user_image_url == "")
                    <img class="img-circle img-sm" src="{{asset('img/avatar.png')}}" alt="User Image">
                    @else
                    <img class="img-circle img-sm" src="{{asset('storage/'.$comment_child->user_image_url)}}" alt="User Image">
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
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
<!--               <form action="#" method="post">
                <img class="img-responsive img-circle img-sm" src="{{asset('img/avatar.png')}}" alt="Alt Text">
                .img-push is used to add margin to elements next to floating images
                <div class="img-push">
                  <input type="text" id="add-comment-box" class="form-control input-sm" placeholder="{Add Comment Function}">
                </div>
              </form> -->
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
    	$( "#add-comment-box" ).prop( "disabled", true );
      // var _backendData = JSON.parse('{!! json_encode($comment_data) !!}');
      // console.log(_backendData);
	</script>
    
@stop
