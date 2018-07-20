@extends('adminlte::page')

@section('title', 'Event List')

@section('content_header')
    <h1>Event List</h1>
@stop

@section('content')

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">Events Table</h3>
    <div class="box-tools pull-right">
      <!-- Buttons, labels, and many other things can be placed here! -->
    </div>
    <!-- /.box-tools -->
  </div>
  <!-- /.box-header -->
  <div class="box-body">
	<table id="event_list_table" class="display" width="100%">
		<thead>
			<th>Id</th>
			<th>Title</th>
			<th>Schedule Date</th>
			<th>Posted By</th>
			<th>Comments</th>
			<th>Action</th>
		</thead>
	</table>
  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    The footer of the box
  </div>
  <!-- box-footer -->
</div>

@stop

@section('js')
    <script> console.log('Hi!'); </script>

	<script type="text/javascript"> 
	$(document).ready(function() {
	    $('#event_list_table').DataTable( {
	        "processing": true,
	        "serverSide": true,
	        "ajax": {
	        	"url": "{{ url('/event/list') }}"
	        },
			"columns": [
	            {data: 'id'},
	            {data: 'title'},
	            {data: 'schedule_date'},
	            {data: 'posted_by_name'},
	            {data: 'comments_count'},
	            {data: 'id'}
			],
	        "columnDefs": [ 
	            {
	                // The `data` parameter refers to the data for the cell (defined by the
	                // `data` option, which defaults to the column being worked with, in
	                // this case `data: 0`.
	                "render": function ( data, type, row ) {
	                    var button_code;
	                    button_code = '<div class="btn-group" role="group">';
	                    button_code += '<button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Aksi <span class="caret"></span></button>';
	                    button_code += '<ul class="dropdown-menu dropdown-menu-right">';
	                    button_code += '    <li><a href="<?php echo url('/admin/event/detail'); ?>">Detail_' + data + '</a></li>';
	                    // button_code += '    <li><a href="<?php echo url('/admin/event/update'); ?>">Update_' + data + '</a></li>';
	                    // button_code += '    <li><a href="<?php echo url('/admin/event/delete'); ?>">Delete_' + data + '</a></li>';
	                    button_code += '   </ul>';
	                    button_code += '</div>';
	                    return button_code;
	                },
	                "className": "text-center",
	                "targets": 5
	            }
	        ]
	    });

	} );	
	</script>
@stop
