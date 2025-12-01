@extends('layouts.manager.template_manager')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">


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
                            <i class="fa-solid fa-book fa-lg" style="margin-right: 10px"></i>Filter Omset
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

  <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - Data Projek</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_sound">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nama Client</span>
                        </div>
                        <input type="text" name="nama_client" id="nama_client" class="form-control" style="border: 1px solid black;" required>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nama Projek</span>
                        </div>
                        <input type="text" name="nama_projek" id="nama_projek" class="form-control" style="border: 1px solid black;" required>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="inputGroupSelect01">Jenis PR</label>
                        </div>
                        <select class="custom-select" name="jenis_pr" id="inputGroupSelect01" required>
                          <option selected>Choose...</option>
                          <option value="pemerintah">Pemerintah</option>
                          <option value="swasta">Swasta</option>
                          <option value="non_ppn">Non PPN</option>
                        </select>
                      </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nomor PR</span>
                        </div>
                        <input type="text" name="nomor_pr" id="nomor_pr" class="form-control" style="border: 1px solid black;" readonly>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-outline-secondary" id="btn-generate-pr">
                            <i class="fa fa-refresh"></i> Generate
                          </button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>


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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script>

console.log("kipak");

window.defaultUrl = '{{ url('/omset/') }}/';

let modal = $("#formModal");
let table;

$('.yearmonthpicker').datepicker({
    format: "yyyy-mm",
    minViewMode: "months",
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
        // Reload datatable dengan filter baru
        if (table) {
            table.ajax.reload(null, false); // false = tetap di halaman saat ini
        } else {
            viewDatatable();
        }
    });

    // Reset filter
    $('#btn-reset-filter').on('click', function() {
        $('#form_filter')[0].reset();
        $('#cmb_sales').val(null).trigger('change');
        $('#created_at').val('');

        // Reload datatable tanpa filter
        if (table) {
            table.ajax.reload(null, false);
        } else {
            viewDatatable();
        }
    });

    $('#btn-generate-pr').on('click', function() {
        generateNomorPR();
    });

    $('select[name=cmb_laundry').on('select2:select', function (e) {
        var data = e.params.data;
        // alert(data)
        $('#harga').val(data.harga);
    });

    $("#btn-edit").on("click", function () {
        let selected = table.row('.selected').data();

        console.log(selected);
        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        modal.find("input[name=_type]").val("update");
        modal.find("input[name=id]").val(selected.id);
        modal.find("input[name=nama_projek]").val(selected.nama_projek);
        modal.find("input[name=nama_client]").val(selected.nama_client);
        modal.find("select[name=jenis_pr]").val(selected.jenis_pr);

        // Sembunyikan field nomor_pr dan tombol generate saat mode edit
        $('.input-group:has(#nomor_pr)').hide();
        $('#btn-generate-pr').hide();

        resetErrors();
        modal.modal("show");
    });


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    modal.find("form").on("submit", function(ev) {
        ev.preventDefault();

        let submitButton = $(this).find("[type=submit]");
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop("disabled", true);

        let type = $("[name=_type]").val();
        let id = $("[name=id]").val();
        let url = type == "create" ? defaultUrl + "create" : defaultUrl + "update/" + id;

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Reset form terlebih dahulu
                $('#form_sound')[0].reset();

                // Reload table
                table.ajax.reload();

                // Tutup modal menggunakan helper function
                closeModal();

                // Tampilkan SweetAlert
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        table.ajax.reload();
                    }
                });
            },
            error: function(jqXHR) {
                if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    let errors = jqXHR.responseJSON.errors;
                    for (let field in errors) {
                        let el = $([name="${field}"]);
                        el.addClass("is-invalid");
                        el.next('.invalid-feedback').text(errors[field]);
                    }
                }
                alert('Terjadi kesalahan saat menyimpan data');
            },
            complete: function() {
                submitButton.html(originalContent);
                submitButton.prop("disabled", false);
            }
        });
    });

    // Tambahkan event handler untuk tombol delete
    $("#btn-delete").on("click", function () {
        let selected = table.row('.selected').data();

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        // Konfirmasi delete dengan SweetAlert
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim request delete
                $.ajax({
                    url: defaultUrl + "delete/" + selected.id,
                    type: 'POST',
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    success: function(response) {
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    table.ajax.reload();
                                }
                            });
                        } else {
                            alert(response.message);
                            table.ajax.reload();
                        }
                        $('#form_sound')[0].reset();
                    },
                    error: function(jqXHR) {
                        let message = 'Terjadi kesalahan saat menghapus data';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                            message = jqXHR.responseJSON.message;
                        }
                        Swal.fire('Error!', message, 'error');
                    }
                });
            }
        });
    });


    $("#btn-detail").on("click", function () {
        let selected = table.row('.selected').data();

        if (_.isEmpty(selected) || selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (selected.jenis_pr === 'pemerintah') {
            window.location.href = defaultUrl + "detail_data_prwapu?id_projek=" + selected.id;
        } else if (selected.jenis_pr === 'swasta') {
            window.location.href = defaultUrl + "detail_data_swasta?id_projek=" + selected.id;
        } else if (selected.jenis_pr === 'non_ppn') {
            window.location.href = defaultUrl + "detail_data_non_ppn?id_projek=" + selected.id;
        }  else {
            Swal.fire({
                title: 'Peringatan',
                text: 'Jenis PR tidak dikenali!',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    });



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

function viewDatatable() {
    table = $('.basic-datatables').DataTable({
        ajax: {
            url: "{{ route('omset/datatable') }}",
            "type": "post",
            "data": function (d) {
                var formData = $("#form_filter").serializeArray();
                $.each(formData, function (key, val) {
                    d[val.name] = val.value;
                });
                // Pastikan cmb_sales dikirim dengan benar
                var selectedSales = $('#cmb_sales').val();
                if (selectedSales) {
                    d['cmb_sales'] = selectedSales;
                }
                d['_token'] = '{{ csrf_token() }}';
            }
        },
        dom: 't<"d-flex justify-content-end mt-3"p>',
        pagingType: "simple_numbers",
        "bInfo" : false,
        destroy: true,
        serverSide: true,
        processing: true,
        responsive: true,
        select: {
            style: 'single'
        },
        aaSorting: [],
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
            $(api.column(5).footer()).html(totalRevenue > 0 ? 'Rp ' + formatRupiahWithDots(totalRevenue.toString(), '') : '-');
            $(api.column(6).footer()).html(totalProfit > 0 ? 'Rp ' + formatRupiahWithDots(totalProfit.toString(), '') : '-');

            // Hitung persentase: total-profit dibagi total-revenue
            var totalPersentase = '-';
            if (totalRevenue > 0 && totalProfit > 0) {
                var persentase = (totalProfit / totalRevenue) * 100;
                totalPersentase = parseFloat(persentase.toFixed(2)) + '%';
            }
            $(api.column(7).footer()).html(totalPersentase);
        },
        columnDefs: [{
            searchable: false,
            targets: [0]
        }],
        columns: [{
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

function collectionS2Search() {
    $('select[name=cmb_sales]').select2({
        dropdownParent: $('#formFilter'),
        allowClear: true,
        width: '72.5%',
        placeholder: 'Pilih Sales...',
        ajax: {
            url: "{{ url('/profit/getSales') }}",
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

     // Event handler for when sales dipilih
     $('select[name=cmb_sales]').on('select2:select', function (e) {
        var data = e.params.data;
        $('#cmb_sales').val(data.id);
    });

     // Event handler for when sales di-clear
     $('select[name=cmb_sales]').on('select2:clear', function (e) {
        $('#cmb_sales').val('');
    });
}


// Event handler for when a barang is selected

// Fungsi untuk generate nomor PR
function generateNomorPR() {
    $.ajax({
        url: '{{ route("pr_wapu.generate_nomor_pr") }}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#nomor_pr').val(response.nomor_pr);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message || 'Gagal generate nomor PR',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat generate nomor PR',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
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
