@extends('users.org.base')

@section('title')
    Dashboard
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
    </div>
    <div class="row">
      <div class="col-md-12">
      @if(count($vulns)>0)
        <div class="box box-default">
          <div class="box-header with-border">
            Latest Vulnerabilities reported for your products
          </div>
          <div class="box-body">
            <table class="table table-striped table-condensed responsive">
              <thead>
              <tr>
                <th>OpenVID-sys ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>OpenVID-sys Score</th>
              </tr>
              </thead>
              <tbody>
                @foreach($vulns as $v)
                  <tr>
                    <td><a href="/vulnerability/{{$v->vul_id}}">{{$v->vul_unique_id}}</a></td>
                    <td><a href="/product/{{$v->p_id}}">{{$v->p_name}}</a></td>
                    <td>{{ str_limit($v->vul_description, $limit = 95, $end = '...') }}</td>
                    <td>
                      @if($v->threat_level <= 3)
                       <p class="text-center bg-yellow disabled">{{$v->threat_level}}</p>
                      @elseif($v->threat_level >3 & $v->threat_level <= 7)
                       <p class="text-center bg-orange-active">{{$v->threat_level}}</p>
                      @else
                       <p class="text-center bg-red">{{$v->threat_level}}</p>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
      </div>
      <div class="col-md-12">
      @if(count($activevulns)>0)
        <div class="box box-default">
          <div class="box-header with-border">
            Active Vulnerabilities
          </div>
          <div class="box-body">
            <table class="table table-striped table-condensed responsive">
              <thead>
              <tr>
                <th>OpenVID-sys ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>OpenVID-sys Score</th>
              </tr>
              </thead>
              <tbody>
                @foreach($activevulns as $v)
                  <tr>
                    <td><a href="/vulnerability/{{$v->vul_id}}">{{$v->vul_unique_id}}</a></td>
                    <td><a href="/product/{{$v->p_id}}">{{$v->p_name}}</a></td>
                    <td>{{ str_limit($v->vul_description, $limit = 95, $end = '...') }}</td>
                    <td>
                      @if($v->threat_level <= 3)
                       <p class="text-center bg-yellow disabled">{{$v->threat_level}}</p>
                      @elseif($v->threat_level >3 & $v->threat_level <= 7)
                       <p class="text-center bg-orange-active">{{$v->threat_level}}</p>
                      @else
                       <p class="text-center bg-red">{{$v->threat_level}}</p>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
      @if(count($highthreatvulns)>0)
        <div class="box box-default">
          <div class="box-header with-border">
            All High Threat Vulnerabilities
          </div>
          <div class="box-body">
            <table class="table table-striped table-condensed responsive">
              <thead>
              <tr>
                <th>OpenVID-sys ID</th>
                <th>Product Name</th>
                <th>Description</th>
                <th>OpenVID-sys Score</th>
              </tr>
              </thead>
              <tbody>
                @foreach($highthreatvulns as $v)
                  <tr>
                    <td><a href="/vulnerability/{{$v->vul_id}}">{{$v->vul_unique_id}}</a></td>
                    <td><a href="/product/{{$v->p_id}}">{{$v->p_name}}</a></td>
                    <td>{{ str_limit($v->vul_description, $limit = 95, $end = '...') }}</td>
                    <td>
                      @if($v->threat_level <= 3)
                       <p class="text-center bg-yellow disabled">{{$v->threat_level}}</p>
                      @elseif($v->threat_level >3 & $v->threat_level <= 7)
                       <p class="text-center bg-orange-active">{{$v->threat_level}}</p>
                      @else
                       <p class="text-center bg-red">{{$v->threat_level}}</p>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@stop
