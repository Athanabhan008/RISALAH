@extends('layouts.barang.template_barang')

@section('content')

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" integrity="sha512-xrbX64SIXOxo5cMQEDUQ3UyKsCreOEq1Im90z3B7KPoxLJ2ol/tCT0aBhuIzASfmBVdODioUdUPbt5EDEXmD9g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    .basic-datatables tbody tr.selected {
        background-color: #e9ecef !important;  /* Bootstrap gray-200 color */
    }

    .basic-datatables tbody tr:hover {
        background-color: #f8f9fa;  /* Bootstrap gray-100 color for hover */
    }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Data Barang</a></li>
          </ol>
          <h6 class="font-weight-bolder mb-0 mt-3">Data Barang</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Barang</h6>
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                  <div class="ml-auto">
                      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-plus fa-lg" style="margin-right: 10px"></i>Tambah Data
                      </button>
                      <button id="btn-edit" type="button" class="btn btn-warning" data-toggle="modal" data-target="#formModal">
                        <i class="fa-solid fa-pencil me-2"></i> Ubah Data
                      </button>
                      <a href="{{ url('/barang/detail_barang') }}" type="button" class="btn" style="background-color: #e3d42f; color: white;">
                        <i class="fa-solid fa-file me-2"></i> Detail Data
                      </a>
                  </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">

              <div class="table-responsive p-0">
                <div class="table-wrapper" style="overflow-x: auto;">
                  <table class="table table-borderless table-striped align-items-center mb-0 basic-datatables">
                    <thead style="background-color: #1E3135;">
                      <tr>
                        <th class="text-uppercase text-white text-xxs font-weight-bolder text-center">No</th>
                        <th class="text-uppercase text-white text-xxs font-weight-bolder text-center">Nama Barang</th>
                        <th class="text-center text-uppercase text-white text-xxs font-weight-bolder text-center">Harga</th>
                        <th class="text-center text-uppercase text-white text-xxs font-weight-bolder text-center">Tgl Masuk</th>
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
          <h5 class="modal-title" id="exampleModalLabel">Form - Data Barang</h5>
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
                          <span class="input-group-text" style="width: 120px; background-color: rgb(222, 222, 222);">Nama Barang</span>
                        </div>
                        <input type="text" name="nama_barang" id="nama_barang" class="input-sm form-control">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Harga</span>
                        </div>
                        <input type="text" name="harga" id="harga" class="input-sm form-control">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">TGL Masuk</span>
                        </div>
                        <input type="date" name="tgl_masuk" id="tgl_booking" class="form-control">
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
window.defaultUrl = `{{ url('/barang/') }}/`;

$(document).ready(function() {
    viewDatatable();
    collectionS2search();

    let modal = $("#formModal");


    $('#btn-ubah').on('click', function() {
        if (!$(this).hasClass('disabled')) {
            var selectedData = table.row('.selected').data();
            console.log('Selected row data:', selectedData);

            // Create simple object structure
            var dataArray = {
                'id': selectedData.id,
                'nama_barang': selectedData.nama_barang,
                'harga': selectedData.harga,
                'tgl_masuk': selectedData.tgl_masuk
            };

            window.location.href = "{{ url('/barang/ubah_data_barang') }}" + '?data_select=' + encodeURIComponent(JSON.stringify(dataArray));
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
        let url = type == "create" ? defaultUrl + "doSave" : defaultUrl + "update/" + id;

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Reset form terlebih dahulu
                $('#form_booking')[0].reset();

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

    $("#btn-edit").on("click", function () {
        let selected = table.row('.selected').data();

        console.log(selected);
        if (selected == '' || selected == null ||  selected == undefined) {
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
        modal.find("input[name=kategori]").val(selected.id_kategori);
        modal.find("input[name=nama_barang]").val(selected.nama_barang);
        modal.find("input[name=harga]").val(selected.harga);
        modal.find("input[name=tgl_masuk]").val(selected.tgl_masuk);

        modal.modal("show");
    });
});


function viewDatatable() {
    table = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('barang/datatable') }}",
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
                data: "harga",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        }).format(data);
                    }
                }
            },
            {
                data: "tgl_masuk",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            }
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
            $("td", row).last().css({
                // width: "7%",
                "text-align": "center",
            });
            //Default
            // $('td', row).eq(1).css({
            //     'text-align': 'left',
            //     'font-weight': 'normal',
            //     // width: "7%"
            // });

            // $('td', row).eq(4).css({
            //     'text-align': 'center',
            //     'font-weight': 'normal',
            // });
        }

    });

    // Handle row selection
    $('.basic-datatables tbody').on('click', 'tr', function () {
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



function closeModal() {
    $('#formModal').modal('hide');
    $('#formModal').hide();
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('body').css('padding-right', '');
}


function collectionS2search() {

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

  $('.select2-container--default .select2-selection--single').css({
      'height': '38px', // Atur tinggi sesuai kebutuhan
      'line-height': '38px' // Atur line-height agar teks terpusat
  });
}



</script>

@endpush

@endsection
