@extends('layouts.manager.template_manager')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

    /* Custom styling untuk datepicker yang lebih besar */
    .datepicker {
        font-size: 16px !important;
    }

    .datepicker table {
        font-size: 16px !important;
    }

    .datepicker table tr td,
    .datepicker table tr th {
        padding: 8px 12px !important;
        font-size: 16px !important;
    }

    .datepicker table tr td.day {
        width: 40px !important;
        height: 40px !important;
        line-height: 10px !important;
    }

    .datepicker table tr td.day:hover {
        background-color: #e9ecef !important;
    }

    .datepicker table tr td.active {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }

    .datepicker table tr td.active:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .datepicker table tr td.today {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #212529 !important;
    }

    .datepicker-dropdown {
        padding: 10px !important;
        border-radius: 8px !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
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
                <h6>Quality Control</h6>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="ml-auto">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#formModal">
                            <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Tambah Data
                          </button>
                          <button type="button" class="btn btn-warning" id="btn-edit" data-toggle="modal" data-target="#formModal">
                            <i class="fa-solid fa-pencil" style="margin-right: 10px;"></i> Ubah Data
                          </button>
                          <button type="button" class="btn btn-danger" id="btn-delete">
                            <i class="fa-solid fa-trash" style="margin-right: 10px;"></i> Hapus Data
                          </button>
                          <button type="button" class="btn btn-info" id="btn-detail">
                            <i class="fa-solid fa-arrow-right" style="margin-right: 10px;"></i> Tambah Barang
                          </button>
                    </div>
                </div>
              </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="datatable" class="table table-striped table-bordered basic-datatables">
                    <thead style="background-color: #1E3135; color: white;">
                      <tr>
                        <th style="color: white;" class="text-uppercase text-xxs font-weight-bolder opacity-7">No</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Client</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Projek</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Tanggal QC</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - QC</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_qc">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Tanggal QC</span>
                          </div>
                          <input id="datepicker" type="text" class="form-control" name="tgl_qc" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nama Sales</span>
                          </div>
                          <select name="cmb_sales" id="cmb_sales" class="bg-danger"></select>
                    </div>

                    <div class="input-group-prepend">
                        <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nomor PR</span>
                        <select name="cmb_pr" id="cmb_pr" class="bg-danger"></select>
                    </div>

                    <div class="row mt-4">

                        <div class="col-md-6 mb-3">
                            <label for="Unit_price" class="form-label">Nama Client</label>
                            <input type="text" class="form-control" id="nama_client" name="nama_client" style="border: 1px solid black;" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="vendor" class="form-label">Nama Projek</label>
                            <input type="text" class="form-control" id="nama_projek" name="nama_projek" style="border: 1px solid black;" readonly>
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

  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
  <script src="../../admin/assets/js/plugins/bootstrap-datepicker.js"></script>

<script>
    $('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    viewMode: 'date',
    minViewMode: 'date',
    autoclose: true,
    startView: 'date'
  });
</script>


<script>

console.log("kipak");

window.defaultUrl = '{{ url('/qc/') }}/';

let modal = $("#formModal");
let table;

$(document).ready(function() {
    viewDatatable();

    // Auto generate nomor PR saat modal dibuka
    $('button[data-target="#formModal"]').on('click', function() {
        // Reset form
        $('#form_qc')[0].reset();

        $('select[name=cmb_sales]').val(null).trigger('change');
        $('select[name=cmb_pr]').val(null).trigger('change');

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

        $("select[name=cmb_sales]").select2("trigger", "select", {
            data: {
                id: selected.id_sales,
                text: selected.nama_sales
            }
        });
        $('select[name=cmb_pr]').val(null).trigger('change');

        modal.find("input[name=nama_projek]").val(selected.nama_projek);
        modal.find("input[name=nomor_pr]").val(selected.nomor_pr);

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
                $('#form_qc')[0].reset();

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
                        $('#cmb_sales').val(null).trigger('change.select2');
                        $('#cmb_pr').val(null).trigger('change.select2');
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
                        $('#form_qc')[0].reset();
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

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        window.location.href = defaultUrl + "detail_data_qc?id_qc=" + selected.id_qc;

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

function viewDatatable() {
    table = $('.basic-datatables').DataTable({
        ajax: {
            url: "{{ route('qc/datatable') }}",
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
                data: "tgl_qc",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
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
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '83.9%',
        placeholder: '',
        ajax: {
                url: "{{ url('/qc/getSales') }}",
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

        // Event handler for when sales changes
        $('select[name=cmb_sales]').on('select2:select', function (e) {
            // Clear the barang dropdown and enable it
            $('select[name=cmb_pr]').val(null).trigger('change');
            $('select[name=cmb_pr]').prop('disabled', false);

            // Clear the harga field
            $('#harga').val('');
        });

        $('select[name=cmb_pr]').select2({
            dropdownParent: $('#formModal'),
            allowClear: true,
            width: '83.9%',
            placeholder: '',
            ajax: {
                url: "{{ url('/qc/getPr') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        id_sales: $('#cmb_sales').val(),
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.data, function (item) {
                            return {
                                text: item.nomor_pr,
                                nama_client: item.nama_client,
                                nama_projek: item.nama_projek,
                                id: item.id,
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

        // setelah inisialisasi $('select[name=cmb_pr]').select2({...})
        $('select[name=cmb_pr]').on('select2:select', function (e) {
            const data = e.params.data;
            $('#nama_client').val(data.nama_client || '');
            $('#nama_projek').val(data.nama_projek || '');
        });

        // opsional: kosongkan jika di-clear
        $('select[name=cmb_pr]').on('select2:clear', function () {
            $('#nama_client').val('');
            $('#nama_projek').val('');
        });

        // saat sales berubah, selain reset cmb_pr, juga kosongkan field
        $('select[name=cmb_sales]').on('select2:select', function () {
            $('select[name=cmb_pr]').val(null).trigger('change');
            $('select[name=cmb_pr]').prop('disabled', false);
            $('#nama_client').val('');
            $('#nama_projek').val('');
        });
    }

</script>
  @endpush

@endsection
