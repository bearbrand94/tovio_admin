@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <h4>This page is under development.</h4>
<!-- 	<table id="event_list_table" class="display" width="100%">
		<thead>
			<th>Id</th>
			<th>Title</th>
			<th>Schedule Date</th>
			<th>Posted By</th>
			<th>Comments</th>
		</thead>
	</table> -->
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
	            {data: 'comments_count'}
			]
	    });

	} );	
	</script>
@stop
