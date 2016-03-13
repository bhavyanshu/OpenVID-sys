@extends('noauth.baselayout')
@section('title') OpenVID-sys | Developed by Bhavyanshu Parasher @stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      OpenVID-sys
      <small>Vulnerability Information Disclosure System</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Jumbotron -->
    <div class="jumbotron">
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">
            <p class="lead">OpenVID-sys is an open source web app built on top of <a href="http://laravel.com">Laravel framework</a> that provides a platform to security researchers to connect with the developers of the products and discuss security flaws. Any organization can host this on their server and by registering, the security researchers can directly connect with the developers of the product.</p>
            <h2>Register as</h2>
            <div class="col-md-12">
              <span><strong>Vendors/Organizations</strong> : You can register your product against which the security researchers can share vulnerabilities with you.</span><br>
              <a class="btn btn-default btn-flat" href="/user/org/register"><i class="fa fa-user-plus"></i> Sign Up</a>
              <hr/>
            </div>
            <div class="col-md-12">
              <span><strong>Researcher</strong> : You can share vulnerabilities found in products added by organizations.</span><br>
              <a class="btn btn-default btn-flat" href="/user/res/register"><i class="fa fa-user-plus"></i> Sign Up</a>
            </div>
          </div>
          <div class="col-md-4 pull-right">
            <h2>Sign In</h2>
            @if(Session::has('message'))
        		<br>
        		<div class="alert alert-info errors">{{ Session::get('message') }}</div>
          	@endif
        		{!! Form::open(array('url' => '/user/login','class'=>'form')) !!}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        		<br>{!! Form::label('email', 'E-Mail Address') !!}
        		{!! Form::text('email', old('email'), array('class' => 'form-control','placeholder' => 'example@gmail.com')) !!}
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        		{!! Form::label('password', 'Password') !!}
        		{!! Form::password('password', array('class' => 'form-control')) !!}
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
            </div>
            <div class="checkbox" style="padding-left:20px;">
         		{!! Form::checkbox('remember', null, null) !!} Remember Me
         		</div>
            <button type="submit" class="btn btn-primary">
              Login  <i class="glyphicon glyphicon-log-in"></i>
            </button>
            {!! Html::link('user/password/reset', 'Forgot password?',array('class'=>'btn btn-link'))!!}
         		<br>
        		{!! Form::close() !!}
            <br>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
@stop
