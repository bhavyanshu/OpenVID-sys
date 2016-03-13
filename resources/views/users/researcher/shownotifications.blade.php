@extends('users.researcher.base')

@section('title')
    User Notifications Manager
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
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3>Notifications Manager</h3>
            </div>
            <div class="box-body">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#unread" data-toggle="tab">Unread</a></li>
                  <li><a href="#all" data-toggle="tab">All</a></li>
                </ul>
                <div class="tab-content">
                  <div class="active tab-pane" id="unread">
                    <?php $unread = Auth::user()->getNotificationsNotRead(20); ?>
                    @if(count($unread)>0)
                    <ul class="timeline">
                      <!-- timeline time label -->
                      <li class="time-label">
                        <span class="bg-red">
                            Unread
                        </span>
                        <span class="pull-right"><a class="btn btn-default bg-gray" href="/user/notifications/mark/read"> Mark All Read <i class="fa fa-check"></i></a></span>
                      </li>
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      @foreach($unread as $n)
                      <li>
                        <!-- timeline icon -->
                        <i class="fa fa-comment bg-blue"></i>
                        <div class="timeline-item bg-gray disabled">
                            <span class="time text-blue"><i class="fa fa-clock-o"></i>{{ date('D d-m-Y h:ia', strtotime($n->created_at)) }}</span>
                            <h3 class="timeline-header">
                              @if($n->body->id==1)
                              <span class="text-red">Vulnerability Reported</span>
                              @elseif($n->body->id==2)
                              <span>Comment</span>
                              @elseif($n->body->id==3)
                              <span class="text-orange">Report Status Update</span>
                              @endif</h3>
                            <div class="timeline-body">
                                <a href="/profile/view/{{$n->from->id}}">{{$n->from->username}}</a> {{$n->body->text}} - <a href="{{$n->url}}">View</a>
                            </div>
                        </div>
                      </li>
                      @endforeach
                      <!-- END timeline item -->
                    </ul>
                    @else
                      <p class="lead">Hurray! No unread notifications!</p>
                    @endif
                    </div>
                    <div class="tab-pane" id="all">
                      <?php $read = Auth::user()->getNotifications(20); ?>
                      @if(count($read)>0)
                        <ul class="timeline">
                          <!-- timeline time label -->
                          <li class="time-label">
                            <span class="bg-red">
                                All Notifications
                            </span>
                          </li>
                          <!-- /.timeline-label -->
                          <!-- timeline item -->
                          @foreach($read as $nr)
                          <li>
                            <!-- timeline icon -->
                            <i class="fa fa-comment bg-blue"></i>
                            <div class="timeline-item bg-gray disabled">
                                <span class="time text-blue"><i class="fa fa-clock-o"></i>{{ date('D d-m-Y h:ia', strtotime($nr->created_at)) }}</span>
                                <h3 class="timeline-header">
                                  @if($nr->body->id==1)
                                  <span class="text-red">Vulnerability Reported</span>
                                  @elseif($nr->body->id==2)
                                  <span>Comment</span>
                                  @endif</h3>
                                <div class="timeline-body">
                                    <a href="/profile/view/{{$nr->from->id}}">{{$nr->from->username}}</a> {{$nr->body->text}} - <a href="{{$nr->url}}">View</a>
                                </div>
                            </div>
                          </li>
                          @endforeach
                          <!-- END timeline item -->
                        </ul>
                      @else
                        <p class="lead">Currently there are no notifications!</p>
                      @endif
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>
@stop
