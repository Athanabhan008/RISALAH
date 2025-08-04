@extends('layouts.manager.template_manager')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<main class="main-content max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tables</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Tables</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
            <div class="card" style="border-top: 2px solid #32636c; filter: drop-shadow(2px 2px 8px rgb(215, 215, 215)); width: 100%;">
              <div class="card-header py-2">
                <h6 class="card-title text-dark">
                  Report Booking
                </h6>
              </div>
              <div class="card-body pb-0">
                <div class="row">
                  <div class="col-7 mx-auto">

                    <form id="soundForm">
                      @csrf

                        <div class="form-group">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-light" style="width: 127px;">Jenis Laporan</span>
                                <select class="select2" name="cmb_jenis" id="cmb_jenis">
                                  <option value=""></option>
                                  <option value="1">Laporan Penjualan Per Bulan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-sm mb-3">
                                <span class="input-group-text bg-light" style="width: 127px;">Periode</span>
                                <input style="height: auto;" type="month" class="form-control" name="periode" id="periode" pattern="\d{4}\d{2}">
                            </div>
                        </div>
                        
                      </form>
                      
                    </div>
                    <div class="my-0 form-group text-right border-top">
                      <div class="col-7 mx-auto">
                        <button onclick="cetakPdf()" id="btn_pdf" type="submit" class="btn btn-danger btn-sm mt-3">Cetak PDF <i class="fa-solid fa-file-pdf"></i></button>
                      </div>
                    </div>

                </div>
              </div>

            </div>
          </div>
      </div>
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">ARGANA</a>
                for a better web.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<script>
  window.defaultUrl = `{{ url('/report_booking/') }}/`;
  
  $(document).ready(function() {
    $('.select2').select2({
      dropdownParent: $('#soundForm'),
      allowClear: true,
      width: '78%',
      placeholder: ''
    });
  });

  function cetakPdf() {
      var jenis_lap = $('#cmb_jenis').val();
      var params = $("#soundForm").serializeArray();
      var params = $.param(params);

      if (jenis_lap == 1) {
        window.open(defaultUrl + "createPdfRekapBookingPerBulan?" + params);

      } else {
          alert("Masih Dalam Pembangunan.");
          return false;
      }
  }

</script>

@endsection
