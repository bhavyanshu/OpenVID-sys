@extends('users.researcher.base')

@section('title')
    Search Vulneribilities
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Researcher
      <small>Search Vulneribilities</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="box box-default">
        <div class="box-header">
        </div>
        <div class="box-body">
          <div class="row">
            <div class="form-group col-md-6">
              <div class="form-group{{ $errors->has('filter') ? ' has-error' : '' }} col-md-6">
              {!! Form::label('filter', 'Filter') !!}
              {{ Form::select('filter', ['ac'=>'Active Vulnerabilities', 'ht'=>'High Threat Vulnerabilities', 'all'=>'All Vulnerabilities'],null, array(
                'class'=>'form-control','id'=>'filter')) }}
              </div>
              <div class="col-md-6">
                <span id="load_comments_ajax">
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed responsive table-bordered" width="100%" id="vulns">
    	<thead>
    		<tr>
          <th>OpenVID-sys ID</th>
    			<th>Product Name</th>
          <th>Vendor Name</th>
          <th>Reported At</th>
    		</tr>
    	</thead>
    	<tbody>
    		<tr>
    			<td></td>
          <td></td>
    			<td></td>
          <td></td>
    		</tr>
    	</tbody>
    </table>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
function loadView(f) {
  $('#vulns').DataTable( {
    processing: true,
    serverSide: true,
    destroy: true,
    "ajax": "/user/vulns/"+f,
    "aaSorting": [[ 2, "desc" ]],
    "pageLength": 25,
    columns: [
      {data: 'vul_unique_id', name: 'vulnerabilities.vul_unique_id'},
      {data: 'p_name', name: 'products.p_name'},
      {data: 'p_author_name', name: 'products.p_author_name'},
      {data: 'created_at', name: 'products.created_at'},
          ]
  });
}
$(function(){
    var filter = $('#filter').val();
    $('#load_comments_ajax').html('<p class="fa fa-cog fa-spin fa-5x"></p>');
    setTimeout(function() {
      loadView(filter);
      $('#load_comments_ajax').html('');
    }, 1000);
});
$('select').on('change', function() {
  var filter = $('#filter').val();
  $('#load_comments_ajax').html('<p class="fa fa-cog fa-spin fa-5x"></p>');
  setTimeout(function() {
    loadView(filter);
    $('#load_comments_ajax').html('');
  }, 1000);
});
</script>
<style>
th {
  width:100px;
}
</style>
@stop
