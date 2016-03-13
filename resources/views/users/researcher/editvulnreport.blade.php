@extends('users.researcher.base')

@section('title')
    Vulnerability Information
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Researcher
      <small>OpenVID-sys | Vulnerability Information Disclosure System</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      @if(Session::has('message'))
      <br>
      <div class="alert alert-success errors">{{ Session::get('message') }}</div>
      @endif
        <div class="col-md-10 col-md-offset-1">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3>Vulnerability Information</h3>
            </div>
            <div class="box-body">
            @if(is_null($vul))
            <div class="row">
              <div class="col-md-8">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed responsive table-bordered" width="100%">
                	<thead>
                		<tr>
                			<th>Product Name</th>
                      <th>Vendor Name</th>
                		</tr>
                	</thead>
                  <tr>
                    <td>{{$product->p_name}}</td>
                    <td>{{$product->p_author_name}}</td>
                  </tr>
                </table>
                <p><strong>Product Description :</strong> {{$product->p_description}} - {{$product->p_url}}</p>
                <hr/>
              </div>
            </div>
            {!! Form::model(null, array('route' => array('vuln_update'),'class'=>'form','id'=>'vulfrm','data-parsley-validate')) !!}
            {!! Form::hidden('vul_prod_id', $product->p_id) !!}
            @elseif(is_null($product))
            Edit vul
            {!! Form::model($vul, array('route' => array('vuln_update'),'class'=>'form','id'=>'vulfrm','data-parsley-validate')) !!}
            {!! Form::hidden('vul_id', null) !!}
            {!! Form::hidden('vul_unique_id', null) !!}
            @endif
              <div class="row">
                <div class="col-md-12">
                  <p>Please provide information about the vulnerability and the person who has found the vulnerability by filling the form provided below.</p>
                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_author_name') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_author_name', 'Publisher Name*') !!}
                    {!! Form::text('vul_author_name', $profile->first_name." ".$profile->last_name, array(
                      'class' => 'form-control',
                      'placeholder' => '','required',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-pattern-message' => 'This can only contain aphabets',
                      'data-parsley-trigger' => 'change focusout',
                      'data-parsley-pattern' => '/^[ a-zA-Z.]*$/',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>

                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_author_email') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_author_email', 'Publisher Email*') !!}
                    {!! Form::text('vul_author_email', Auth::user()->email, array(
                      'class' => 'form-control',
                      'placeholder' => '','required',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-trigger' => 'change focusout',
                      'type'=>'email',
                      'data-parsley-type'=>'email')) !!}
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">

                  <div class="form-group col-md-6">
                    <div class="form-group{{ $errors->has('vul_type') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_type', 'Vulnerability Type*') !!}
                    {{ Form::select('vul_type', ['Bypass authentication/restriction','Cross Site Scripting','Denial of service','Execute arbitrary code','Gain Privileges','Directory Traversal','Http Response Splitting','Memory Corruption','Overflow (stack/heap/other)','CSRF','File Inclusion','SQL Injection','Unrestricted Critical Information Access'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_complexity') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_complexity', 'Vulnerability Complexity*') !!}
                    <p>Low (Easy to access) -> High (Difficult to access)</p>
                    {{ Form::select('vul_complexity', ['Low','Medium','High'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>

                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_auth') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_auth', 'Authentication*') !!}
                    <p>Does this require exploiter to be authenticated?</p>
                    {{ Form::select('vul_auth', ['Not Required','Required'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>

                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_confidentiality') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_confidentiality', 'Confidentiality*') !!}
                    <p>Information of database & files disclosed by Vulnerability</p>
                    {{ Form::select('vul_confidentiality', ['None','Partial','Complete'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group col-md-4">
                    <div class="form-group{{ $errors->has('vul_integrity') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_integrity', 'Integrity*') !!}
                    <p>Information that can be modified by exploiter</p>
                    {{ Form::select('vul_integrity', ['None','Partial','Complete'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>

                    <div class="form-group col-md-4">
                      <div class="form-group{{ $errors->has('vul_performance') ? ' has-error' : '' }}">
                      <br>{!! Form::label('vul_performance', 'Performance*') !!}
                      <p>No affect on performance or complete shutdown of resource?</p>
                      {{ Form::select('vul_performance', ['None','Partial','Complete'],null, array(
                        'class'=>'form-control','required')) }}
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <div class="form-group{{ $errors->has('vul_access') ? ' has-error' : '' }}">
                      <br>{!! Form::label('vul_access', 'Access Level*') !!}
                      <p>Can exploiter gain admin access in the system?</p>
                      {{ Form::select('vul_access', ['None','Admin Level','User Level','Other'],null, array(
                        'class'=>'form-control','required')) }}
                      </div>
                    </div>
                  </div>
                </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group col-md-12">
                    <div class="form-group{{ $errors->has('vul_description') ? ' has-error' : '' }}">
                    <br>{!! Form::label('vul_description', 'Description*') !!}
                    {!! Form::textarea('vul_description', null, array(
                      'class' => 'form-control', 'required',
                      'placeholder' => 'Give more details about the vulnerability, ex. testing environment etc',
                      'rows' => '5',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-trigger'=>'keyup',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-maxlength-message' => 'This cannot be greater than 1000 characters',
                      'data-parsley-maxlength' => '1000',
                      'data-parsley-validation-threshold'=>'10',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group col-md-12">
                    <div class="form-group{{ $errors->has('ref_url_1') ? ' has-error' : '' }}">
                    <br>{!! Form::label('ref_url_1', 'Add Some References') !!}
                    {!! Form::text('ref_url_1', null, array(
                      'class' => 'form-control',
                      'placeholder' => '1st reference',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-trigger' => 'change focusout',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <div class="form-group{{ $errors->has('ref_url_2') ? ' has-error' : '' }}">
                    {!! Form::text('ref_url_2', null, array(
                      'class' => 'form-control',
                      'placeholder' => '2nd reference',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-trigger' => 'change focusout',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>

                  <div class="form-group col-md-12">
                    <div class="form-group{{ $errors->has('ref_url_3') ? ' has-error' : '' }}">
                    {!! Form::text('ref_url_3', null, array(
                      'class' => 'form-control',
                      'placeholder' => '3rd reference',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-trigger' => 'change focusout',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>
                </div>
              </div>
              <div id="form1_alert" class="alert"></div>
              <br>
              <div class="text-center">
                {{Form::button('Save', array('type' => 'submit', 'class' => 'btn btn-primary has-spinner','data-loading-text' => "Saving...",'id' => 'loader1'))}}
              </div>
              {!! Form::close() !!}
              <br>
            </div>
          </div>
        </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@stop
