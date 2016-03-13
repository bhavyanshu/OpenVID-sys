@extends('users.org.base')

@section('title')
    Product Information
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Vendor
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
              <h3>Product Information</h3>
            </div>
            <div class="box-body">
            @if(is_null($product))
            {!! Form::model(null, array('route' => array('product_create'),'class'=>'form','id'=>'productfrm','data-parsley-validate')) !!}
            @else
            {!! Form::model($product, array('route' => array('product_create'),'class'=>'form','id'=>'productfrm','data-parsley-validate')) !!}
            {!! Form::hidden('p_id', null) !!}
            @endif
              <div class="row">
                <div class="form-group col-xs-3">
                  <div class="form-group{{ $errors->has('p_name') ? ' has-error' : '' }}">
                  <br>{!! Form::label('p_name', 'Product Name*') !!}
                  {!! Form::text('p_name', null, array(
                    'class' => 'form-control',
                    'placeholder' => '','required',
                    'data-parsley-required-message' => 'This is required',
                    'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                    'data-parsley-pattern-message' => 'This can only contain aphabets, numbers & (&.-)',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[ a-zA-Z0-9&.-]*$/',
                    'data-parsley-minlength' => '3')) !!}
                  </div>
                </div>

                <div class="form-group col-xs-3">
                  <div class="form-group{{ $errors->has('p_author_name') ? ' has-error' : '' }}">
                  <br>{!! Form::label('p_author_name', 'Vendor/Author Name*') !!}
                  {!! Form::text('p_author_name', $profile->display_name, array(
                    'class' => 'form-control',
                    'placeholder' => '','required',
                    'data-parsley-required-message' => 'This is required',
                    'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                    'data-parsley-pattern-message' => 'This can only contain aphabets',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[a-zA-Z]*$/',
                    'data-parsley-minlength' => '3')) !!}
                  </div>
                </div>

                <div class="form-group col-xs-3">
                  <div class="form-group{{ $errors->has('p_author_email') ? ' has-error' : '' }}">
                  <br>{!! Form::label('p_author_email', 'Vendor/Author Email*') !!}
                  {!! Form::text('p_author_email', Auth::user()->email, array(
                    'class' => 'form-control',
                    'placeholder' => '','required',
                    'data-parsley-required-message' => 'This is required',
                    'data-parsley-trigger' => 'change focusout',
                    'type'=>'email',
                    'data-parsley-type'=>'email')) !!}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('p_url') ? ' has-error' : '' }}">
                    <br>{!! Form::label('p_url', 'Active Product URL*') !!}
                    {!! Form::text('p_url', null, array(
                      'class' => 'form-control',
                      'placeholder' => 'http://github.com/you/are/awesome','required',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-pattern-message' => 'This can only contain aphabets,numbers & (.:?-#&%) character',
                      'data-parsley-trigger' => 'change',
                      'data-parsley-pattern' => '/^[a-zA-Z0-9\:\?\-\.\#\%\&\/]*$/',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>

                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('p_type') ? ' has-error' : '' }}">
                    <br>{!! Form::label('p_type', 'Product Type') !!}
                    {{ Form::select('p_type', ['App'=>'Application', 'OS'=>'OS','Hardware'=>'Hardware', 'Other'=>'Other'],null, array(
                      'class'=>'form-control','required')) }}
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('p_description') ? ' has-error' : '' }}">
                    <br>{!! Form::label('p_description', 'Product Description*') !!}
                    {!! Form::textarea('p_description', null, array(
                      'class' => 'form-control', 'required',
                      'placeholder' => 'Write something about the product',
                      'size' => '5x5',
                      'data-parsley-trigger'=>'keyup',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-maxlength-message' => 'This cannot be greater than 1000 characters',
                      'data-parsley-pattern-message' => 'This can only contain aphabets, numbers & (.-) character',
                      'data-parsley-pattern' => '/^[ a-zA-Z0-9\-\(\)\:\?\-\.\#\%\&\/]*$/',
                      'data-parsley-maxlength' => '1000',
                      'data-parsley-validation-threshold'=>'10',
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
