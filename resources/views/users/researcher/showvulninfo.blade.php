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
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Vulnerability Information</h3>
                <div class="pull-right">
                  <strong>Status :</strong>
                  @if($vuln->vul_status == 0)
                    <span class="text-green">Fixed</span>
                  @elseif($vuln->vul_status == 1)
                    <span class="text-red">Open</span>
                  @elseif($vuln->vul_status == 2)
                    <span class="text-yellow">Won't Fix</span>
                  @else
                  @endif
                  |
                  <strong>VID : <a href="/vulnerability/{{$vuln->vul_id}}">{{$vuln->vul_unique_id}} </a></strong>
                  @if (Auth::user()->id == $vuln->user_vul_author_id)
                     | <a href="/vuln/edit/{{$vuln->vul_id}}" class="btn"><strong> Edit</strong></a>
                  @endif
              </div>
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
                  @if(!is_null($vuln->patch_description) && $vuln->vul_status == 0)
                  <div class="col-md-12">
                    <div class="col-md-9">
                      <h3>Patch Information</h3>
                      <br>
                      <p>{{$vuln->patch_description}}</p>
                      @if(!is_null($vuln->patch_url))
                      <a href="{{$vuln->patch_url}}">{{$vuln->patch_url}}</a><br>
                      @endif
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="col-md-12">
                <hr>
                <div class="row">
                  <div class="col-md-6"><h4 class="box-title">Comments</h4></div>
                  <div class="col-md-6"><button class="btn btn-sm btn-default pull-right" id="addcom">Post a comment</button></div>
                </div>
                <div class="row">
                  <div id="load_comments_ajax" class="col-md-12">
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="bs-example" data-example-id="textarea-form-control">
                  {!! Form::open(['url' => 'vuln/comment', 'class' => 'form','id' =>'commentpost', 'files' => true]) !!}
                    {!! Form::hidden('com_vul_id',$vuln->vul_id) !!}
                    {!! Form::hidden('replyto_id',null, array('class'=>'replyto')) !!}
                    <div class="col-md-12">
                      {!! Editor::view('', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="bg-gray-contrast zero-margin">
                      <div class="col-md-3">
                        <a class="" href="/vuln/getcform/{{$vuln->vul_id}}">Got attachments?</a>
                      </div>
                      <div class="col-md-3 pull-right">
                        {{Form::button('Post', array('type' => 'submit', 'class' => 'btn btn-primary btn-block btn-flat has-spinner','data-loading-text' => "Posting...",'id' => 'loader1'))}}
                        {{Form::button('Clear', array('class' => 'btn btn-default btn-block btn-flat','id' => 'resetbtn'))}}
                      </div>
                    </div>
                  {!! Form::close() !!}
                 </div>
              </div>
              <div class="col-md-4 col-md-offset-4">
                <div id="form1_alert" class="alert"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>
<script>
var firstLoad = true;
$(function(){
    $('#load_comments_ajax').html('<div class="text-center"><p class="fa fa-cog fa-spin fa-5x"></p></div>');
    setTimeout(loadComments,2000);
});
function loadComments(){
  $.ajax({
    url:'/vuln/getcomments/{{$vuln->vul_id}}',
    type:"GET",
    success:function(response){
      commentsoutput = '';
      $.each(response.comments, function(i, val) {
        attachments = '';
        $.each(val['attachment'], function(i,atval) {
            attachments += '<span class="atc bg-gray-contrast"><a href="/user/files/download/'+atval['file_token']+'"> > '+atval['file_name']+'</a></span><br>';
        })
        commentsoutput += '<div class="direct-chat-msg bg-gray-padding">'
                              +'<div class="direct-chat-info clearfix">'
                                +'<span class="direct-chat-name pull-left">@'
                                +val['user']['username']
                                +'</span>'
                                +'<a id="comment-'+val['com_id']+'" href="#comment-'+val['com_id']+'" class="commlink name">'
                                +'<span class="direct-chat-timestamp pull-right">'
                                +val['created_at']
                                +'</span></a></div>'
                                +'<div class="direct-chat-text bg-gray-contrast">'
                                +'<a style="cursor: pointer;" id="quote-'+val['com_id']+' replyto-'+val['user']['id']+'" class="pull-right quote">Quote Reply</a>'
                                +'<div class="output">'+val['com_text']+'</div>'
                                +attachments
                                +'</div>'
                              +'</div>';
      });
      $('#load_comments_ajax').html(commentsoutput).show('slow');
      if(firstLoad == true) {
        var urlHash = window.location.href.split("#")[1];
        firstLoad = false;
        if(typeof urlHash != 'undefined') {
          $('html,body').animate({
              scrollTop: $('#'+urlHash).offset().top
          }, 2000);
        }
      }
    }
  });
}
$('#commentpost').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    form.parsley().validate();
    if (form.parsley().isValid()){
      $('#loader1').addClass('disabled');
      $('#loader1').html('Posting... <i id="rotater" class="glyphicon"></i>');
      $('#rotater').addClass('glyphicon-refresh glyphicon-spin');
      $.ajax({
          type: "POST",
          url: '/vuln/comment',
          headers: {
            'X-CSRF-TOKEN': $('[name="_token"]').val()
          },
          data: $(this).serialize(),
          success: function(response) {
            if(response.status === 'success') {
              setTimeout(function(){
                $('#rotater').removeClass('glyphicon-refresh glyphicon-spin');
                $('#loader1').removeClass('disabled').html('Post');
                $('#form1_alert').addClass('alert-success');
                $('#form1_alert').html('<i class="fa fa-check"></i> '+response.msg);
                $("#form1_alert").show();
                $("textarea").val('');
                $('input.replyto').removeAttr('value');
                setTimeout(function() { $("#form1_alert").hide(); loadComments(); }, 2000);
              }, 2000);
            }
            else {
              var finalresponse = "Sorry, the form could not be submitted.<br>";
              $.each(response.msg, function(key, value) {
                finalresponse += value+"<br>";
              });
              $('#form1_alert').addClass('alert-error');
              $('#form1_alert').html(finalresponse).show();
              $('#rotater').removeClass('glyphicon-refresh glyphicon-spin');
              $('#loader1').removeClass('disabled').html('Post');
              setTimeout(function() { $("#form1_alert").hide(); }, 10000);
              loadComments();
            }
          }
      });
    }
});
$("#addcom").click(function() {
    $('html, body').animate({
        scrollTop: $("#commentpost").offset().top
    }, 2000);
});
$("#resetbtn").click(function() {
    $("textarea").val('');
    $('input.replyto').removeAttr('value');
});
$(document).on('click', "a.quote", function() {
    var username = $(this).parent().prev('div').find('span.direct-chat-name').text();
    var commlink = $(this).parent().prev('div').find('a.commlink').attr('id');
    var getid = $(this).attr('id');
    var content = $(this).next('div.output').text();
    if(content.length>20) {
      content = content.substring(0,50)+'...';
      content = $.trim(content.replace(/\s+/g, " "));
    }

    $('.replyto').val(getid.split('-')[2]);
    $("textarea").val('\n\n\n ****** \n> @'+username+'~ \n '+content+' [View Comment](#'+commlink+')');
    $('html, body').animate({
        scrollTop: $("textarea").offset().top
    }, 2000);
});
</script>
@stop
