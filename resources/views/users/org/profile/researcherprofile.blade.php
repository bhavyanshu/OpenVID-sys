@extends('users.org.base')

@section('title')
     {{$elseprofile->first_name}} {{$elseprofile->last_name}} Profile
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{$elseprofile->first_name}} {{$elseprofile->last_name}} Profile
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            @if(is_null($elseprofile->profpic) OR empty($elseprofile->profpic))
            <img class="profile-user-img img-responsive img-circle" alt="User Image" src="/images/defaults/avatar.jpg">
            @else
            <img class="profile-user-img img-responsive img-circle" alt="User Image" src="/user/uploads/{{ $elseuser->username.'/'.$elseprofile->profpic}}">
            @endif

            <h3 class="profile-username text-center">{{$elseprofile->first_name}} {{$elseprofile->last_name}}</h3>

            <p class="text-muted text-center">{{$elseprofile->designation}}</p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Credits</b> <a class="pull-right">1,322</a>
              </li>
            </ul>

          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">About Me</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <hr>
            <strong><i class="fa fa-user margin-r-5"></i> Designation</strong>
            <p>
              {{$elseprofile->designation}}
            </p>
            <hr>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#lists" data-toggle="tab">Vulnerabilities Reported</a></li>
          </ul>
          <div class="tab-content">
            <div class="active tab-pane" id="lists">
              @if(count($vulns)<1)
                <p class="lead">No vulnerabilities have been reported yet.</p>
              @else
                <h4>Vulnerabilities Reported by {{$elseprofile->first_name}} {{$elseprofile->last_name}}</h4>
              @endif
              @foreach ($vulns as $v)
              <div class="box-body box-padding">
                <h5 class="box-title"><a href="/vulnerability/{{$v->vul_id}}">{{$v->vul_unique_id}}</a></h5>
                <div class="col-md-12">
                  <div class="col-md-3">
                    {{$v->product->p_name}}
                  </div>
                  <div class="col-md-3">
                    By {{$v->product->p_author_name}}
                  </div>
                  <div class="col-md-3">
                    @if($v->threat_level <= 3)
                     <p class="text-center bg-yellow disabled">OpenVID-sys Score {{$v->threat_level}}</p>
                    @elseif($v->threat_level >3 & $v->threat_level <= 7)
                     <p class="text-center bg-orange-active">OpenVID-sys Score {{$v->threat_level}}</p>
                    @else
                     <p class="text-center bg-red">OpenVID-sys Score {{$v->threat_level}}</p>
                    @endif
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="col-md-12">
                    Vulnerability Description : {{ str_limit($v->vul_description, $limit = 95, $end = '...') }}
                  </div>
                </div>
                <div class="col-md-12">
                  <hr>
                </div>
              </div>
              @endforeach
              <div class="row text-center">
                {{$vulns->render()}}
              </div>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
        <!-- /.nav-tabs-custom -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@stop
