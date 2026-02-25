@extends('layouts.manager.template_manager')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.bootstrap4.min.css">



<style>
    table.dataTable tbody tr.selected {
        background-color: #58a2f1 !important;
        color: white;
    }

    .select2-container {
        z-index: 9999;
    }

    .select2-dropdown {
        z-index: 9999;
    }

    table.dataTable tfoot th {
        border-top: 2px solid #3d3d3d;
        background-color: #f8f9fa !important;
        font-weight: bold;
        padding: 10px;
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>
                    LAPORAN OMSET PERUSAHAAN
                    <script> document.write(new Date().getFullYear())</script>
                </h6>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="ml-auto">
                        @if(Auth::user() && Auth::user()->role === 'super_admin' || Auth::user() && Auth::user()->role == 'admin' || Auth::user() && Auth::user()->role == 'manager')

                        <button type="button" class="btn btn-primary" id="btn-reset-filter">
                            <i class="fa-solid fa-rotate-right fa-lg" style="margin-right: 10px"></i>Reset
                        </button>

                        <button type="button" class="btn btn-warning" id="btn-filter" data-toggle="modal" data-target="#formFilter">
                            <i class="fa-solid fa-book fa-lg" style="margin-right: 10px"></i>Filter Bulan
                        </button>

                        <button type="button" class="btn btn-success" id="btn-filter" data-toggle="modal" data-target="#formFiltertahun">
                            <i class="fa-solid fa-book fa-lg" style="margin-right: 10px"></i>Filter Tahun
                        </button>

                        <button onclick="exportAllPDF()" id="btn_export_all_pdf" type="button" class="btn btn-danger mr-3">
                            <i class="fa-solid fa-file-pdf mr-2"></i>Cetak PDF
                        </button>


                        @endif
                    </div>
                </div>
              </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="datatable" class="table table-striped table-bordered basic-datatables">
                    <thead style="background-color: #1E3135; color: white;">
                      <tr>
                        <th style="color: white;" class="text-uppercase text-xxs font-weight-bolder opacity-7">No</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Tanggal DO</th>
                        <th style="display:none">Bulan</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Customer</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Jenis Barang</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nomor PR</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Revenue</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">AE</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Keterangan</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot style="background-color: #f8f9fa; font-weight: bold;">
                      <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: center;">Total:</th>
                        <th style="text-align: center;" id="total-revenue">-</th>
                      </tr>

                    </tfoot>
                  </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row gx-3 gy-3">
        <!-- VALIDASI PAYMENT -->
        <div class="card mb-4 col-md-12">
          <div class="card-header pb-0">
            <h6>Subtotal Omset</h6>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">

              </div>

              <div class="row mt-3" style="margin-right: 100px;">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">

                          <!-- Form pertama - Validasi Payment -->
                          <form id="form_update_validasi_payment" method="POST" action="{{ url('/pr_wapu/updateValidasiPayment') }}">
                              @csrf
                              <div class="row">
                                  <div class="col-6">
                                      <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                              <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Subtotal Omset</span>
                                          </div>
                                          <input type="text" class="form-control font-weight-bold text-right" id="subtotal_omset" name="subtotal_omset" value="{{ isset($validasi_payment) && $validasi_payment !== '' ? 'Rp ' . number_format($validasi_payment, 0, ',', '.') : '' }}" @if(!in_array(Auth::user()->role, ['super_admin','admin'])) @endif readonly>
                                      </div>
                                  </div>

                                  <div class="col-6">
                                      <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                              <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Achievement</span>
                                          </div>
                                          <input type="text" class="form-control font-weight-bold text-right" id="achievement" name="achievement" value="{{ isset($pph_bank_fee) && $pph_bank_fee !== '' && $pph_bank_fee !== 0 ? 'Rp ' . number_format($pph_bank_fee, 0, ',', '.') : '-' }}" readonly>
                                      </div>
                                  </div>
                              </div>

                              <div class="row">
                                <div class="col-12">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Target Omset</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" id="target_omset" name="target_omset" value="3.000.000.000,00 " @if(!in_array(Auth::user()->role, ['super_admin','admin'])) @endif readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Minus Target</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" style="color: red;" id="minus_target" name="minus_target" @if(!in_array(Auth::user()->role, ['super_admin','admin'])) @endif readonly>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style=" height: 35px; background-color: rgb(222, 222, 222);">Persentase Minus</span>
                                        </div>
                                        <input type="text" class="form-control font-weight-bold text-right" style="color: red;" id="persentase_minus" name="persentase_minus" readonly>
                                    </div>
                                </div>
                            </div>

                              @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'manager','admin']))
                              <div class="text-center">
                                  <button type="submit" class="btn btn-primary btn-sm mt-2">Simpan Perubahan</button>
                              </div>
                              @endif
                          </form>

                      </div>
                    </div>
                  </div>
                </div>


          </div>
        </div>

      </div>


      <div class="row gx-3 gy-3">
        <!-- VALIDASI PAYMENT -->
        <div class="card mb-4 col-md-12">
          <div class="card-header pb-0">
            <h6>Grafik Subtotal Omset</h6>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">

              </div>

              <div class="row mt-3" style="margin-right: 100px;">
                  <div class="col-12">
                    <div class="card">
                      <div class="card-body">

                        <canvas id="grafik-omset-pertahun" width="500" height="250"></canvas>

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
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">MBS</a>
                for a better web.
              </div>
            </div>
          </div>
        </div>
      </footer>


    </div>
  </main>

  <div class="modal fade" id="formFilter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - FIlter PR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_filter">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <input type="text" name="created_at" id="created_at" class="form-control form-control-lg pl-3 yearmonthpicker" placeholder="Pilih Bulan (YYYYMM)" autocomplete="off">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success submit-filter" data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="formFiltertahun" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form Filter Tahun</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_filter_tahun">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <input type="text" name="created_at" id="created_at" class="form-control form-control-lg pl-3 yearpicker" placeholder="Pilih Tahun (YYYY)" autocomplete="off">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success submit-filter" data-dismiss="modal">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>


  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="../../admin/assets/js/plugins/bootstrap-datepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.datatables.net/rowgroup/1.4.1/js/dataTables.rowGroup.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>


  <script>
    const ctx = document.getElementById('grafik-omset-pertahun');
    const monthLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    let omsetChart;

    // Hitung total omset per bulan dari data tabel yang sedang tampil
    function buildMonthData(rows = []) {
      const monthData = Array(12).fill(0);

      rows.forEach(function(item) {
        const amount = parseFloat(item.validasi_payment) || 0;
        const dateValue = item.created_at;

        if (!dateValue || amount <= 0) return;

        const monthIndex = moment(dateValue).month(); // 0-11
        if (monthIndex >= 0 && monthIndex < 12) {
          monthData[monthIndex] += amount;
        }
      });

      return monthData;
    }

    // Render / perbarui grafik berdasarkan data tabel
    function renderOmsetChart(rows = []) {
      const monthData = buildMonthData(rows);

      if (omsetChart) {
        omsetChart.data.datasets[0].data = monthData;
        omsetChart.update();
        return;
      }

      omsetChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: monthLabels,
          datasets: [{
            label: 'Amount',
            data: monthData,
            borderWidth: 1,
            backgroundColor: 'rgba(75, 192, 192, 0.4)',
            borderColor: 'rgba(75, 192, 192, 1)'
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
  </script>


<script>
// console.log("kipak");

window.defaultUrl = '{{ url('/omset/') }}/';

let modal = $("#formModal");
let table;
const currentYear = moment().year();

$('.yearmonthpicker').datepicker({
    format: "yyyy-mm",
    minViewMode: "months",
    startView: "years",
    autoclose: true
});

$('.yearpicker').datepicker({
    format: "yyyy",
    minViewMode: "years",
    startView: "years",
    autoclose: true
});

$(document).ready(function() {
    viewDatatable();

    // Auto generate nomor PR saat modal dibuka
    $('button[data-target="#formModal"]').on('click', function() {
        // Reset form
        $('#form_sound')[0].reset();

        // Set form type to create
        $('input[name=_type]').val('create');

        // Clear any previous error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Clear hidden fields
        $('#id').val('');
        $('#nama_projek').val('');

        // Tampilkan kembali field nomor_pr dan tombol generate saat mode create
        $('.input-group:has(#nomor_pr)').show();
        $('#btn-generate-pr').show();

        // Auto generate nomor PR
        generateNomorPR();
    });

    $('.submit-filter').on('click', function() {
        // Jika submit dari form filter bulan, reset filter tahun
        if ($(this).closest('#form_filter').length > 0) {
            $('#form_filter_tahun input[name="created_at"]').val('');
        }
        // Jika submit dari form filter tahun, reset filter bulan
        if ($(this).closest('#form_filter_tahun').length > 0) {
            $('#form_filter input[name="created_at"]').val('');
        }

        // Reload datatable dengan filter baru
        if (tableDetail) {
            tableDetail.ajax.reload(null, false); // false = tetap di halaman saat ini
        } else {
            viewDatatable();
        }
    });

    // Reset filter
    $('#btn-reset-filter').on('click', function() {
        $('#form_filter')[0].reset();
        $('#form_filter_tahun')[0].reset();
        $('#cmb_sales').val(null).trigger('change');
        $('#form_filter input[name="created_at"]').val('');
        $('#form_filter_tahun input[name="created_at"]').val('');

        // Reload datatable tanpa filter
        if (tableDetail) {
            tableDetail.ajax.reload(null, false);
        } else {
            viewDatatable();
        }
    });


// Fungsi Export Semua Data (tanpa filter)
window.exportAllPDF = function() {
    // Ambil tahun dari form filter
    const yearValue = $('#form_filter_tahun input[name="created_at"]').val();
    let url = "{{ route('omset/cetakpdf') }}";
    if (yearValue) {
        url += '?created_at=' + encodeURIComponent(yearValue);
    }
    window.open(url, '_blank');
}
    // Tambahkan event handler untuk tombol close
    $('.close, .btn-secondary').click(function() {
        closeModal();
    });

    // Event handler ketika modal akan ditutup
    $('#formModal').on('hide.bs.modal', function () {
        closeModal();
    });

    collectionS2Search();

});

function collectionS2Search() {
    $('select[name=cmb_sales]').select2({
        dropdownParent: $('#formFilter'),
        allowClear: true,
        width: '72.5%',
        placeholder: '',
        ajax: {
            url: "{{ url('/omset/getSales') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        }
    });

     // Event handler for when kategori changes
     $('select[name=cmb_sales]').on('select2:select', function (e) {
        var data = e.params.data;

        $('#cmb_sales').val(data.id);
    });
}

let tableDetail;
function viewDatatable() {
    tableDetail = $(".basic-datatables").DataTable({
        scrollY: '400px',
        scrollX: true,
        pageLength: 10,
        paging: false,
        serverSide: false,
        processing: false,

        ajax: {
            url: "{{ route('omset/datatable') }}",
            type: "post",
            data: function (d) {
                // Ambil data dari form filter bulan
                var filterBulan = $('#form_filter input[name="created_at"]').val();
                // Ambil data dari form filter tahun
                var filterTahun = $('#form_filter_tahun input[name="created_at"]').val();

                // Prioritaskan filter bulan jika ada, jika tidak gunakan filter tahun
                if (filterBulan) {
                    d['created_at'] = filterBulan;
                } else if (filterTahun) {
                    d['created_at'] = filterTahun;
                }

                var selectedSales = $('#cmb_sales').val();
                if (selectedSales) {
                    d['cmb_sales'] = selectedSales;
                }
                d['_token'] = '{{ csrf_token() }}';
            },
            dataSrc: function (json) {
                // Jika ada filter, return semua data yang sudah difilter di server
                // Jika tidak ada filter, tampilkan data tahun berjalan
                var filterBulan = $('#form_filter input[name="created_at"]').val();
                var filterTahun = $('#form_filter_tahun input[name="created_at"]').val();
                if (filterBulan || filterTahun) {
                    return json || [];
                } else {
                    // Tampilkan hanya data pada tahun berjalan di sisi client
                    return (json || []).filter(function (row) {
                        return moment(row.created_at).year() === currentYear;
                    });
                }
            }
        },

        dom: 't',
        bInfo: false,
        destroy: true,
        responsive: true,
        select: { style: 'single' },
        aaSorting: [],
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        initComplete: function () {
            const rows = this.api().rows({ filter: 'applied' }).data().toArray();
            renderOmsetChart(rows);
        },
        drawCallback: function () {
            const rows = this.api().rows({ filter: 'applied' }).data().toArray();
            renderOmsetChart(rows);
        },

        footerCallback: function (row, data, start, end, display) {
            var api = this.api();

            // Menghitung total Revenue dan Profit dari raw data
            var totalRevenue = 0;
            var totalProfit = 0;

            // Loop melalui semua data di halaman saat ini
            api.rows({page: 'current'}).data().each(function (rowData) {
                // Hitung total Revenue dari validasi_payment
                var revenue = parseFloat(rowData.validasi_payment) || 0;
                if (revenue > 0) {
                    totalRevenue += revenue;
                }

                // Hitung total Profit dari gross_provit
                var profit = parseFloat(rowData.gross_provit) || 0;
                if (profit > 0) {
                    totalProfit += profit;
                }
            });

            // Update footer dengan total yang sudah diformat
            var formattedRevenue = totalRevenue > 0 ? 'Rp ' + formatRupiahWithDots(totalRevenue.toString(), '') : '-';
            $(api.column(5).footer()).html(formattedRevenue);
            $(api.column(6).footer()).html(totalProfit > 0 ? 'Rp ' + formatRupiahWithDots(totalProfit.toString(), '') : '-');

            // Hitung persentase: total-profit dibagi total-revenue
            var totalPersentase = '-';
            if (totalRevenue > 0 && totalProfit > 0) {
                var persentase = (totalProfit / totalRevenue) * 100;
                totalPersentase = parseFloat(persentase.toFixed(2)) + '%';
            }
            $(api.column(7).footer()).html(totalPersentase);

            // PEMBAGIAN ANTARA KOLOM SUBTOTAL OMSET DENGAN TARGET OMSET 3.000.000.000
            if (formattedRevenue === '-') {
                $('#subtotal_omset').val('');
                $('#achievement').val('-');
                $('#minus_target').val('');
                $('#persentase_minus').val('-');
            } else {
                $('#subtotal_omset').val(formattedRevenue);

                var achievementPercentage = (totalRevenue / 3000000000) * 100;
                var formattedAchievement = achievementPercentage > 0 ? achievementPercentage.toFixed(2) + '%' : '-';
                $('#achievement').val(formattedAchievement);

                // Hitung minus target
                var minusTarget = totalRevenue - 3000000000;
                var formattedMinusTarget = 'Rp ' + formatRupiahWithDots(minusTarget.toString(), '');
                $('#minus_target').val(formattedMinusTarget);

                // Perhitungan persentase minus: (minusTarget / 3.000.000.000)*100
                var persentaseMinus = ((minusTarget / 3000000000) * 100);
                var persentaseMinusText = persentaseMinus !== 0 ? persentaseMinus.toFixed(2) + '%' : '-';
                $('#persentase_minus').val(persentaseMinusText);
            }
        },
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        columns: [
            {
                "data": "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1 + ".";
                }
            },
            {
                data: "created_at",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return moment(data).format('DD-MM-YYYY');
                    }
                }
            },
            {
                data: "created_at",
                visible: false,
                render: function (data, type, row, meta) {
                    if (!data) return '-';
                    return moment(data).format('MMMM');
                }
            },
            {
                data: "nama_client",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "nama_projek",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "nomor_pr",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "validasi_payment",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null || data == undefined || data == 0) {
                        return '-';
                    } else {
                        // Konversi ke number dan format sebagai rupiah
                        let numberValue = parseFloat(data) || 0;
                        if (numberValue == 0) {
                            return '-';
                        }
                        return 'Rp ' + formatRupiahWithDots(numberValue.toString(), '');
                    }
                }
            },
            {
                data: "name",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },

            {
                data: "-",
                render: function (data, type, row, meta) {

                    // Cek nilai pada kolom validasi_payment
                    let payment = row.validasi_payment;

                    // Kondisi belum payment
                    if (payment == '' || payment == null || payment == undefined || payment == 0) {
                        return '<span class="badge badge-danger">Pend Invoice</span>';
                    }

                    // Kondisi sudah payment
                    return '<span class="badge badge-success">Sudah Payment</span>';
                }
            },
        ],
            createdRow: function (row, data, index) {
                $(row).attr("data-value", encodeURIComponent(JSON.stringify(data)));
                $("thead").css({
                    "vertical-align": "middle",
                    "text-align": "center",
                });
                $("td", row).css({
                    "vertical-align": "middle",
                    padding: "0.5em",
                    cursor: "pointer",
                });
                $("td", row).first().css({
                    width: "2%",
                    "text-align": "center",
                });
                $("td", row).eq(2).css({
                    "text-align": "center",
                    "font-weight": "normal",
                });
                $("td", row).eq(3).css({
                    "text-align": "center",
                    "font-weight": "normal",
                });
                $("td", row).eq(4).css({
                    "text-align": "center",
                    "font-weight": "normal",
                    width: "5%",
                });
                // $("td", row).last().css({ width: "7%", "text-align": "center", });
                //Default
            },
        })
        .on("select", function (e, dt, type, indexes) {
            var rowData = table.row(indexes).data();
            $("#btn-edit").removeClass("disabled");
            $("#btn-delete").removeClass("disabled");
            alert('1');
        })
        .on("deselect", function (e, dt, type, indexes) {
            $("#btn-edit").addClass("disabled");
            $("#btn-delete").addClass("disabled");
            alert('0');
        });

            // Handle row selection
    // Pindahkan event handler ini ke sini, dan gunakan .off() untuk mencegah duplikasi
    $('.basic-datatables tbody').off('click', 'tr').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#btn-ubah').addClass('disabled');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#btn-ubah').removeClass('disabled');
        }
    });
}


// Tambahkan fungsi helper untuk handle modal
function closeModal() {
    $('#formModal').modal('hide');
    $('#formModal').hide();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}

// Tambahkan fungsi helper di bagian atas script
function showNotification(type, message) {
    Swal.fire({
        title: type.charAt(0).toUpperCase() + type.slice(1),
        text: message,
        icon: type, // 'success', 'error', 'warning', 'info'
        confirmButtonText: 'OK'
    });
}

// Fungsi format rupiah dengan titik sebagai pemisah ribuan
function formatRupiahWithDots(angka, prefix = '') {
    if (!angka || angka === null || angka === undefined) {
        return prefix + '0';
    }

    let isNegative = false;
    let number_string = angka.toString();

    if (number_string[0] === '-') {
        isNegative = true;
        number_string = number_string.slice(1);
    }

    // Pastikan desimal titik (.) dari hasil perhitungan diubah ke koma (,)
    number_string = number_string.replace(/\./g, ',');

    number_string = number_string.replace(/[^,\d]/g, '');
    let split = number_string.split(',');
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return (isNegative ? '-' : '') + prefix + rupiah;
}

</script>
  @endpush

@endsection
