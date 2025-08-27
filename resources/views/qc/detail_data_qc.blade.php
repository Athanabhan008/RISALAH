@extends('layouts.manager.template_manager')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css">
<style>
  :root {
    --main-bg: #f8fafc;
    --card-bg: #fff;
    --primary: #5897fb;
    --secondary: #b3b3b4;
    --soft-shadow: 0 2px 12px rgba(0,0,0,0.07);
    --border-radius: 1rem;
  }
  body, main {
    background: var(--main-bg) !important;
  }
  .card {
    border-radius: var(--border-radius);
    box-shadow: var(--soft-shadow);
    border: none;
    margin-bottom: 1.5rem;
    background: var(--card-bg);
  }
  .card-header {
    background: linear-gradient(90deg, #e0e7ff 0%, #f8fafc 100%);
    border-radius: var(--border-radius) var(--border-radius) 0 0;
    border-bottom: none;
    padding: 1rem 1.5rem;
  }
  .btn {
    border-radius: 0.5rem;
    transition: background 0.2s, color 0.2s;
    font-weight: 500;
  }
  .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    vertical-align: middle !important;
    font-size: 0.98rem;
  }
  .badge-status {
    font-size: 0.9em;
    padding: 0.4em 0.7em;
    border-radius: 0.5em;
  }
  .badge-approve {
    background: #d1fae5;
    color: #065f46;
  }
  .badge-need {
    background: #fef3c7;
    color: #92400e;
  }
  @media (max-width: 991.98px) {
    .card-header > div {
      flex-wrap: wrap !important;
      gap: 0.5rem;
    }
    .btn, .input-group-text, .form-control {
      font-size: 0.95rem;
      padding: 0.4rem 0.7rem;
    }
    .modal-dialog {
      max-width: 95vw;
      margin: 1.75rem auto;
    }
  }
  @media (max-width: 767.98px) {
    .table thead {
      font-size: 12px;
    }
    .table td, .table th {
      font-size: 12px;
      padding: 0.3rem;
    }
    .card .card-header h6 {
      font-size: 1rem;
    }
    .modal-dialog {
      max-width: 98vw;
      margin: 0.5rem auto;
    }
  }
  .table-responsive::-webkit-scrollbar {
    height: 6px;
    background: #f1f1f1;
  }
  .table-responsive::-webkit-scrollbar-thumb {
    background: var(--secondary);
    border-radius: 3px;
  }
</style>
@endpush

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">

        <!-- BARANG/PRODUK -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Quality Control</h6>

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
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Part Number</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
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
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">MBS</a>
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
          <h5 class="modal-title" id="exampleModalLabel">Form - Barang</h5>
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
                    <input type="hidden" id="id_pr" value="{{ $qc->id_pr }}">

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Barang</span>
                                  </div>
                                  <select name="cmb_barang" id="cmb_barang" class="bg-danger"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Serial Number</label>
                                <textarea class="form-control" id="serial_number" name="serial_number" rows="3"></textarea>
                              </div>
                        </div>
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
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script>
window.defaultUrl = `{{ url('/qc/') }}/`;

let modal = $("#formModal");
let modalCogs = $("#formCogs");
let baseCostCogs = 0;
let tableDetail; // untuk tabel utama
let tableCogs;   // untuk tabel cogs

$(document).ready(function() {
    viewDatatable();
    collectionS2Search();

    $('#btn-edit-cogs').prop('disabled', true);

    $("#btn-back").on("click", function () {
        window.location.href = defaultUrl;
    });


      // Reset select2
      $('select[name=cmb_barang]').val(null).trigger('change');

      $('select[name=cmb_barang').on('select2:select', function (e) {
        var data = e.params.data;
        // alert(data)
        $('#total_harga').val(data.harga);
    });

    $("#btn-edit").on("click", function () {
        let selected = tableDetail.row('.selected').data();

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
        $("select[name=serial_number]").val(selected.serial_number);



        $("select[name=cmb_barang]").select2("trigger", "select", {
            data: {
                id: selected.id_pr,
                text: selected.partnumber_description
            }
        });

        $('#serial_number').val(selected.serial_number);
        // $('#part_number').val(selected.part_number);
        // $('#partnumber_description').val(selected.partnumber_description);
        // $('#Unit_price').val(selected.unit_price);
        // $('#total_price').val(selected.total_price);
        // $('#qty').val(selected.qty);
        // $('#vendor_price').val(selected.vendor_price);
        // $('#unit_price_cv').val(selected.unit_price_cv);
        // $('#total_po_cv').val(selected.total_po_cv);
        // $('#total_cost').val(selected.total_cost);
        // $('#margin').val(selected.margin);
        // $('#persentase').val(selected.persentase);

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
            data: $(this).serialize() + "&id_qc=" + "<?php echo $id_qc ?>",
            dataType: 'json',
            success: function(response) {
                // Tampilkan SweetAlert
                viewDatatable();
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset seluruh form
                        $('#form_qc')[0].reset();
                        // Reset select2
                        $('#cmb_barang').val(null).trigger('change.select2');
                        // Kosongkan input readonly
                        $('#total_price').val('');
                        $('#unit_price_cv').val('');
                        $('#total_po_cv').val('');
                        $('#total_cost').val('');
                        $('#margin').val('');
                        $('#persentase').val('');
                        // Kembalikan _type ke create
                        $("input[name=_type]").val("create");
                        // Reload datatable
                        tableDetail.ajax.reload();
                        $('#formModal').modal('hide');
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            $('#formModal').hide();
                        }, 150);
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

    modalCogs.find("form").on("submit", function(ev) {
        ev.preventDefault();

        let submitButton = $(this).find("[type=submit]");
        let originalContent = submitButton.html();
        submitButton.html('<i class="fa fa-spin fa-spinner"></i> Menyimpan...');
        submitButton.prop("disabled", true);

        let type = $("[name=_type]").val();
        let id = $("[name=id]").val();
        let url = type == "create" ? defaultUrl + "createcogs" : defaultUrl + "detailUpdateCogs/" + id;


        $(this).find('input[type="text"]').each(function() {
    if ($(this).attr('name') && [
        'Unit_price', 'total_price', 'vendor_price', 'unit_price_cv', 'total_po_cv', 'total_cost', 'margin', 'expedittion', 'add_insentif_fe001a', 'instalasi_setting', 'other'
    ].includes($(this).attr('id'))) {
        let val = $(this).val();
        $(this).val(unformatRupiah(val));
    }
});


        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize() + "&id_qc=" + "<?php echo $id_qc ?>",
            dataType: 'json',
            success: function(response) {
                // Tampilkan SweetAlert
                viewDatatable();
                Swal.fire({
                    title: 'Sukses',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset seluruh form
                        $('#form_cogs')[0].reset();
                        // Hilangkan titik pada semua input text di form COGS
                        $('#form_cogs input[type="text"]').each(function() {
                            let val = $(this).val();
                            if (val) {
                                $(this).val(val.replace(/\./g, ''));
                            }
                        });
                        // Kosongkan input readonly
                        $('#expedittion').val('');
                        $('#add_insetif_fe001a').val('');
                        $('#instalasi_setting').val('');
                        $('#pph_bank_fee').val('');
                        $('#other').val('');
                        // Kembalikan _type ke create
                        $("input[name=_type]").val("create");
                        // Reload datatable
                        tableCogs.ajax.reload();
                        // Update total COGS di modal
                        $('#total_cost_cogs').text('Rp 0');
                        baseCostCogs = 0;

                        $('#formCogs').modal('hide');
                        setTimeout(function() {
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('padding-right', '');
                            $('#formCogs').hide();
                        }, 150);
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

     $("#btn-delete").on("click", function () {
        let selected = tableDetail.row('.selected').data();

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
                    url: defaultUrl + "deletedetail/" + selected.id,
                    type: 'POST',
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    success: function(response) {
                        viewDatatable();
                        if(typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Sukses!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    tableDetail.ajax.reload();
                                }
                            });
                        } else {
                            alert(response.message);
                            tableDetail.ajax.reload();
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

    // Tambahkan event handler untuk tombol close
    $('.close, .btn-secondary').click(function() {
        closeModal();
    });

    // Event handler ketika modal akan ditutup
    $('#formModal').on('hide.bs.modal', function () {
        closeModal();
    });

});

function viewDatatable() {
    tableDetail = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('qc/datatabledetail') }}",
            "type": "post",
            data: {
                id_qc: "<?php echo $id_qc ?>"
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
        columns: [
        {
            data: "id_qc",
            className: "text-center align-middle"
        },
        {
            data: "part_number",
            className: "text-center align-middle"
        },
        {
            data: "partnumber_description",
            className: "text-center align-middle"
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
        // Toggle select/unselect
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            $('.basic-datatables tbody tr').removeClass('selected');
            $(this).addClass('selected');
        }
    });
}

// Tambahkan fungsi helper untuk handle modal
function closeModal() {
    $('#formModal').modal('hide');
    $('#formCogs').modal('hide');
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
    $('select[name=cmb_barang]').select2({
        dropdownParent: $('#formModal'),
        allowClear: true,
        width: '72%',
        // height: '35px',
        placeholder: 'Pilih Barang',
        ajax: {
            url: "{{ url('/qc/getbarang') }}",
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1,
                    id_pr: $('#id_pr').val() // ambil id_pr dari input hidden
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.partnumber_description,
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

    $('.select2-container--default .select2-selection--single').css({
        'height': '38px', // Atur tinggi sesuai kebutuhan
        'line-height': '38px' // Atur line-height agar teks terpusat
    });
}




</script>
  @endpush


@endsection
