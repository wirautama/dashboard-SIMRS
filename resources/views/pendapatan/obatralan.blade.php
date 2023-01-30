@extends('layouts.management')

@section('title')
<title>{{ config('app.name', 'Laravel') }} :: {{ __('Obat Rawat Jalan')  }}</title>
@endsection

@section('head')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('ionicons-v2.0.1/css/ionicons.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- bootstrap datetime picker-->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

@endsection


@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Honor Dokter Dan Pendapatan Obat Rawat Jalan</h1> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('manager.home') }}">Home</a></li>
              <li class="breadcrumb-item active">Obat Rawat Jalan</li>
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
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Data Obat Rawat Jalan <div id ="filter_period_id"></div></h3>
                  <div class="margin float-right">
                    <a class="btn btn-info btn-sm float-right"  href="javascript:void(0)"
                      data-toggle="tooltip" data-placement="top" title="Filter data" id="btn-filter">
                      <i class="fas fa-filter"></i> Filter
                    </a>
                </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-striped" id="data-obat-ralan" style="width: 100% !important">
                        <thead>
                            <tr>
                              <th>Tgl. Registrasi</th>
                              <th>Nota Ralan</th>
                              <th>No. Rawat</th>
                              <th>No. SEP</th>
                              <th>Nama Pasien</th>
                              <th>Nama Dokter</th> 
                              <th>Nama Penjamin</th> 
                              <th>Status</th> 
                              <th>Poli</th> 
                              <th>Kode Barang</th>
                              <th>Nama Barang</th>
                              <th>Harga Beli</th>
                              <th>Harga Jual</th>
                              <th>Jumlah</th>
                              <th>H. Jual x Jmlh</th>
                              <th>PPN (11%)</th>
                              <th>Total Obat + PPN</th>
                            </tr>
                        </thead>
                    </table>
                    <!-- /.table -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.card -->

            
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

      </div>
      <!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Filter data -->
    <div class="modal fade" id="filter-item-modal" role="dialog">
        <div class="modal-dialog">
            <form id="form-filter" name="form-filter" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="create-title">@Lang('common.filter')</h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
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

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_penjab">@Lang('common.penjamin'):</label>
                            <select name="filter_penjab" id="filter_penjab" class="form-control">
                              <option value="all">Semua</option>
                              @foreach($penjab ?? '' as $p )
                              <option value="{{ $p->kd_pj }}">{{ $p->png_jawab }}</option>
                              @endforeach  
                            </select>
                          </div>  
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_poli">@Lang('common.poly'):</label>
                            <select name="filter_poli" id="filter_poli" class="form-control">
                              <option value="all">Semua</option>
                              @foreach($poli as $p )
                              <option value="{{ $p->kd_poli }}">{{ $p->nm_poli }}</option>
                              @endforeach  
                            </select>
                          </div>  
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="filter_doctor">@Lang('common.doctor'):</label>
                            <select name="filter_doctor" id="filter_doctor" class="form-control">
                              <option value="all">Semua</option>
                              @foreach($doctor as $p )
                              <option value="{{ $p->kd_dokter }}">{{ $p->nm_dokter }}</option>
                              @endforeach  
                            </select>
                          </div>  
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary float-right" id="submit-filter">@Lang('common.filter')</button>
                        <button type="button" class="btn btn-default float-right" id="submit-filter-cancel">@Lang('common.cancel')</button>
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
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Momen -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/moment/moment-with-locales.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Toastr -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- overlayScrollbars -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script type="text/javascript" language="javascript" src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

@endsection


@section('javascript')
<script type="text/javascript">

$(document).ready(function () {
    var dateEnd;
    var dateStart;
    var datatableObatRalan;
    var selectedDoctor;
    var selectedPoli;
    var selectedPenjamin;
    var total = 0;

    $('[data-toggle="tooltip"]').tooltip();

    $('#date_start').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'LL',
        startDate: new Date(),
    });

    $('#date_end').datetimepicker({
        locale: 'id',
        viewMode: 'days',
        format:'LL',
    });

    $("#date_start").on("change.datetimepicker", function(e) {
      dateStart= moment(e.date).format("YYYY-MM-DD");
    });


    $("#date_end").on("change.datetimepicker", function (e) {
      dateEnd = moment(e.date).format("YYYY-MM-DD");
    });

  
    //Doctor
    $('#filter_doctor').change(function(){
      selectedDoctor = $(this).val();
    });

    $('#filter_poli').change(function(){
      selectedPoli = $(this).val();
      
    });

    $('#filter_penjab').change(function(){
      selectedPenjamin = $(this).val();
    });

    $('#btn-filter').click(function () {
        $('#dateStart').trigger("reset");
        $('#endStart').trigger('reset');
        $('#filter_doctor').trigger('reset');
        $('#filter_poli').trigger('reset');
        $('#filter_penjab').trigger('reset');
        $('#form-filter').trigger("reset");
        $('#filter-item-modal').modal('show');
    });

    $('#submit-filter-cancel').click(function () {
        $('#form-filter').trigger("reset");
        $('#filter-item-modal').modal('hide');
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

        $('#filter-item-modal').modal('hide');
        
        if ( $.fn.dataTable.isDataTable('#data-obat-ralan')) {
          datatableObatRalan = $('#data-obat-ralan').DataTable().destroy();
        }

        datatableObatRalan = $('#data-obat-ralan').DataTable({
            responsive: true, 
            lengthChange: false, 
            autoWidth: false,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
            ],
            ajax:{
                url: "{{ route('pendapatan.obatralan.list') }}",
                type: 'GET',
                data:{
                  start: dateStart, 
                  end: dateEnd, 
                  doctor: selectedDoctor,
                  poli: selectedPoli, 
                  penjamin: selectedPenjamin
                }
            },
            columns:[
                { data: 'tgl_registrasi', name: 'tgl_registrasi',
                    render: data => {
                        return moment(data, "YYYY-MM-DD").format("DD-MMM-YYYY");
                    }
                },
                { data: 'no_nota', name: 'no_nota', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'no_rawat', name: 'no_rawat' },
                { data: 'no_sep', name: 'no_sep'},
                { data: 'nm_pasien', name: 'nm_pasien'},
                { data: 'nm_dokter', name: 'nm_dokter'},
                { data: 'png_jawab', name: 'png_jawab'},
                { data: 'status_lanjut', name: 'status_lanjut'},
                { data: 'nm_poli', name: 'nm_poli'},
                { data: 'kode_brng', name: 'kode_brng'},
                { data: 'nama_brng', name: 'nama_brng'},
                { data: 'h_beli', name: 'h_beli', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'h_jual', name: 'h_jual', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'jml', name: 'jml', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'total_obat', name: 'total_obat', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'ppn', name: 'ppn', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                },
                { data: 'total_obat_ppn', name: 'total_obat_ppn', className: 'dt-body-right',
                    render: data => {
                        return data.toLocaleString(); 
                    }
                }
            ],
            
          });

          
    });
    
    // reset data request tanggal
    $('#filter-item-modal').on('hide.bs.modal', function(){
        $('#dateStart').trigger("reset");
        $('#endStart').trigger('reset');
        $('#filter_doctor').trigger('reset');
        $('#filter_poli').trigger('reset');
        $('#filter_penjab').trigger('reset');
        $('#form-filter').trigger("reset");
      
      
    });



});

</script>

@endsection
