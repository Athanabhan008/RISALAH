@extends('layouts.barang.template_barang')

@section('content')

<style>

</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Data Kategori</a></li>
          </ol>
          <h6 class="font-weight-bolder mb-0 mt-3">Data Kategori</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Kategori</h6>
              <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                  <a href="{{ url('/kategori_barang/tambah_data_kategori') }}" type="button" class="btn btn-success">
                    <i class="fa-solid fa-plus fa-lg me-2"></i>Tambah Data
                  </a>
                  <div class="d-flex flex-column flex-sm-row gap-2">
                      <a href="{{ url('/barang/ubah_data_barang') }}" type="button" class="btn" style="background-image: linear-gradient(to right, #344CB7, #577BC1); color: white;">
                        <i class="fa-solid fa-pencil me-2"></i> Ubah Data
                      </a>
                      <a href="{{ url('/barang/detail_barang') }}" type="button" class="btn" style="background-color: #e3d42f; color: white;">
                        <i class="fa-solid fa-file me-2"></i> Detail Data
                      </a>
                  </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <div class="table-wrapper" style="overflow-x: auto;">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Barang</th>
                      </tr>
                    </thead>
                    <tbody>
                          {{-- @foreach ($laundry as $item)

                          <tr>
                              <td>
                              <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                  <h6 class="mb-0 text-sm"> {{ $loop->iteration }} </h6>
                                  </div>
                              </div>
                          </td>
                          <td>
                              <p class="text-xs font-weight-bold mb-0">{{ $item->nama_paket }}</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                              <span class="text-secondary text-xs font-weight-bold">@currency($item->harga)</span>
                          </td>
                          <td class="align-middle text-center">
                              <span class="text-secondary text-xs font-weight-bold">{{ $item->berat }} KG</span>
                          </td>
                          <td class="align-middle text-center">
                              <span class="text-secondary text-xs font-weight-bold">@currency( $item->total_harga )</span>
                          </td>
                      </tr>
                      @endforeach --}}
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
                © <script>
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

@endsection
