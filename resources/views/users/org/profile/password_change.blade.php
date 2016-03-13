@extends('users.org.base')

@section('title')
	Change your password
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Vendor
      <small>Change security settings</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">

		<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<h1>Change Password</h1>
			<p>Here you can change your password. Simply enter the new password and press "Reset Password" button.</p>
			<br>
		        @if(Session::has('message'))
		                <br><br>
		                <div class="alert alert-info errors">{{ Session::get('message') }}</div>
		        @endif
		        @if (count($errors) > 0)
		                <div class="alert alert-danger errors">{!! Html::ul($errors->all(), array('class'=>'errors')) !!}</div>
		        @endif

		        {!! Form::open(array('url' => 'user/settings/password','class'=>'form')) !!}

		        <br>{!! Form::label('password', 'Password') !!}
		        {!! Form::password('password', array('class' => 'form-control')) !!}
		        <br>
		        {!! Form::label('password_confirmation','Confirm Password',['class'=>'control-label']) !!}
		        {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
		        <br>
		        {!! Form::submit('Reset Password' , array('class' => 'btn btn-primary')) !!}

		        {!! Form::close() !!}
		        <br>
		      </div>
		</div>


	</section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@stop
