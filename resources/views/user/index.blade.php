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

    select[name="divisi"] { }
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
                <h6>Data Akun</h6>
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
                          <button type="button" class="btn btn-info" id="btn-password" data-toggle="modal" data-target="#formPassword">
                            <i class="fa-solid fa-key" style="margin-right: 10px;"></i> Ubah Password
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
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">NIP</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Nama</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Email</th>
                        <th style="color: white;" class="text-center text-uppercase text-xxs font-weight-bolder opacity-7">Role</th>
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
          <h5 class="modal-title" id="exampleModalLabel">Form User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="form_user">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">NIP</span>
                        </div>
                        <input type="text" name="nip" id="nip" class="form-control" style="border: 1px solid black;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Nama User</span>
                        </div>
                        <input type="text" name="name" id="name" class="form-control" style="border: 1px solid black;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Email</span>
                        </div>
                        <input type="email" name="email" id="email" class="form-control" style="border: 1px solid black;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Password</span>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" style="border: 1px solid black;">
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="inputGroupSelect01">Role</label>
                        </div>
                        <select class="custom-select" id="inputGroupSelect01" name="role">
                          <option selected>Choose...</option>
                          <option value="super_admin">Super Admin</option>
                          <option value="manager">Manager</option>
                          <option value="sales">Sales</option>
                          <option value="teknisi">Teknisi</option>
                          <option value="admin">Admin</option>
                        </select>
                      </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <label class="input-group-text" for="inputGroupSelect01">Divisi</label>
                        </div>
                        <select class="custom-select" id="inputGroupSelect01" name="divisi">
                          <option selected>Choose...</option>
                          <option value="agus_sopyan">Pak Agus Sopyan</option>
                          <option value="acep_sonjaya">Pak Acep Sonjaya</option>
                          <option value="taufik_rachmat">Pak Taufik Rachmat. H</option>
                        </select>
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

  <div class="modal fade" id="formPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Form User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="formPassword">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="_type" value="create">
                    <input type="hidden" name="id" id="id" value="">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text" style="width: 120px; height: 35px; background-color: rgb(222, 222, 222);">Password</span>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" style="border: 1px solid black;">
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
<script>

console.log("kipak");

window.defaultUrl = '{{ url('/user/') }}/';

let modalUser = $("#formModal");
let modalPassword = $("#formPassword");
let tableUser;

function toggleDivisi() {
    const roleVal = $('select[name=role]').val();
    const divisiGroup = $('select[name=divisi]').closest('.input-group');

    if (roleVal === 'sales') {
        divisiGroup.show();
    } else {
        divisiGroup.hide();
        // reset nilai divisi saat disembunyikan
        $('select[name=divisi]').prop('selectedIndex', 0).trigger('change');
    }
}

$(document).ready(function() {
    viewDatatable();
    collectionS2Search();

    // Inisialisasi tampilan awal
    toggleDivisi();

    // Tampilkan/sembunyikan saat role berubah
    $('select[name=role]').on('change', toggleDivisi);

    // Saat buka modal create
    $('button[data-target="#formModal"]').on('click', function() {
        // Reset form
        $('#form_user')[0].reset();

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
        toggleDivisi(); // default akan hide karena role belum 'staff'
    });

    // Event handler untuk tombol generate PR
    $('#btn-generate-pr').on('click', function() {
        generateNomorPR();
    });

    $('.submit-filter').on('click', function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        viewDatatable();
    });


    $("#btn-edit").on("click", function () {
        let selected = tableUser.row('.selected').data();

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

        modalUser.find("input[name=_type]").val("update");
        modalUser.find("input[name=id]").val(selected.id);
        modalUser.find("input[name=nip]").val(selected.nip);
        modalUser.find("input[name=name]").val(selected.name);
        modalUser.find("input[name=email]").val(selected.email);
        modalUser.find("select[name=role]").val(selected.role);
        toggleDivisi();

        // Sembunyikan field password saat mode edit
        $('.input-group:has(#password)').hide();

        resetErrors();
        modalUser.modal("show");
    });

    $("#btn-password").on("click", function () {
        let selected = tableUser.row('.selected').data();

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

        modalPassword.find("input[name=_type]").val("update");
        modalPassword.find("input[name=id]").val(selected.id);
        modalPassword.find("input[name=password]").val(selected.password);

        resetErrors();
        modalPassword.modal("show");
    });



    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    modalUser.find("form").on("submit", function(ev) {
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
                $('#form_user')[0].reset();

                // Reload table
                tableUser.ajax.reload();

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
                        tableUser.ajax.reload();
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
        let selected = tableUser.row('.selected').data();

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
                                    tableUser.ajax.reload();
                                }
                            });
                        } else {
                            alert(response.message);
                            tableUser.ajax.reload();
                        }
                        $('#form_user')[0].reset();
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
        let selected = tableUser.row('.selected').data();

        if (_.isEmpty(selected) ||  selected == undefined) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Pilih Data Terlebih Dahulu',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }

        window.location.href = defaultUrl + "detail_data_swasta?id_swasta=" + selected.id;

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
    tableUser = $(".basic-datatables").DataTable({
        ajax: {
            url: "{{ route('user/datatable') }}",
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
                data: "nip",
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
                data: "email",
                render: function (data, type, row, meta) {
                    if (data == '' || data == null) {
                        return '-';
                    } else {
                        return data;
                    }
                }
            },
            {
                data: "role",
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
            var rowData = tableUser.row(indexes).data();
            $("#btn-edit").removeClass("disabled");
            $("#btn-password").removeClass("disabled");
            $("#btn-delete").removeClass("disabled");
            alert('1');
        })
        .on("deselect", function (e, dt, type, indexes) {
            $("#btn-edit").addClass("disabled");
            $("#btn-password").addClass("disabled");
            $("#btn-delete").addClass("disabled");
            alert('0');
        });

    // Pindahkan event handler ini ke sini, dan gunakan .off() untuk mencegah duplikasi
    $('.basic-datatables tbody').off('click', 'tr').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('#btn-ubah').addClass('disabled');
        } else {
            tableUser.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $('#btn-ubah').removeClass('disabled');
        }
    });
}


function collectionS2Search() {
    $('select[name=cmb_nip]').select2({
        dropdownParent: $('#formFilter'),
        allowClear: true,
        width: '72.5%',
        placeholder: '',
        ajax: {
            url: "{{ url('/user/getSales') }}",
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

</script>
  @endpush

@endsection
