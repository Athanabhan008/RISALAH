@extends('layouts.barang.template_barang')

@section('content')

<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>

</style>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Penyesuaian Stok</a></li>
          </ol>
          <h6 class="font-weight-bolder mb-0 mt-3">Penyesuaian Stok</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6>Penyesuaian Stok</h6>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                  <div class="ml-auto">

                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Tambah Data
                      </button>

                      <button id="btn-edit" type="button" class="btn btn-warning" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-pencil me-2"></i> Ubah Data
                      </button>

                      <button id="btn-delete" type="button" class="btn btn-danger">
                        <i class="fa-solid fa-trash me-2"></i> Batalkan
                      </button>

                  </div>
                </div>
              </div>

              <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                  <div class="table-wrapper" style="overflow-x: auto;">
                    <table id="datatable" class="table table-striped table-bordered align-items-center mb-0">
                    <thead style="background-color: #1E3135;">
                        <tr>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7">No</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Penyesuaian</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7">Keterangan</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7">Jenis Penyesuaian</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7">Jumlah Penyesuaian</th>
                          <th class="text-center text-uppercase text-white text-xxs font-weight-bolder opacity-7">Stok Akhir</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
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
                ©<script>
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


  <!-- Modal -->
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form - Penyesuaian Stok</h5>
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
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Kategori</span>
                        </div>
                        <select name="kategori" id="kategori"></select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Barang</span>
                        </div>
                        <select name="cmb_barang" id="cmb_barang"></select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Jenis</span>
                        </div>
                        <select name="cmb_jenis" id="cmb_jenis" class="select2">
                          <option value=""></option>
                          <option value="Penambahan">Penambahan</option>
                          <option value="Pengurangan">Pengurangan</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Jumlah</span>
                        </div>
                        <input type="text" name="jumlah" id="jumlah" class="form-control">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Keterangan</span>
                        </div>
                        <input type="text" name="keterangan" id="keterangan" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>

<script>
window.defaultUrl = `{{ url('/stok_barang/') }}/`;

$(document).ready(function() {
    viewDatatable();
    collectionS2search();

    $('#btn-edit').addClass('disabled');
    $('#btn-delete').addClass('disabled');

    let modal = $("#formModal");

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

        $("input[name=_type]").val("update");
        $("input[name=id]").val(selected.id);

        $("select[name=kategori]").select2("trigger", "select", {
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

        $("select[name=cmb_jenis]").select2("trigger", "select", {
            data: {
                id: selected.jenis,
                text: selected.jenis
            }
        });

        $('#jumlah').val(selected.jml_penyesuaian);
        $('#keterangan').val(selected.keterangan);


        modal.modal("show");
    });

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
            text: "Stok barang akan kembali ke jumlah stok sebelumnya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim request delete
                $.ajax({
                    url: defaultUrl + "delete",
                    type: 'POST',
                    data: {
                        id: selected.id,
                        _token: '{{ csrf_token() }}'
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


    modal.find("form").on("submit", function(ev) {
        ev.preventDefault();

        let submitButton = $(this).find("[type=submit]");
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop("disabled", true);

        let type = $("[name=_type]").val();
        let id = $("[name=id]").val();
        let url = type == "create" ? defaultUrl + "create" : defaultUrl + "update";

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('#formModal').modal('hide');

                // Tampilkan SweetAlert
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $("input[name=_type]").val("create");
                        $('#kategori').val(null).trigger('change.select2');
                        $('#cmb_barang').val(null).trigger('change.select2');
                        $('#jumlah').val(null);
                        $('#cmb_jenis').val(null).trigger('change.select2');
                        $('#keterangan').val(null);

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

});

function viewDatatable() {
    table = $("#datatable").DataTable({
        ajax: {
            url: defaultUrl + "datatable",
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
                data: "tgl",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null || data == '0000-00-00') {
                        return '-';
                    } else {
                        return (moment(data).format('DD MMMM YYYY')) + ' <br> ' + row.waktu;
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
                data: "jenis",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "jml_penyesuaian",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "stok_akhir",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
        ],
        "createdRow": function (row, data, index) {
            $(row).attr('data-value', encodeURIComponent(JSON.stringify(data)));
            $("thead").css({
                "vertical-align": "middle",
                "text-align": "center",
            });
            $("td", row).css({
                "vertical-align": "middle",
                padding: "0.5em",
                'cursor': 'pointer'
            });
            $("td", row).first().css({
                width: "2%",
                "text-align": "center",
            });
            $("td", row).eq(5).css({
                // width: "7%",
                "text-align": "right",
            });
            $("td", row).eq(6).css({
                // width: "7%",
                "text-align": "right",
            });
            //Default
            $('td', row).eq(1).css({
                'text-align': 'center',
                'font-weight': 'normal',
                // width: "7%"
            });

            // $('td', row).eq(4).css({
            //     'text-align': 'center',
            //     'font-weight': 'normal',
            // });
        }

    });

    // Handle row selection
    $('#datatable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#btn-edit').addClass('disabled');
            $('#btn-delete').addClass('disabled');
        } else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#btn-edit').removeClass('disabled');
            $('#btn-delete').removeClass('disabled');
        }
    });


}


function collectionS2search() {

    $('.select2').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '72%',
        placeholder: ''
    });

  $('select[name=kategori]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '72%',
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

  $('select[name=cmb_barang]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '72%',
        placeholder: '',
        ajax: {
            url: "{{ url('/booking/getBarang') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    id_kategori: $('#kategori').val(),
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
                            harga: item.harga
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




  $('.select2-container--default .select2-selection--single').css({
      'height': '38px', // Atur tinggi sesuai kebutuhan
      'line-height': '38px' // Atur line-height agar teks terpusat
  });
}


</script>

@endsection
