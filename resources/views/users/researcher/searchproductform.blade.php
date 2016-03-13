@extends('users.researcher.base')

@section('title')
    Search Products
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Researcher
      <small>Search Products</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="box box-default">
        <div class="box-header">
          <p>Fill whatever you know about the product and press search button.</p>
        </div>
        <div class="box-body">
          {!! Form::model('', array('route' => array('productsjson'),'class'=>'form','id'=>'searchajax','data-parsley-validate')) !!}
          <div class="form-group col-md-3">
            <label>Product Name</label>
            {!! Form::text('search_p_name', null, array(
              'class' => 'form-control',
              'id' => 'search_p_name',
              'placeholder' => 'Search by product name',
              'data-parsley-required-message' => '',
              'data-parsley-trigger' => 'change focusout')) !!}
          </div>
          <div class="form-group col-md-3">
            <label>Vendor Name</label>
            <div class="input-group">
            {!! Form::text('search_p_vendor', null, array(
              'class' => 'form-control',
              'id' => 'search_email',
              'placeholder' => 'Search by vendor',
              'data-parsley-required-message' => '',
              'data-parsley-trigger' => 'change focusout')) !!}
                <span class="input-group-btn">
                  <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>

    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed responsive table-bordered" width="100%" id="users">
    	<thead>
    		<tr>
    			<th>Product Name</th>
          <th>Vendor Name</th>
          <th>Created At</th>
          <th>Actions</th>
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
$('#searchajax').on('submit', function(e) {
    loading  = true;
    e.preventDefault();
    var form = $(this);
    form.parsley().validate();
    if (form.parsley().isValid()){
      $.ajax({
          type: "POST",
          url: '/search/products',
          headers: {
            'X-CSRF-TOKEN': $('[name="_token"]').val()
          },
          data: $(this).serialize(),
          success: function(response) {
            $('#users').DataTable( {
              data: response.data,
              "aaSorting": [[ 2, "desc" ]],
              "bDestroy": true,
              "pageLength": 25,
              columns: [
                        {data: 'p_name', name: 'products.p_name'},
                        {data: 'p_author_name', name: 'products.p_author_name'},
                        {data: 'created_at', name: 'products.created_at'},
                        {data: 'p_actions', name: 'p_actions'},
                    ]
            });
          }
      });
    }
});
</script>
<style>
th {
  width:100px;
}
</style>
@stop
