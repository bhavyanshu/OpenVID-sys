@extends('noauth.baselayout')

@section('title')
    Vulnerability Information
@stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Public Access
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
              <h3>Product Information</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed responsive table-bordered" width="100%">
                  	<thead>
                  		<tr>
                  			<th>Product Name</th>
                        <th>Vendor Name</th>
                  		</tr>
                  	</thead>
                    <tr>
                      <td>{{$product->p_name}}</td>
                      <td>{{$product->p_author_name}}</td>
                    </tr>
                  </table>
                  <p><strong>Product Description :</strong> {{$product->p_description}} - {{$product->p_url}}</p>
                  <hr/>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed responsive " width="100%">
                  	<thead>
                  		<tr>
                  			<th>VID</th>
                        <th>Description</th>
                        <th>OpenVID-sys Score</th>
                  		</tr>
                  	</thead>
                    @foreach($vulns as $v)
                      <tr>
                        <td><a href="/public/vulnerability/{{$v->vul_id}}">{{$v->vul_unique_id}}</a></td>
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
                  </table>
                  <hr/>
                </div>
                <div class="col-md-12">
                  <h2>OpenVID-sys Distribution Graphs</h2>
                  <div class="col-md-8">
                    <p>By Type</p>
                    <div class="chart">
                      <canvas height="227" width="480" id="barChart" style="height: 227px; width: 480px;"></canvas>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <p>By Threat Level</p>
                    <canvas height="250" width="500" id="pieChart" style="height: 250px; width: 500px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </section>
</div>
{!! Html::script('adminlte/plugins/chartjs/Chart.min.js') !!}
<script>
$(document).ready(function () {
  //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: {{$threat_values['high']}},
        color: "#dd4b39",
        highlight: "#dd4b39",
        label: "High"
      },
      {
        value: {{$threat_values['medium']}},
        color: "#ff7701",
        highlight: "#ff7701",
        label: "Medium"
      },
      {
        value: {{$threat_values['low']}},
        color: "#f39c12",
        highlight: "#f39c12",
        label: "Low"
      }
    ];
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);

    //-------------
    //- BAR CHART -
    //-------------
    var areaChartData = {
      labels: ["Bypass Auth", "Cross Site Scripting", "Denial of Service", "Execute Arbitrary Code", "Gain Privileges", "Directory Traversal","HTTP response Splitting", "Memory Corruption", "Overflow","CSRF", "File Inclusion", "SQLi","Critical Info Access"],
      datasets: [
        {
          label: "Number of threats",
          fillColor: "#dd4b39",
          strokeColor: "#dd4b39",
          pointColor: "#dd4b39",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [{{$vultypes['bpa']}},
                {{$vultypes['css']}},
                {{$vultypes['dos']}},
                {{$vultypes['eac']}},
                {{$vultypes['gp']}},
                {{$vultypes['dt']}},
                {{$vultypes['hrs']}},
                {{$vultypes['mc']}},
                {{$vultypes['overflow']}},
                {{$vultypes['csrf']}},
                {{$vultypes['fi']}},
                {{$vultypes['sqli']}},
                {{$vultypes['scia']}}
              ]
        }
      ]
    };
    var barChartCanvas = $("#barChart").get(0).getContext("2d");
    var barChart = new Chart(barChartCanvas);
    var barChartData = areaChartData;
    //barChartData.datasets[1].fillColor = "#00a65a";
    //barChartData.datasets[1].strokeColor = "#00a65a";
    //barChartData.datasets[1].pointColor = "#00a65a";
    var barChartOptions = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: true,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - If there is a stroke on each bar
      barShowStroke: true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth: 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing: 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing: 1,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to make the chart responsive
      responsive: true,
      maintainAspectRatio: true
    };

    barChartOptions.datasetFill = false;
    barChart.Bar(barChartData, barChartOptions);
});
</script>
@stop
