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
                    Laporan Profit Perusahaan <script> document.write(new Date().getFullYear()) </script>
                </h6>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="ml-auto d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#formModal">
                            <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Tambah Data
                        </button>

                        <div class="dropdown mr-2">
                            <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-file-invoice"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a onclick="cetakPT()" class="dropdown-item" href="#">Purchase Order PT</a>
                                <a onclick="cetakCV()" class="dropdown-item" href="#">Purchase Order CV</a>
                            </div>
                        </div>

                        <button type="button" class="btn btn-warning mr-2" id="btn-edit" data-toggle="modal" data-target="#formModal">
                            <i class="fa-solid fa-pencil" style="margin-right: 10px;"></i> Ubah Data
                        </button>
                        <button type="button" class="btn btn-danger" id="btn-delete">
                            <i class="fa-solid fa-trash" style="margin-right: 10px;"></i> Hapus Data
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
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Tanggal</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Customer</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Jenis Barang</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Revenue</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Profit</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">%Profit</th>
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
          <h5 class="modal-title" id="exampleModalLabel">Form - Invoice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_invoice">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nomor Invoice</span>
                        </div>
                        <input type="text" name="nomor_invoice" id="nomor_invoice" class="form-control" style="border: 1px solid black;" readonly>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Tanggal Invoice</span>
                          </div>
                          <input id="datepicker" type="text" class="form-control" name="tgl_inv" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
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

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Total  Vat</span>
                        </div>
                        <input type="text" name="total_vat" id="total_vat" class="form-control" style="border: 1px solid black;" readonly>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Terbilang</span>
                        </div>
                        <input type="text" name="terbilang" id="terbilang" class="form-control" style="border: 1px solid black;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" style="background-color: rgb(222, 222, 222);">Alamat</span>
                            </div>
                            <textarea class="form-control" aria-label="With textarea" name="alamat" id="alamat" style="border: 1px solid black;"></textarea>
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
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
  <script src="../../admin/assets/js/plugins/bootstrap-datepicker.js"></script>

  <script>
    $('#datepicker').datepicker({
    format: 'yyyy-mm-dd',
    minViewMode: 'date',
    autoclose: true,
    startView: 'date'
  });
</script>


<script>


console.log("kipak");

window.defaultUrl = "{{ url('/invoice/') }}/";

let modal = $("#formModal");

$(document).ready(function() {
    viewDatatable();
    collectionS2Search();

    // Auto generate nomor PR saat modal dibuka
    $('button[data-target="#formModal"]').on('click', function() {
        // Reset form
        $('#form_invoice')[0].reset();

        $('select[name=cmb_sales]').val(null).trigger('change');
        $('select[name=cmb_pr]').val(null).trigger('change');

        // Set form type to create
        $('input[name=_type]').val('create');

        // Clear any previous error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Clear hidden fields
        $('#id').val('');
        $('#nama_client').val('');
        $('#nama_projek').val('');

        // Tampilkan kembali field nomor_pr dan tombol generate saat mode create
        $('.input-group:has(#nomor_pr)').show();
        $('#btn-generate-pr').show();

        // Auto generate nomor PR
        generateNomorInv();
    });

    // Event handler untuk tombol generate PR
    $('#btn-generate-pr').on('click', function() {
        generateNomorInv();
    });

    $('.submit-filter').on('click', function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        viewDatatable();
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

        modal.find("input[name=nama_client]").val(selected.nama_client);
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
                $('#form_invoice')[0].reset();

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
                        $('#form_invoice')[0].reset();
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


    // Tambahkan event handler untuk tombol close
    $('.close, .btn-secondary').click(function() {
        closeModal();
    });

    // Event handler ketika modal akan ditutup
    $('#formModal').on('hide.bs.modal', function () {
        closeModal();
    });

});

function cetakCV() {
    let selected = table.row('.selected').data();

    window.open(defaultUrl + "cetakCV?id_invoice=" + selected.id_invoice);
}

function cetakPT() {
    let selected = table.row('.selected').data();

    window.open(defaultUrl + "cetakPT?id_invoice=" + selected.id_invoice);
}



function viewDatatable() {
    table = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('invoice/datatable') }}",
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
                data: "nomor_invoice",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "tgl_inv",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
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
                data: "total_vat",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return formatRupiah(data.toString());
                    }
                }
            },
            {
                data: "alamat",
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

// Event handler for when a barang is selected

// Fungsi untuk generate nomor PR
function generateNomorInv() {
    $.ajax({
        url: '{{ route("invoice.generate_nomor_inv") }}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#nomor_invoice').val(response.nomor_invoice);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message || 'Gagal generate nomor Invoice',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat generate nomor Invoice',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function collectionS2Search() {
    $('select[name=cmb_sales]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '83.9%',
        placeholder: '',
        ajax: {
            url: "{{ url('/invoice/getSales') }}",
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
            url: "{{ url('/invoice/getPr') }}",
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
                            total_vat: item.total_vat,
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

    // Event handler for when a barang is selected
    $('select[name=cmb_pr]').on('select2:select', function (e) {
        var data = e.params.data;

        $('#nama_client').val(data.nama_client);
        $('#nama_projek').val(data.nama_projek);
        $('#total_vat').val(formatRupiah(data.total_vat ? data.total_vat.toString() : ''));
        $('#terbilang').val(angkaTerbilang(data.total_vat ? data.total_vat.toString() : ''));
    });

    // (Opsional) Jika ingin update otomatis saat total_vat diubah manual:
    $('#total_vat').on('input', function() {
        var angka = $(this).val().replace(/[^0-9]/g, '');
        $('#terbilang').val(angkaTerbilang(angka));
    });
}

function formatRupiah(angka, prefix = '') {
    let number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   	 = number_string.split(','),
        sisa     	 = split[0].length % 3,
        rupiah     	 = split[0].substr(0, sisa),
        ribuan     	 = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        let separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix + rupiah;
}

// Hapus atau bisa dikosongkan event input berikut karena tidak akan pernah terpanggil
// document.getElementById('total_vat').addEventListener('input', function(e) {
//     let value = this.value.replace(/[^,\d]/g, '').toString();
//     if (value) {
//         this.value = formatRupiah(value, 'Rp ');
//     } else {
//         this.value = '';
//     }
// });

function toTitleCase(str) {
    return str
        .trim()
        .replace(/\s+/g, ' ')
        .split(' ')
        .map(w => w ? w.charAt(0).toUpperCase() + w.slice(1) : '')
        .join(' ');
}

function angkaTerbilang(angka) {
    function core(n) {
        var satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        if (n < 12) return satuan[n];
        if (n < 20) return core(n - 10) + " belas";
        if (n < 100) return core(Math.floor(n / 10)) + " puluh " + core(n % 10);
        if (n < 200) return "seratus " + core(n - 100);
        if (n < 1000) return core(Math.floor(n / 100)) + " ratus " + core(n % 100);
        if (n < 2000) return "seribu " + core(n - 1000);
        if (n < 1000000) return core(Math.floor(n / 1000)) + " ribu " + core(n % 1000);
        if (n < 1000000000) return core(Math.floor(n / 1000000)) + " juta " + core(n % 1000000);
        if (n < 1000000000000) return core(Math.floor(n / 1000000000)) + " miliar " + core(n % 1000000000);
        if (n < 1000000000000000) return core(Math.floor(n / 1000000000000)) + " triliun " + core(n % 1000000000000);
        return "Angka terlalu besar";
    }

    angka = angka.toString().replace(/[^0-9]/g, '');
    angka = parseInt(angka, 10);
    if (isNaN(angka) || angka === 0) return "";

    var hasil = core(angka);
    return toTitleCase(hasil);
}

</script>
  @endpush

@endsection
