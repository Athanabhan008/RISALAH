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
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <div class="page-content container-fluid py-4">
        <div class="card ccard">
            <div class="card-header align-middle border-t-3 brc-primary-tp3" style="border-bottom: 1px solid #e0e5e8 !important;">
                <h4 class="card-title text-dark-m3 mt-2">
                    <i class="fa fa-money-bill-alt"></i>
                    <?php if ($user['role'] == 'admin') { ?>
                        Pencairan Data PR
                        <?php } else if (in_array($user['role'], ['super_admin', 'manager'])) { ?>
                            Approval Data PR
                    <?php } ?>
                </h4>
            </div>

            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" id="form_filter">
                            <div class="input-group mb-2">
                                <div class="input-group-prepend" style="height: 39px;">
                                    <span class="input-group-text bg-light">
                                        Periode PR
                                    </span>
                                </div>
                                <input type="text" name="periode_pr" id="periode_pr" class="form-control form-control-lg pl-3 yearmonthpicker" placeholder="Pilih periode" autocomplete="off">

                                <div class="input-group-append">
                                    <button class="btn btn-info" id="btnCekData" type="button">
                                        <i class="fa fa-arrow-right mr-1"></i>
                                        Cek Data
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row" id="dv_table">
                    <div class="col-12">
                        <div class="card mb-4">


                            <div class="card-body px-0 pt-0 pb-2">

                                <div class="text-right px-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success" id="btn_approve">
                                            <i class="fas fa-check-circle"></i>
                                            <?php if ($user['role'] == 'admin') { ?>
                                                Pencairan Data
                                                <?php } else if (in_array($user['role'], ['super_admin', 'manager'])) { ?>
                                                    Approve Data
                                            <?php } ?>
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive p-0">
                                    <table id="datatable" class="table table-striped table-bordered basic-datatables">
                                        <thead style="background-color: #1E3135; color: white;">
                                            <tr>
                                            <th style="color: white;" class="text-uppercase text-xxs font-weight-bolder opacity-7">No</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Client</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nomor PR</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit Holding</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit Leader</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit Dirutama</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit SIM</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit Keuangan</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Total Profit</th>
                                            <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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

</main>

<div id="modal_approval" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg radius-4">
        <!-- Modal content-->
        <div class="modal-content radius-4">
            <div class="modal-header btn-success radius-t-4">
                <h4 class="modal-title text-white">
                    <i class="fa fa-print text-white"></i>&nbsp;&nbsp;
                    <?php if ($user['role'] == 'admin') { ?>
                        Pencairan Data PR
                        <?php } else if (in_array($user['role'], ['super_admin', 'manager'])) { ?>
                            Approve Data PR
                    <?php } ?>
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body ace-scrollbar">
                <div class="card ccard">
                    <div class="card-header">
                        <h4 class="text-120 mb-0">
                            <?php if ($user['role'] == 'admin') { ?>
                               Apakah Anda Yakin Ingin Melakukan Pencairan?
                                <?php } else if (in_array($user['role'], ['super_admin', 'manager'])) { ?>
                                    Apakah Anda Yakin Ingin Melakukan Approve Pencairan?
                            <?php } ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer radius-b-4">
                <button type="button" id="btnAction" class="btn btn-success text-120 radius-2">
                    <i class="fa fa-save"></i>
                    Proses Data
                </button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="../../admin/assets/js/plugins/bootstrap-datepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

console.log("kipak");

window.defaultUrl = '{{ url('/pr_wapu/') }}/';

let modal = $("#formModal");
let table;

$('.yearmonthpicker').datepicker({
    format: "yyyymm",
    minViewMode: "months",
    startView: "years",
    autoclose: true
});

$(document).ready(function() {
    $('#dv_table').hide();

    $('#btnCekData').on('click', function() {
        // Simpan referensi ke tombol
        let btnCekData = $(this);
        let originalContent = btnCekData.html();

        // Tampilkan loading pada tombol
        btnCekData.html('<i class="fa fa-spinner fa-spin mr-1"></i>Loading...');
        btnCekData.prop('disabled', true);

        // Tampilkan loading SweetAlert
        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang mengambil data, harap tunggu',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        // Panggil viewDatatable dengan callback untuk menangani selesai loading
        viewDatatable(function() {
            // Restore tombol ke kondisi semula
            btnCekData.html(originalContent);
            btnCekData.prop('disabled', false);

            // Tutup loading SweetAlert
            Swal.close();
        });
    });

    $('#btn_approve').on('click', function() {
        $("#modal_approval").modal("show");
    });

    $('#btnAction').on('click', function() {

        $.ajax({
            type: "POST",
            url: "{{ route('approval/setApprove') }}",
            dataType: "JSON",
            data: $('#form_filter').serialize(),
            beforeSend: function () {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang memproses data, harap tunggu',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            },
            success: function (response) {
                Swal.close();

                Swal.fire({
                    title: 'Berhasil!',
                    text: response.message || 'Data berhasil diubah',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#modal_approval').modal('hide');
                        if (table) {
                            table.ajax.reload();
                        }
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Swal.close();

                let errorMessage = 'Terjadi kesalahan saat memproses data';

                if (jqXHR.responseJSON && jqXHR.responseJSON.message) {
                    errorMessage = jqXHR.responseJSON.message;
                } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                    errorMessage = jqXHR.responseJSON.error;
                } else if (errorThrown) {
                    errorMessage = 'Terjadi Kesalahan : ' + errorThrown;
                }

                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });

    });















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
        viewDatatable();
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


    $('select[name=cmb_laundry]').on('select2:select', function (e) {
        var data = e.params.data;
        var perkalian = $('#harga').val(data.harga);
        $('#harga').val(toRp(data.harga));
        $('#total_harga').val(toRp(data.harga));
    });

    $('#berat').on('input', function (e) {
        var perkalian = $(this).val();
        var harga = $('#harga').val().replace(/\./g, '');

        // Set default value to 1 if input is empty or less than 1
        if (!perkalian || parseInt(perkalian) < 1) {
            $(this).val(1);
            perkalian = 1;
        }

        $('#total_harga').val(toRp(parseInt(harga) * parseInt(perkalian)));
    });

    // Add blur event to handle when input loses focus
    $('#berat').on('blur', function() {
        if (!$(this).val()) {
            $(this).val(1);
            var harga = $('#harga').val();
            $('#total_harga').val(parseInt(harga) * 1);
        }
    });

    collectionS2Search();

});

function viewDatatable(callback) {
    table = $('.basic-datatables').DataTable({
        ajax: {
            url: "{{ route('approval/datatable') }}",
            "type": "post",
            "data": function (d) {
                var formData = $("#form_filter").serializeArray();
                $.each(formData, function (key, val) {
                    d[val.name] = val.value;
                });
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
                data: "profit_holding",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "profit_leader",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "profit_dirutama",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "profit_sim",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "profit_keuangan",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "total_profit",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: null,
                render: function (data, type, row, meta) {
                    // Convert to string untuk memastikan perbandingan yang konsisten
                    const isPengajuanAdmin = String(row.is_pengajuan_admin || 0);
                    const isApprove = String(row.is_approve || 0);

                    // Prioritas: is_approve lebih tinggi dari is_pengajuan_admin
                    if (isApprove === "1") {
                        return '<span class="badge badge-success">Approve</span>';
                    } else if (isPengajuanAdmin === "1") {
                        return '<span class="badge badge-warning">Pending</span>';
                    } else {
                        return '<span class="badge badge-secondary">-</span>';
                    }
                }
            }
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
            //Default
            $('#dv_table').show();
        },
        initComplete: function() {
            // Panggil callback jika ada, setelah DataTable selesai diinisialisasi
            if (typeof callback === 'function') {
                callback();
            }
        }
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
    $('select[name=cmb_nip]').select2({
        dropdownParent: $('#formFilter'),
        allowClear: true,
        width: '72.5%',
        placeholder: '',
        ajax: {
            url: "{{ url('/pr_wapu/getSales') }}",
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
                            text: item.nip,
                            name: item.name,
                            id: item.nip
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
     $('select[name=cmb_nip]').on('select2:select', function (e) {
        var data = e.params.data;

        $('#name').val(data.name);
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

</script>
  @endpush

@endsection
