@extends('adminlte::page')

@section('title', 'Create Event')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1>Create Event</h1>
@stop

@section('content')
      
        <!-- /.col -->
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <strong>Event Data</strong>
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->

            <form method="post" id="create_event-form" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="form-group">
                    <label for="client_name">Event Title</label>
                    <input type="text" class="form-control" name="title" placeholder="Type your event title here">
                  </div>
                  <div class="form-group">
                    <label for="client_address">Event Content</label>

                      <textarea class="textarea" name="content" placeholder="Place some content here"
                                style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>

                  </div>
                  <div class="form-group">
                    <label for="client_name">Schedule</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="schedule_date" name="schedule_date">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="client_address">Image</label>
                    <input type="file" class="form-control" name="post_image">
                  </div> 
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-success btn-flat btn-sm" id="btn-submit">Submit</button>
              </div>
            </form>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

@stop

@section('js')
<!-- Bootstrap WYSIHTML5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.css" />

<!-- datepicker -->
<link href="{!! asset('public/css/bootstrap-datepicker.css') !!}" media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{!! asset('public/js/bootstrap-datepicker.js') !!}"></script>

<script>
  $(document).ready(function() {

    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5();

    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    })
  });

  $('#create_event-form').on('submit',(function(e) {
      e.preventDefault();
      var form_data = new FormData(this);

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          url: "{{ url('api/post/add') }}", // point to server-side PHP script
          data: form_data,
          type: 'POST',
          contentType: false, // The content type used when sending data to the server.
          cache: false, // To unable request pages to be cached
          processData: false,
          success: function(data) {
            window.location.replace("{{ url('admin/event/list') }}");
          }
      });
  }));

  $("#btn-submit").on("click", function() {
      $("#create_event-form").submit();
  });
</script>
    
@stop
