@extends('layouts.management')

@section('title')
<title>{{ config('app.name', 'Laravel') }} :: {{ __('Rekam Medis')  }}</title>
@endsection

@section('head')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('ionicons-v2.0.1/css/ionicons.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- bootstrap datetime picker-->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}" />

@endsection


@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Rekam Medis</h1> <small><div id ="filter_period_id"></div></small>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('manager.home') }}">Home</a></li>
              <li class="breadcrumb-item active">Rekam Medis</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <a class="btn btn-info btn-sm float-right" style="margin-bottom: 5px;" href="javascript:void(0)"
              data-toggle="modal" id="btn-filter">
              <i class="fas fa-filter"></i> Filter</a>
          </div>
        </div>

        <!-- Small boxes (Stat box) -->
        <div class="row">

          <div class="col-lg-3 col-6">
            <div class="row">

              <div class="col-12">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3><div id="doctor_count_id">0</div></h3>
                    <h2>Dokter</h2>
                  </div>
                  <div class="icon">
                    <i class="ion ion-medkit"></i>
                  </div>
                </div>
              </div>

              <div class="col-12">
                <!-- small box -->
                <div class="small-box bg-success">
                  <div class="inner">
                    <div class="row">
                      <div class="col-lg-3 col-6">
                        <div class="description-block">
                          <span class="description-text"><h5 class="description-header"><div id="px_male_count_id">0</div></h5> LAKI-LAKI</span> 
                        </div>
                        <!-- /.description-block -->
                      </div>
                      <div class="col-lg-3 col-6">
                        <div class="description-block">
                          <span class="description-text"><h5 class="description-header"><div id="px_female_count_id">0</div></h5> PEREMPUAN</span>
                        </div>
                        <!-- /.description-block -->
                      </div>
                    </div>
                    <!-- /.row -->
                    <h2>Pasien</h2>
                  </div>
                  <div class="icon">
                    <i class="ion ion-person"></i>
                  </div>
                </div>
              </div>

            </div>
            <!-- ./row -->
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <div class="description-block">
                      
                      <span class="description-text"><h5 class="description-header"><div id="px_readmission_count_id">0</div></h5> Readmission</span> 
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="description-block">
                      
                      <span class="description-text"><h5 class="description-header"><div id="px_admission_count_id">0</div></h5> Admission</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                </div>
                <!-- /.row -->
                
                <h3><div id="px_readmission_rate_count_id">0</div><sup style="font-size: 20px">%</sup></h3>
                <h2>Readmission Rate</h2>
              </div>
              <!-- /.inner -->
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
            <!-- /.small box -->
          </div>
          <!-- ./col -->
          
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <div class="row">
                  <div class="col-lg-3 col-6">
                    <div class="description-block">
                      <span class="description-text"><h5 class="description-header"><div id="days_count_id">0</div></h5> Hari</span> 
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <div class="col-lg-3 col-6">
                    <div class="description-block">
                      <span class="description-text"><h5 class="description-header"><div id="rooms_count_id">0</div></h5> Kamar</span>
                    </div>
                    <!-- /.description-block -->
                  </div>

                  <div class="col-lg-3 col-6">
                    <div class="description-block">
                      <span class="description-text"><h5 class="description-header"><div id="daycare_count_id">0</div></h5> Hari Rawat</span>
                    </div>
                    <!-- /.description-block -->
                  </div>

                </div>
                <!-- /.row -->
                <h3><div id="bor_count_id">0</div><sup style="font-size: 20px">%</sup></h3>
                <h2>Bed Occupancy Rate</h2>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-6">
            <div class="row ">
              <div class="col-12">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><div id="admitted_px_count_id">0</div></h3>
                    <h2>Admitted Patient</h2>
                  </div>
                  <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                  </div>
                </div>
                <!--/ small box -->
              </div>
              <!--/ col -->

              <div class="col-12">
                <div class="small-box bg-info">
                  <div class="inner">
                    <h3><div id="alos_count_id">0</div></h3>
                    <h2>Avg Length Of Stay</h2>
                  </div>
                  <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                  </div>
                </div>
                <!--/ small box -->
              </div>
              <!--/ col -->
            </div>
            <!--/ row -->
          </div>
          <!-- ./col -->

        </div>
        <!-- ./row -->
        
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Patient In/Out By Week</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="visitors-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> In
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Out
                  </span>
                </div>

              </div>
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Upcoming Appointments</h3>
                
              </div>
              <div class="card-body table-responsive">
                  <table class="table table-hover table-striped small" id="dataregistered" style="width: 100% !important">
                      <thead>
                          <tr>
                            <th>Dokter</th>
                            <th>Poli</th>
                            <th>Pasien</th>
                            <th>Tanggal</th>
                          </tr>
                      </thead>
                  </table>
                  <!-- /.table -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Patient Count By Specialization</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">10 Diagnosa Terbesar Ranap</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->

              
            </div>
            <!-- /.card -->


          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->



      </div>
      <!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Filter data -->
    <div class="modal fade" id="create-new-item-modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <form id="form1" name="form1" >
                <input type="hidden" id="id" name="id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="create-title">@Lang('common.filter')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dos">@Lang('common.date_start'):</label>
                                <div class="input-group date" id="date_start" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#date_start"/>
                                    <div class="input-group-append" data-target="#date_start" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dos">@Lang('common.date_end'):</label>
                                <div class="input-group date" id="date_end" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#date_end"/>
                                    <div class="input-group-append" data-target="#date_end" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary float-right" id="submit-filter">@Lang('common.filter')</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </form>
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

  </div>
  <!-- /.content-wrapper -->

@endsection

@section('includejs')
<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Momen -->
<!-- DataTables -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('adminlte/plugins/moment/moment-with-locales.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

@endsection


@section('javascript')
<script type="text/javascript">

$(document).ready(function () {
    var dateEnd;
    var dateStart;

    $('[data-toggle="tooltip"]').tooltip();

    $('#date_start').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'LL',
    });

    $('#date_end').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'LL',
    });

    $("#date_start").on("change.datetimepicker", function (e) {
      dateStart = moment(e.date, 'YYYY-MM-DD').format("YYYY-MM-DD");
    });

    $("#date_end").on("change.datetimepicker", function (e) {
      dateEnd = moment(e.date, 'YYYY-MM-DD').format("YYYY-MM-DD");
    });

    $('#btn-filter').click(function () {
        $('#submit-create').val("<?php Lang::get('common.saves')  ?>");
        $('#form1').trigger("reset");
        
        $('#create-new-item-modal').modal('show');
    });

    $('#submit-filter').click(function(e) {
        var period = "("+ moment(dateStart, "YYYY-MM-DD").format("DD MMM YYYY") + " - "+ moment(dateEnd, "YYYY-MM-DD").format("DD MMM YYYY") + ")";
        $('#filter_period_id').text(period); 
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#create-new-item-modal').modal('hide');
        
        $.ajax({
          url: 'medrec/getalos/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){
            $('#alos_count_id').text(data); 
          }
        });

        $.ajax({
          url: 'medrec/getbor/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){
            $('#bor_count_id').text(data['bor']);
            $('#days_count_id').text(data['hari']); 
            $('#rooms_count_id').text(data['kamar']);
            $('#daycare_count_id').text(data['lama']);
          }
        });
        
        $.ajax({
          url: 'medrec/getdoctor',
          type: 'GET',
          dataType: 'json',
          success: function(data){
            $('#doctor_count_id').text(data['doctor']);
          }
        });

        $.ajax({
          url: 'medrec/getpatient/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){
            $('#px_male_count_id').text(data['L']);
            $('#px_female_count_id').text(data['P']);
            $('#admitted_px_count_id').text(data['admitted']);
          }
        });
        
        $.ajax({
          url: 'medrec/getReadmittedRate/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){
            $('#px_readmission_count_id').text(data['Lama']);
            $('#px_admission_count_id').text(data['Baru']);
            $('#px_readmission_rate_count_id').text(data['admitted']);
          }
        });

        $.ajax({
          url: 'medrec/getAdmittedRateRalan/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){

            var poli = [];
            var jumlah = [];

            for (let i = 0; i < data.length; i++) {
              var x = data[i]['jumlah'];
              var y = data[i]['nm_poli']

              jumlah.push(x);
              poli.push(y);

            }
            
            var chartdata = {
                labels: poli,
                datasets: [
                    {
                        label: 'Patient Count',
                        backgroundColor: '#49e2ff',
                        borderColor: '#46d5f1',
                        hoverBackgroundColor: '#CCCCCC',
                        hoverBorderColor: '#666666',
                        data: jumlah
                    }
                ]
            };

            var barChartOptions = {
              responsive              : true,
              maintainAspectRatio     : false,
              datasetFill             : false
            }
            
            var graphTarget = $("#barChart");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata,
                options: barChartOptions
            });
          }
            
        });

        //diagnosa 10 penyakit
        $.ajax({
          url: 'medrec/getBestDiagnose/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){

            
            var jumlah = [];
            var kode_diagnosa = [];
            // var nama_diagnosa = [];

            for (let i = 0; i < data.length; i++) {
              var x = data[i]['jumlah'];
              var y = data[i]['kd_penyakit'];
              // var z = data[i]['nm_penyakit']


              jumlah.push(x);
              kode_diagnosa.push(y);
              // nama_diagnosa.push(z);
            }
            
            var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
            var donutData        = {
              labels: kode_diagnosa ,
              datasets: [
                {
                  data: jumlah ,
                  backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }
              ]
            }
            
            var donutOptions     = {
              maintainAspectRatio : false,
              responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(donutChartCanvas, {
              type: 'doughnut',
              data: donutData,
              options: donutOptions
            })

          }
            
        });

        //In Out Patient
        $.ajax({
          url: 'medrec/getInOut/'+dateStart+"/"+dateEnd,
          type: 'GET',
          dataType: 'json',
          success: function(data){
            console.log(data['in']);
            console.log(data['out']);

            var data_in = data['in'];
            var data_out = data['out'];

            var label = [];
            var px_in = [];
            for (let i = 0; i < data_in.length; i++) {
              var x = data_in[i]['Week'];
              var y = data_in[i]['total'];

              label.push(x);
              px_in.push(y);
            }

            var px_out = [];
            for (let i = 0; i < data_out.length; i++) {
              var z = data_out[i]['total'];

              px_out.push(z);
            }

            var ticksStyle = {
              fontColor: '#495057',
              fontStyle: 'bold'
            }

            var mode = 'index'
            var intersect = true

            var $visitorsChart = $('#visitors-chart')
            // eslint-disable-next-line no-unused-vars
            var visitorsChart = new Chart($visitorsChart, {
              data: {
                labels: label,
                datasets: [
                  {
                  type: 'line',
                  data: px_in,
                  backgroundColor: 'transparent',
                  borderColor: '#007bff',
                  pointBorderColor: '#007bff',
                  pointBackgroundColor: '#007bff',
                  fill: false
                  // pointHoverBackgroundColor: '#007bff',
                  // pointHoverBorderColor    : '#007bff'
                },
                {
                  type: 'line',
                  data: px_out,
                  backgroundColor: 'tansparent',
                  borderColor: '#ced4da',
                  pointBorderColor: '#ced4da',
                  pointBackgroundColor: '#ced4da',
                  fill: false
                  // pointHoverBackgroundColor: '#ced4da',
                  // pointHoverBorderColor    : '#ced4da'
                }]
              },
              options: {
                maintainAspectRatio: false,
                tooltips: {
                  mode: mode,
                  intersect: intersect
                },
                hover: {
                  mode: mode,
                  intersect: intersect
                },
                legend: {
                  display: false
                },
                scales: {
                  yAxes: [{
                    // display: false,
                    gridLines: {
                      display: true,
                      lineWidth: '4px',
                      color: 'rgba(0, 0, 0, .2)',
                      zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                      beginAtZero: true,
                      suggestedMax: 200
                    }, ticksStyle)
                  }],
                  xAxes: [{
                    display: true,
                    gridLines: {
                      display: false
                    },
                    ticks: ticksStyle
                  }]
                }
              }
            })


          }
            
        });

        $('#dataregistered').DataTable({
         paging      : false,
         searching   : false,
         info        : true,
         ordering    : false,
         processing: true,
         serverSide: true,
         pageLength: 15,
         responsive: true,
         ajax:{
             url: "medrec/getAppointments",
             type: 'GET',
         },

         columns:[
             { data: 'nm_dokter', name: 'nm_dokter' },
             { data: 'nm_poli', name: 'nm_poli'},
             { data: 'no_rkm_medis', name: 'no_rkm_medis'},
             { data: 'dob', name: 'dob',
               render: data => {
                  return moment(data, "YYYY-MM-DD").format("DD MMM YYYY");
              }
             }
         ]
     });

    });


});

</script>

@endsection
