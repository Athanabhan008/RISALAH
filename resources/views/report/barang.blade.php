@extends('layouts.manager.template_manager')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Tables</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Tables</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <center>
            <div class="col-8">
              <div class="card mb-4" style="filter: drop-shadow(5px 5px 10px rgb(0, 0, 0)); width: 100%; height: 20%">
                <div class="card-header pb-0">
                  <h6>Report Barang</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2" >
                  <div class="table-responsive p-0">
                    <div class="container">
                    <form class="p-3" id="soundForm">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="form-group col-12 col-md-12">
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="width: 90px; font-weight: bold; background-color: #AAB99A;">Tgl Mulai</span>
                                    <input type="date" class="form-control" autofocus name="nama_peserta" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                  </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-12 col-md-12">
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text" id="inputGroup-sizing-sm" style="width: 90px; font-weight: bold; background-color: #AAB99A;">Tgl Akhir</span>
                                    <input type="date" class="form-control" autofocus name="nama_peserta" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                                  </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="form-group col-12 col-md-12">
                                <button type="submit" class="btn btn-danger mt-4 w-100">Cetak PDF <i class="fa-solid fa-file-pdf" style="margin-left: 10px"></i></button>
                            </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </center>
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

  @push('scripts')
  {{-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> --}}
  {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css"> --}}
  <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
  <script src="/admin/assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js" integrity="sha512-9p/L4acAjbjIaaGXmZf0Q2bV42HetlCLbv8EP0z3rLbQED2TAFUlDvAezy7kumYqg5T8jHtDdlm1fgIsr5QzKg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <script>

</script>

  @endpush

@endsection
