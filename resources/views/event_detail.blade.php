@extends('adminlte::page')

@section('title', 'Event Detail')

@section('content_header')
    <h1>JCI Meeting Room</h1>
@stop

@section('content')

        <!-- /.col -->
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <img class="img-circle" src="{{asset('img/avatar.png')}}" alt="User Image">
                <span class="username"><a href="#">Jonathan Burke Jr.</a></span>
                <span class="description">Shared publicly - 7:30 PM Today</span>
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
              <img class="img-responsive pad" src="{{asset('img/photo2.png')}}" alt="Photo">

              <p>Meeting Ready At 13:00 Sat.?</p>
              <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
              <span class="pull-right text-muted">127 likes - 3 comments</span>
            </div>
            <!-- /.box-body -->
            <div class="box-footer box-comments">
              <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="{{asset('img/avatar5.png')}}" alt="User Image">

                <div class="comment-text">
                      <span class="username">
                        Maria Gonzales
                        <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
                  It is a long established fact that a reader will be distracted
                  by the readable content of a page when looking at its layout.
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.box-comment -->
              <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="{{asset('img/avatar2.png')}}" alt="User Image">

                <div class="comment-text">
                      <span class="username">
                        Nora Havisham
                        <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
                  The point of using Lorem Ipsum is that it has a more-or-less
                  normal distribution of letters, as opposed to using
                  'Content here, content here', making it look like readable English.
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.box-comment -->
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
              <form action="#" method="post">
                <img class="img-responsive img-circle img-sm" src="{{asset('img/avatar.png')}}" alt="Alt Text">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <input type="text" id="add-comment-box" class="form-control input-sm" placeholder="{Add Comment Function}">
                </div>
              </form>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

@stop

@section('js')
    <script> console.log('Hi!'); 
    	$( "#add-comment-box" ).prop( "disabled", true );
	</script>
    
@stop
