@extends('users.researcher.base')

@section('title')
    Edit Profile
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Researcher
      <small>Edit Profile {{$profile->display_name}}</small>
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
              <h3>Profile Information</h3>
            </div>
            <div class="box-body">
              <div class="row" style="text-align:center;margin:0 auto;">
                <p>Click image to upload new one.</p>
                {!! Form::open(array('route' => 'userprofilepic_upload', 'method' => 'POST', 'id' => 'profpic-upload', 'class' => 'form', 'style' => 'text-align:center;' , 'files' => true)) !!}
                <div class="image-upload">
                  <div id="img_replace">
                <label for="image">
                @if(is_null($profile->profpic) OR empty($profile->profpic))
                <img id="img-thumb" class="user img-thumbnail img-clip" title="click image to upload image" src="/images/defaults/avatar.jpg">
                @else
                <img id="img-thumb" title="click image to upload image" class="user img-thumbnail" src="/user/uploads/{{ Auth::user()->username.'/'.$profile->profpic}}">
                @endif
                </label></div>
                <input type="file" name="image" id="image" />
                </div>
                <!--<button id="upload-submit" class="btn btn-default"><i ></i> Upload Picture</button>-->
                {!! Form::close() !!}
                <div id="validation-errors"></div>
              </div>

              {!! Form::model($profile, array('route' => array('userprofile_update'),'class'=>'form','id'=>'profileajax','data-parsley-validate')) !!}
              <div class="row">

                <div class="form-group col-xs-3">
                  <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                  <br>{!! Form::label('first_name', 'First Name*') !!}
                  {!! Form::text('first_name', null, array(
                    'class' => 'form-control',
                    'placeholder' => '','required',
                    'data-parsley-required-message' => 'This is required',
                    'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                    'data-parsley-pattern-message' => 'This can only contain aphabets and (-.) character',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[ a-zA-Z.-]*$/',
                    'data-parsley-minlength' => '3')) !!}
                  </div>
                </div>

                <div class="form-group col-xs-3">
                  <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                  <br>{!! Form::label('last_name', 'Last Name*') !!}
                  {!! Form::text('last_name', null, array(
                    'class' => 'form-control',
                    'placeholder' => '','required',
                    'data-parsley-required-message' => 'This is required',
                    'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                    'data-parsley-pattern-message' => 'This can only contain aphabets and (-.) character',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[ a-zA-Z.-]*$/',
                    'data-parsley-minlength' => '3')) !!}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('designation') ? ' has-error' : '' }}">
                    <br>{!! Form::label('designation', 'Designation*') !!}
                    {!! Form::text('designation', null, array(
                      'class' => 'form-control',
                      'placeholder' => 'ex. Executive Manager','required',
                      'data-parsley-required-message' => 'This is required',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-pattern-message' => 'This can only contain aphabets & (.) character',
                      'data-parsley-trigger' => 'change focusout',
                      'data-parsley-pattern' => '/^[ a-zA-Z.-]*$/',
                      'data-parsley-minlength' => '3',
                      'data-parsley-maxlength' => '200',
                      'data-parsley-validation-threshold'=>'10')) !!}
                    </div>
                  </div>

                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                    <br>{!! Form::label('gender', 'Gender') !!}
                    {{ Form::select('gender', ['Male', 'Female', 'Decline to state'],null, array(
                      'class'=>'form-control')) }}
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group col-xs-12">
                    <div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                    <br>{!! Form::label('bio', 'Bio') !!}
                    {!! Form::textarea('bio', null, array(
                      'class' => 'form-control',
                      'placeholder' => '',
                      'size' => '5x5',
                      'data-parsley-trigger'=>'keyup',
                      'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                      'data-parsley-maxlength-message' => 'This cannot be greater than 200 characters',
                      'data-parsley-pattern-message' => 'This can only contain aphabets & (.) character',
                      'data-parsley-pattern' => '/^[ a-zA-Z.-]*$/',
                      'data-parsley-maxlength' => '200',
                      'data-parsley-validation-threshold'=>'10',
                      'data-parsley-minlength' => '3')) !!}
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-xs-4">
                  <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                  <br>{!! Form::label('address', 'Address') !!}
                  {!! Form::text('address', null, array(
                    'class' => 'form-control',
                    'placeholder' => '',
                    'data-parsley-trigger'=>'keyup',
                    'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                    'data-parsley-maxlength-message' => 'This cannot be greater than 200 characters',
                    'data-parsley-pattern-message' => 'This can only contain aphabets & (.) character',
                    'data-parsley-pattern' => '/^[ a-zA-Z.-]*$/',
                    'data-parsley-maxlength' => '200',
                    'data-parsley-validation-threshold'=>'3',
                    'data-parsley-minlength' => '3')) !!}
                  </div>
                </div>
                <div class="form-group col-xs-4">
                  <div class="form-group{{ $errors->has('pincode') ? ' has-error' : '' }}">
                  <br>{!! Form::label('pincode', 'Pincode') !!}
                  {!! Form::text('pincode', null, array(
                    'class' => 'form-control',
                    'data-parsley-minlength-message' => 'This cannot be less than 5 characters',
                    'data-parsley-pattern-message' => 'This can only contain numbers',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[0-9]*$/',
                    'data-parsley-minlength' => '5')) !!}
                  </div>
                </div>
                <div class="form-group col-xs-4">
                  <div class="form-group{{ $errors->has('mobilenumber') ? ' has-error' : '' }}">
                  <br>{!! Form::label('mobilenumber', 'Contact Number') !!} (+91 not needed)
                  {!! Form::text('mobilenumber', null, array(
                    'class' => 'form-control',
                    'data-parsley-minlength-message' => 'This cannot be less than 10 characters',
                    'data-parsley-pattern-message' => 'This can only contain numbers',
                    'data-parsley-trigger' => 'change focusout',
                    'data-parsley-pattern' => '/^[0-9]*$/',
                    'data-parsley-minlength' => '10')) !!}
                  </div>
                </div>
              </div>
              <div id="form1_alert" class="alert"></div>
              <br>
              {{Form::button('Save', array('type' => 'submit', 'class' => 'btn btn-primary has-spinner','data-loading-text' => "Saving...",'id' => 'loader1'))}}
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
<script>
$('#profileajax').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    form.parsley().validate();
    if (form.parsley().isValid()){
      $('#loader1').addClass('disabled');
      $('#loader1').html('Saving... <i id="rotater" class="glyphicon"></i>');
      $('#rotater').addClass('glyphicon-refresh glyphicon-spin');
      $.ajax({
          type: "POST",
          url: '/user/profile/edit',
          headers: {
            'X-CSRF-TOKEN': $('[name="_token"]').val()
          },
          data: $(this).serialize(),
          success: function(response) {
            if(response.status === 'success') {
              setTimeout(function(){
                $('#rotater').removeClass('glyphicon-refresh glyphicon-spin');
                $('#loader1').removeClass('disabled').html('Save');
                $('#form1_alert').addClass('alert-success');
                $('#form1_alert').html(response.msg);
                $("#form1_alert").show();
                setTimeout(function() { $("#form1_alert").hide(); }, 5000);
              }, 2000);
            }
            else {
              var finalresponse = "Sorry, the form could not be submitted.<br>";
              $.each(response.msg, function(key, value) {
                finalresponse += value+"<br>";
              });
              $('#form1_alert').addClass('alert-error');
              $('#form1_alert').html(finalresponse);
              $('#rotater').removeClass('glyphicon-refresh glyphicon-spin');
              $('#loader1').removeClass('disabled').html('Save');
            }
          }
      });
    }
});
var username="{{Auth::user()->username}}";
$(document).ready(function() {
	var options = {
    beforeSubmit:showRequest,
		success:showResponse,
		dataType:'json',
    headers: {
            'X-CSRF-TOKEN': $('[name="_token"]').val()
          }};
 	  $('body').delegate('#image','change', function(){
 		$('#profpic-upload').ajaxForm(options).submit();
 	});
});
function showRequest(formData, jqForm, options) {
	$("#validation-errors").hide().empty();
  return true;
}
function showResponse(response, statusText, xhr, $form)  {
	if(response.success == false)
	{
		var arr = response.errors;
		$.each(arr, function(index, value)
		{
			if (value.length != 0)
			{
				$("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
			}
		});
		$("#validation-errors").show();
	} else {
		 $("#img_replace label[for]").html("<img id=\"img-thumb\" title=\"click image to upload image\" class=\"user img-thumbnail\" src='/user/uploads/"+username+"/"+response.file+"' />");
     $(".dropdown img").attr('src', "/user/uploads/"+username+"/"+response.file);
     $("#validation-errors").append('<div class="col-md-4 col-md-offset-4 alert alert-success"><strong>New picture saved!</strong><div>').show();
     setTimeout(function() {
       $('#validation-errors').fadeOut('slow');
     },5000);
	}
}
</script>
@stop
