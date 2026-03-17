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
                <h6>Menu</h6>
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
                    </div>
                </div>
              </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="datatable" class="table table-striped table-bordered basic-datatables">
                    <thead style="background-color: #1E3135; color: white;">
                      <tr>
                        <th style="color: white;" class="text-uppercase text-xxs font-weight-bolder opacity-7">No</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Jenis Makanan</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama Menu</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Keterangan</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Harga</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Gambar</th>
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
          <h5 class="modal-title" id="exampleModalLabel">Form - Tambah Menu</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_menu" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Jenis Menu</span>
                        </div>
                        <select class="custom-select" id="cmb_jenis" name="cmb_jenis" aria-label="Options"></select>
                      </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nama Menu</span>
                                </div>
                                <input type="text" name="nama_menu" id="nama_menu" class="form-control" style="border: 1px solid black;" required>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Harga</span>
                                </div>
                                <input type="text" name="harga" id="harga" class="form-control" style="border: 1px solid black;" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="background-color: rgb(222, 222, 222);">Keterangan</span>
                                </div>
                                <textarea class="form-control" aria-label="With textarea" name="keterangan" id="keterangan" style="border: 1px solid black;" required></textarea>
                              </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Image</span>
                                </div>
                                <input type="file" name="gambar" id="gambar" class="form-control" style="border: 1px solid black;">
                            </div>
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
                        <input type="text" name="periode_start" id="periode_start" class="form-control form-control-lg pl-3 yearmonthpicker" placeholder="Pilih Bulan (YYYYMM)" autocomplete="off">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 100px; height: 35px; background-color: rgb(222, 222, 222);">Nama Sales</span>
                        </div>
                        <select name="cmb_sales" id="cmb_sales" class="bg-danger"></select>
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

  <div class="modal fade" id="formFilterPR" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - FIlter PR</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_filterPR">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <input type="text" name="periode_start" id="periode_start" class="form-control form-control-lg pl-3 yearmonthpicker" placeholder="Pilih Bulan (YYYYMM)" autocomplete="off">
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

window.defaultUrl = '{{ url('/menu/') }}/';

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
    // Tombol tambah data (mode create)
    $('button.btn-success[data-target="#formModal"]').on('click', function() {
        // Reset form
        $('#form_menu')[0].reset();

        // Set form type to create
        $('input[name=_type]').val('create');

        // Reset select2 jenis menu
        $('select[name=cmb_jenis]').val(null).trigger('change');

        // Clear any previous error states
        resetErrors();

        // Clear hidden fields
        $('#id').val('');
    });

    $('.submit-filter').on('click', function() {
        // Reload datatable dengan filter baru
        if (table) {
            table.ajax.reload();
        } else {
            viewDatatable();
        }
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

        // Set form ke mode update dan isi dengan data terpilih
        modal.find("input[name=_type]").val("update");
        modal.find("input[name=id]").val(selected.id);

        // Isi field sesuai kolom yang ada di datatable / view
        // id_jenis -> cmb_jenis (select2)
        if (selected.id_jenis) {
            let option = new Option(selected.nama_jenis, selected.id_jenis, true, true);
            $('select[name=cmb_jenis]').append(option).trigger('change');
        }

        modal.find("input[name=nama_menu]").val(selected.nama_menu);
        modal.find("textarea[name=keterangan]").val(selected.keterangan);
        modal.find("input[name=harga]").val(selected.harga);
        modal.find("input[name=gambar]").val(selected.gambar);

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

    let formData = new FormData(this); // 🔥 ambil semua input termasuk file

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false, // wajib
        contentType: false, // wajib
        dataType: 'json',

        success: function(response) {

            $('#form_menu')[0].reset();
            table.ajax.reload();
            closeModal();

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
                    let el = $('[name="' + field + '"]');
                    el.addClass("is-invalid");
                    el.next('.invalid-feedback').text(errors[field]);
                }
            }
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
                        $('#form_menu')[0].reset();
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

    collectionS2Search();
    collectionS2SearchPR();

});

function viewDatatable() {
    table = $('.basic-datatables').DataTable({
        ajax: {
            url: "{{ route('menu/datatable') }}",
            "type": "post",
            "data": function (d) {
                var formData = $("#form_filter").serializeArray().concat($("#form_filterPR").serializeArray());
                $.each(formData, function (key, val) {
                    if (val.value !== '') {
                        d[val.name] = val.value;
                    }
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
        // Urutkan data terbaru di paling atas (berdasarkan kolom created_at / index ke-1)
        order: [[1, 'desc']],
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
                data: "nama_jenis",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "nama_menu",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "keterangan",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "harga",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "gambar",
                className: "text-center",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        // `data` berisi path seperti "foto_produk/namafile.jpg"
                        var baseUrl = "{{ asset('storage') }}";
                        return '<img src="' + baseUrl + '/' + data + '" alt="Gambar Menu" style="max-width: 80px; max-height: 80px; object-fit: cover;">';
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

// Reset state error validasi
function resetErrors() {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').text('');
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
    $('select[name=cmb_jenis]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '80%',
        placeholder: 'Pilih Jenis Menu',
        ajax: {
            url: "{{ url('/menu/getjenis') }}",
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
                            text: item.nama_jenis,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
    });
}

$('#formFilter').on('shown.bs.modal', function () {
    collectionS2Search();
});


</script>
  @endpush

@endsection
