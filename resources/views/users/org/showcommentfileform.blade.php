@extends('users.org.base')

@section('title')
    Post comment for vulnerability
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
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3>Vulnerability Information</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <div class="col-md-4">
                      <p>Project Name : <a href="{{$product->p_url}}">{{$product->p_name}}</a></p>
                    </div>
                    <div class="col-md-4">
                      <p>Project Author : <a href="mailto:{{$product->p_author_email}}">{{$product->p_author_name}}</a></p>
                    </div>
                    <div class="col-md-4">
                      <p>Representative : {{$vendorprof->first_name}} {{$vendorprof->last_name}} ({{$vendorprof->designation}})</p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-12">
                      <p>Project Description : {{$product->p_type}} - {{$product->p_description}}</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr>
                </div>
                <div class="col-md-12">
                  <div class="col-md-12">
                    <div class="col-md-4">
                      <p>Found By : {{$vuln->vul_author_name}} - {{$vuln->vul_author_email}}</p>
                    </div>
                    <div class="col-md-8">
                      <p>Description : {{$vuln->vul_description}}</p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <br>
                    <div class="col-md-3">
                      <p><strong>
                        Type :
                        @if($vuln->vul_type == 0)
                          Bypass authentication/restriction
                        @elseif($vuln->vul_type == 1)
                          Cross Site Scripting
                        @elseif($vuln->vul_type == 2)
                          Denial of service
                        @elseif($vuln->vul_type == 3)
                          Execute arbitrary code
                        @elseif($vuln->vul_type == 4)
                          Gain Privileges
                        @elseif($vuln->vul_type == 5)
                          Directory Traversal
                        @elseif($vuln->vul_type == 6)
                          Http Response Splitting
                        @elseif($vuln->vul_type == 7)
                          Memory Corruption
                        @elseif($vuln->vul_type == 8)
                          Overflow (stack/heap/other)
                        @elseif($vuln->vul_type == 9)
                          CSRF
                        @elseif($vuln->vul_type == 10)
                          File Inclusion
                        @elseif($vuln->vul_type == 11)
                          SQL Injection
                        @elseif($vuln->vul_type == 12)
                          Unrestricted Critical Information
                        @else
                        @endif
                      </strong>
                      </p>
                    </div>
                    <div class="col-md-3">
                      Complexity :
                        @if($vuln->vul_complexity == 0)
                          <span class="text-red">Easy to exploit</span>
                        @elseif($vuln->vul_complexity == 1)
                          <span class="text-yellow">Access conditions are somewhat specialized</span>
                        @elseif($vuln->vul_complexity == 2)
                          <span class="text-green">Access conditions highly specialized</span>
                        @else
                        @endif
                    </div>
                    <div class="col-md-3">
                        Authentication :
                        @if($vuln->vul_auth == 0)
                          Not Required
                        @elseif($vuln->vul_auth == 1)
                          Required
                        @else
                        @endif
                    </div>
                    <div class="col-md-3">
                        Confidentiality :
                        @if($vuln->vul_confidentiality == 0)
                          <span class="text-green">No information disclosure</span>
                        @elseif($vuln->vul_confidentiality == 1)
                          <span class="text-yellow">Considerable informational disclosure</span>
                        @elseif($vuln->vul_confidentiality == 2)
                          <span class="text-red">Complete informational disclosure</span>
                        @else
                        @endif
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-3">
                        Integrity :
                        @if($vuln->vul_integrity == 0)
                          <span class="text-green">Exploiter cannot modify any file</span>
                        @elseif($vuln->vul_integrity == 1)
                          <span class="text-yellow">Partial - Exploiter can modify some files</span>
                        @elseif($vuln->vul_integrity == 2)
                          <span class="text-red">Complete - Exploiter can modify most files</span>
                        @else
                        @endif
                    </div>
                    <div class="col-md-3">
                        Performance :
                        @if($vuln->vul_performance == 0)
                          <span class="text-green">No effect</span>
                        @elseif($vuln->vul_performance == 1)
                          <span class="text-yellow">Partial takedown</span>
                        @elseif($vuln->vul_performance == 2)
                          <span class="text-red">Complete takedown</span>
                        @else
                        @endif
                    </div>
                    <div class="col-md-3">
                        Access :
                        @if($vuln->vul_access == 0)
                          <span class="text-green">None</span>
                        @elseif($vuln->vul_access == 1)
                          <span class="text-red">Admin Level</span>
                        @elseif($vuln->vul_access == 2)
                          <span class="text-yellow">User Level</span>
                        @elseif($vuln->vul_access == 3)
                          <span class="text-yellow">Other/Unknown</span>
                        @else
                        @endif
                    </div>
                    <div class="col-md-3">
                      @if($vuln->threat_level <= 3)
                       <p class="text-center bg-yellow disabled">OpenVID-sys Score {{$vuln->threat_level}}</p>
                      @elseif($vuln->threat_level >3 & $vuln->threat_level <= 7)
                       <p class="text-center bg-orange-active">OpenVID-sys Score {{$vuln->threat_level}}</p>
                      @else
                       <p class="text-center bg-red">OpenVID-sys Score {{$vuln->threat_level}}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-9">
                      <br>
                      <a href="{{$vuln->ref_url_1}}">{{$vuln->ref_url_1}}</a><br>
                      <a href="{{$vuln->ref_url_2}}">{{$vuln->ref_url_2}}</a><br>
                      <a href="{{$vuln->ref_url_3}}">{{$vuln->ref_url_3}}</a><br>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <?php $content = session('content'); ?>
                <div class="col-md-12">
                  {!! Form::open(['url' => '/vuln/comment/fileupload', 'class' => 'form','id' =>'commentpost', 'files' => true]) !!}
                    {!! Form::hidden('com_vul_id',$vuln->vul_id) !!}
                    <div class="form-group{{ $errors->has('vinelab-editor-text') ? ' has-error' : '' }}">
                      {!! Editor::view($content, ['class' => 'form-control']) !!}
                      @if ($errors->has('vinelab-editor-text'))
                          <span class="alert-danger">
                              <strong>{{ $errors->first('vinelab-editor-text') }}</strong>
                          </span>
                      @endif
                    </div>
                      <div class="row">
                        <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                          <div class="col-md-3">{{ Form::file('files[]','',array('id'=>'file1','class'=>'')) }}</div>
                          <div class="col-md-3">{{ Form::file('files[]','',array('id'=>'file2','class'=>'')) }}</div>
                          <div class="col-md-3">{{ Form::file('files[]','',array('id'=>'file3','class'=>'')) }}</div>
                          <div class="col-md-12">
                            @if ($errors->has('file'))
                                <span class="alert-danger">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif
                          </div>
                        </div>
                        @if(Session::has('message'))
                        <br>
                        <div class="alert alert-success errors">{{ Session::get('message') }}</div>
                        @endif
                      </div>
                      <br>
                      {{Form::button('Post', array('type' => 'submit', 'class' => 'btn btn-sm btn-primary has-spinner','data-loading-text' => "Posting...",'id' => 'loader1'))}}
                  {!! Form::close() !!}
                  <div id="form1_alert" class="alert"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>
<script>
$(document).ready(function() {
    var t = $('textarea').val().replace('&lt;','<').replace('&gt;', '>');
    $('textarea').val(t);
});
</script>
@stop
