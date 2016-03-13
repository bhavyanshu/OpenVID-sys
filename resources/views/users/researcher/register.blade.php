@extends('noauth.baselayout')

@section('title')
    Register - Researcher
@stop

@section('content')
<div class="content-wrapper">
  <div class="row">
      <div class="col-md-6 col-md-offset-3 register-box-body">
          <h2>Register for a researcher account</h2>
          <br>
          @if(Session::has('message'))
      		<br>
      		<div class="alert alert-info errors">{{ Session::get('message') }}</div>
        	@endif
          {!! Form::open(array('url' => '/user/register','class'=>'form','data-parsley-validate')) !!}
          {!! Form::hidden('_u_ro_id', '3') !!}
          <div class="row">
          <div class="form-group col-xs-6">
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
            <br>{!! Form::label('first_name', 'First Name') !!}
            {!! Form::text('first_name', null, array(
              'class' => 'form-control',
              'placeholder' => '','required',
            'data-parsley-required-message' => 'This is required',
            'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
            'data-parsley-pattern-message' => 'This can only contain aphabets',
            'data-parsley-trigger' => 'change focusout',
            'data-parsley-pattern' => '/^[a-zA-Z]*$/',
            'data-parsley-minlength' => '3')) !!}
            @if ($errors->has('first_name'))
                <span class="alert-danger">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
            @endif
            </div>
          </div>

            <div class="form-group col-xs-6">
              <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
              <br>{!! Form::label('last_name', 'Last Name') !!}
              {!! Form::text('last_name', null, array(
                'class' => 'form-control',
                'placeholder' => '','required',
                'data-parsley-required-message' => 'This is required',
                'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
                'data-parsley-pattern-message' => 'This can only contain aphabets',
                'data-parsley-trigger' => 'change focusout',
                'data-parsley-pattern' => '/^[a-zA-Z]*$/',
                'data-parsley-minlength' => '3')) !!}
              @if ($errors->has('last_name'))
                  <span class="alert-danger">
                      <strong>{{ $errors->first('last_name') }}</strong>
                  </span>
              @endif
              </div>
            </div>
          </div>

          <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
          <br>{!! Form::label('username', 'Username') !!}
          {!! Form::text('username', null, array(
            'class' => 'form-control',
            'placeholder' => '','required',
            'data-parsley-required-message' => 'This is required',
            'data-parsley-minlength-message' => 'This cannot be less than 5 characters',
            'data-parsley-pattern-message' => 'This can only contain aphabets, numbers & (_) character',
            'data-parsley-trigger' => 'change focusout',
            'data-parsley-pattern' => '/^[a-zA-Z0-9_]*$/',
            'data-parsley-minlength' => '5')) !!}
          @if ($errors->has('username'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('username') }}</strong>
              </span>
          @endif
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <br>{!! Form::label('email', 'E-Mail Address') !!}
          {!! Form::text('email', null, array(
            'class' => 'form-control',
            'placeholder' => 'example@gmail.com','required',
            'data-parsley-required-message' => 'This is required',
            'data-parsley-minlength-message' => 'This cannot be less than 3 characters',
            'data-parsley-pattern-message' => 'This can only contain aphabets, numbers & (_.@)',
            'data-parsley-trigger' => 'change focusout',
            'data-parsley-pattern' => '/^[a-zA-Z0-9_.@]*$/',
            'data-parsley-minlength' => '3')) !!}
          @if ($errors->has('email'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
          </div>

          <div class="row"><div class="form-group col-xs-6">
          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <br>{!! Form::label('password', 'Password') !!}
          {!! Form::password('password', array(
            'class' => 'form-control','required',
            'id'                            => 'inputPassword',
            'data-parsley-required-message' => 'Password is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-minlength'        => '6',
            'data-parsley-maxlength'        => '20')) !!}
          @if ($errors->has('password'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
          </div></div>

          <div class="form-group col-xs-6">
          <br>{!! Form::label('password_confirmation','Confirm Password',['class'=>'control-label']) !!}
          {!! Form::password('password_confirmation',array(
            'class'=>'form-control','required',
            'id'                            => 'inputPasswordConfirm',
            'data-parsley-required-message' => 'Password confirmation is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-equalto'          => '#inputPassword',
            'data-parsley-equalto-message'  => 'Not same as Password')) !!}
          </div></div>
          <br>{!! Form::submit('Sign Up' , array('class' => 'btn btn-primary')) !!}

          {!! Form::close() !!}
          <br>
      </div>
  </div>
</div>
@stop
