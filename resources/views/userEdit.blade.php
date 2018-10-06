@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <h1>Edit User</h1>
@stop

@section('content')
      <form method="post" id="edit_user-form" enctype="multipart/form-data">
        <!-- /.col -->
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <strong>User Data</strong>
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
              <input type="hidden" name="id" value="{{$user_data->id}}">
              <div class="box-body">
                <div class="col-md-4">
                  <div class="col-md-12">
                    <img class="img-thumbnail img-responsive" src="{{asset($user_data->original_image_url)}}" alt="User Image">
                  </div>
                  <div class="col-md-12 form-group">
                    <label for="client_address">Upload New Picture</label>
                    <input type="file" class="form-control" name="user_image">
                  </div> 
                </div>
                <div class="col-md-8">
                  <div class="form-group col-md-12">
                    <label for="client_name">Username</label>
                    <input type="text" class="form-control" name="username" placeholder="Type your username here" value="{{$user_data->username}}">
                  </div>
                  <div class="form-group col-md-12">
                    <label for="client_name">E-mail</label>
                    <input type="email" class="form-control" name="email" placeholder="Type your email here" value="{{$user_data->email}}">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="client_name">First Name</label>
                    <input type="text" class="form-control" name="first_name" placeholder="Type your username here" value="{{$user_data->first_name}}">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="client_name">Last Name</label>
                    <input type="text" class="form-control" name="last_name" placeholder="Type your username here" value="{{$user_data->last_name}}">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="client_name">Birthday</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control pull-right datepicker" id="birthday" name="birthday">
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="client_name">Telephone</label>
                    <input type="text" class="form-control" name="telephone" placeholder="Type your username here" value="{{$user_data->telephone}}">
                  </div>
                  <div class="form-group col-md-12">
                    <label for="client_name">Address</label>
                    <input type="text" class="form-control" name="address" placeholder="Type your username here" value="{{$user_data->address}}">
                  </div>
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-8">

                </div>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <div class="box box-widget">
            <div class="box-header with-border">
              <strong>Company Data</strong>
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
         
              <div class="box-body">
                <div class="col-md-12">
                  <div class="form-group col-md-6">
                    <label for="client_name">Company Name</label>
                    <input type="text" class="form-control" name="company" placeholder="Type your company here" value="{{$user_data->company}}">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="client_name">Website</label>
                    <input type="text" class="form-control" name="website" placeholder="Type your website here" value="{{$user_data->website}}">
                  </div>
                  <div class="form-group col-md-12">
                    <label for="client_name">Short Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Type your description here" value="{{$user_data->description}}">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-success btn-flat btn-sm pull-right" id="btn-submit">Submit</button>
              </div>
            <!-- /.box-footer -->
          </div>
        </div>
        <!-- /.col -->
      </form>

@stop

@section('js')
<!-- Bootstrap WYSIHTML5 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.all.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-wysiwyg/0.3.3/bootstrap3-wysihtml5.css" />

<!-- datepicker -->
<link href="{!! asset('public/css/bootstrap-datepicker.css') !!}" media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{!! asset('public/js/bootstrap-datepicker.js') !!}"></script>

<script>
  $('#edit_user-form').on('submit',(function(e) {
      e.preventDefault();
      var form_data = new FormData(this);

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
          url: "{{ url('api/user/edit') }}", // point to server-side PHP script
          data: form_data,
          type: 'POST',
          contentType: false, // The content type used when sending data to the server.
          cache: false, // To unable request pages to be cached
          processData: false,
          success: function(data) {
            window.location.replace("{{ url('admin/users') }}");
          }
      });
  }));

  $("#btn-submit").on("click", function() {
      $("#edit_user-form").submit();
  });

  $(document).ready(function() {

    //bootstrap WYSIHTML5 - text editor
    $('.textarea').wysihtml5();

    //Date picker
    var birth_datepicker = $('.datepicker').datepicker({
      autoclose: true
    });
    $('.datepicker').datepicker('update', "{{Date('m/d/Y',strtotime($user_data->birthday))}}");
  });
</script>
    
@stop
