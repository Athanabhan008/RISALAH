@extends('layouts.manager.template_manager')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<style>
    .basic-datatables tbody tr.selected {
        background-color: #5897fb !important;
    }

    .basic-datatables tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Booking</a></li>
          </ol>
          <h6 class="font-weight-bolder mb-0 mt-3">Booking</h6>
        </nav>
      </div>
    </nav>
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Booking - Detail Barang</h6>
              <div style="display: flex; justify-content: space-between; align-items: center;">
                  <div class="ml-auto">
                    <button type="button" class="btn btn-info" id="btn-back">
                        <i class="fa-solid fa-arrow-left" style="margin-right: 10px;"></i> Kembali
                      </button>
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
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Client</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Booking</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Kategori</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga</th>
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
                <script>
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



  {{-- form modal --}}
  <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - Detail Barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_booking">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Kategori</span>
                        </div>
                        <select name="cmb_kategori" id="cmb_kategori" class="bg-danger"></select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Barang</span>
                        </div>
                        <select name="cmb_barang" class="custom-select" id="cmb_barang"></select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Jumlah Barang</span>
                        </div>
                        <input value="1" min="1" type="number" name="jumlah" id="jumlah" class="form-control pl-2">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Stok Barang</span>
                        </div>
                        <input type="text" name="stok_barang" id="stok_barang" class="form-control pl-2" readonly>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Harga Barang</span>
                        </div>
                        <input type="text" name="harga_barang" id="harga_barang" class="form-control pl-2" readonly>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 150px; height: 35px; background-color: rgb(222, 222, 222);">Harga Total</span>
                        </div>
                        <input type="text" name="harga_total" id="harga_total" class="form-control pl-2" readonly>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button id="btn_submit" type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>


  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script>
window.defaultUrl = `{{ url('/booking/') }}/`;

let modal = $("#formModal");

$(document).ready(function() {
    viewDatatable();



    $("#btn-back").on("click", function () {

        window.location.href = defaultUrl;

    });

    $("#btn-edit").on("click", function () {
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

        $("input[name=_type]").val("update");
        $("input[name=id]").val(selected.id);

        $("select[name=cmb_kategori]").select2("trigger", "select", {
            data: {
                id: selected.id_kategori,
                text: selected.nama_kategori
            }
        });

        $("select[name=cmb_barang]").select2("trigger", "select", {
            data: {
                id: selected.id_barang,
                text: selected.nama_barang
            }
        });

        $('#jumlah').val(selected.jumlah);
        $('#harga_barang').val(selected.harga_barang);
        $('#harga_total').val(selected.harga);


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
        let url = type == "create" ? defaultUrl + "detailCreate" : defaultUrl + "detailUpdate";

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize() + "&id_booking=" + "<?php echo $id_booking ?>",
            dataType: 'json',
            success: function(response) {

                // Tampilkan SweetAlert
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $("input[name=_type]").val("create");
                        $('#cmb_kategori').val(null).trigger('change.select2');
                        $('#cmb_barang').val(null).trigger('change.select2');
                        $('#jumlah').val(null);
                        $('#harga_barang').val(null);
                        $('#harga_total').val(null);

                        table.ajax.reload();
                    }
                });
            },
            error: function(jqXHR) {
                if (jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    let errors = jqXHR.responseJSON.errors;
                    for (let field in errors) {
                        let el = $(`[name="${field}"]`);
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
                    url: defaultUrl + "detailDelete",
                    type: 'POST',
                    data: {
                        id_booking: selected.id_booking,
                        id: selected.id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
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
                        $('#form_booking')[0].reset();
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

        window.location.href = defaultUrl + "detail_data_booking?id_booking=" + selected.id;

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
    table = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('booking/datatabledetail') }}",
            "type": "post",
            data: {
                id_booking: "<?php echo $id_booking ?>"
            },
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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
                "data": "id_booking",
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
                data: "tgl_booking",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "nama_barang",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "nama_kategori",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "jumlah",
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
        ],createdRow: function (row, data, index) {
            $(row).attr("data-value", encodeURIComponent(JSON.stringify(data)));
            if (data.selected) {
                $(row).addClass('selected');
            }
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
    });

        // Handle row selection
    $('.basic-datatables tbody').on('click', 'tr', function () {
        // Hapus kelas 'selected' dari semua baris
        $('.basic-datatables tbody tr').removeClass('selected');
        // Tambahkan kelas 'selected' pada baris yang diklik
        $(this).addClass('selected');
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
    $('select[name=cmb_kategori]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '79%',
        placeholder: '',
        ajax: {
            url: "{{ url('/booking/getKategori') }}",
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
                            text: item.nama_kategori,
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
    $('select[name=cmb_kategori]').on('select2:select', function (e) {
        // Clear the barang dropdown and enable it
        $('select[name=cmb_barang]').val(null).trigger('change');
        $('select[name=cmb_barang]').prop('disabled', false);

        // Clear the harga field
        $('#harga').val('');
    });

    $('select[name=cmb_barang]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '79%',
        placeholder: '',
        ajax: {
            url: "{{ url('/booking/getBarang') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    id_kategori: $('#cmb_kategori').val(),
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.nama_barang,
                            id: item.id,
                            harga: item.harga,
                            jumlah: item.jumlah,
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
    $('select[name=cmb_barang]').on('select2:select', function (e) {
        var data = e.params.data;
        var perkalian = $('#harga').val(data.harga);
        
        $('#stok_barang').val(data.jumlah);
        $('#harga_barang').val(data.harga);
        $('#harga_total').val(data.harga);

        if (data.jumlah < 1) {   
            Swal.fire({
                title: 'Stok barang sudah habis!',
                text: 'Tidak dapat memilih barang ini',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#btn_submit').addClass('disabled');
        } else {
            $('#btn_submit').removeClass('disabled');
        }
    });

    $('#jumlah').on('input', function (e) {
        var perkalian = $(this).val();
        var harga = $('#harga_barang').val();

        $('#harga_total').val(parseInt(harga) * parseInt(perkalian));
    });
}

</script>
  @endpush


@endsection