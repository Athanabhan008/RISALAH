@extends('layouts.manager.template_manager')

@section('content')

@push('css')
<link rel="stylesheet" href="../../admin/assets/css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
  /* main {
    overflow-x: hidden;
    background-color: #d9d9d9bc;
  } */

  .main-content {
    overflow-x: hidden;
    max-width: 100vw;
  }

  .container-fluid {
    padding-right: 15px;
    padding-left: 15px;
    width: 100%;
    overflow-x: hidden;
  }

  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
  }

  .swiper-pagination {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    text-align: center;
    margin-top: 15px;
  }
</style>

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-hidden">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
      </div>
    </nav>
    <!-- End Navbar -->
      <div class="row g-0">
        <div class="col-12">
          <div class="row g-3">

            <h4>Booking</h4>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="swiper">
                            <div class="slider-wrapper">
                                <div class="card-list swiper-wrapper">
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Hari Ini</span>
                                      </div>
                                      <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                                      </div>
                                      <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Tahun Ini</span>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="swiper">
                    <div class="slider-wrapper">
                        <div class="card-list swiper-wrapper">
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
          </div>
        </div>
      </div>


      <div class="row g-0">
        <div class="col-12">
          <div class="row g-3">

            <h4>Sound System</h4>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="swiper">
                            <div class="slider-wrapper">
                                <div class="card-list swiper-wrapper">
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Hari Ini</span>
                                    </div>
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                                    </div>
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Tahun Ini</span>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="swiper">
                    <div class="slider-wrapper">
                        <div class="card-list swiper-wrapper">
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
          </div>
        </div>
      </div>


      <div class="row g-0">
        <div class="col-12">
          <div class="row g-3">

            <h4>Laundry</h4>
            <div class="col-lg-6 col-md-6 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="swiper">
                            <div class="slider-wrapper">
                                <div class="card-list swiper-wrapper ">
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                            357
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                                    </div>
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                            357
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                                    </div>
                                    <div class="card-item swiper-slide">
                                        <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                        <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                            357
                                        </h5>
                                        <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                                    </div>
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="col-lg-6 col-md-6 col-12">
        <div class="card">
            <div class="card-body">
                <div class="swiper">
                    <div class="slider-wrapper">
                        <div class="card-list swiper-wrapper">
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                            <div class="card-item swiper-slide">
                                <i class="fa-solid fa-money-bill-trend-up icon-wrapper"></i>
                                <h5 class="text-white font-weight-bolder mb-0" style="margin-top: -5px">
                                    357
                                </h5>
                                <span class="text-white text-sm">Pemasukkan Bulan Ini</span>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
          </div>
        </div>
      </div>


      <div class="row my-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-lg-6 col-7">
                  <h6>Overview Penjualan</h6>
                </div>
                <div class="col-lg-6 col-5 my-auto text-end">
                  <div class="dropdown float-lg-end pe-4">
                    <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="fa fa-ellipsis-v text-secondary"></i>
                    </a>
                    <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                      <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive overflow-hidden">
                <!-- Grafik Overview Penjualan -->
                    <div style="height: 300px;" class="container">
                        <canvas id="salesChart"></canvas>
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
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Argana</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
      document.addEventListener("DOMContentLoaded", function () {
          const ctx = document.getElementById('salesChart').getContext('2d');
          new Chart(ctx, {
              type: 'line',
              data: {
                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                  datasets: [
                      {
                          label: 'Penjualan',
                          data: [200, 400, 250, 450, 300, 420, 380, 500, 450, 350, 400, 430],
                          borderColor: 'magenta',
                          fill: false,
                          tension: 0.4
                      },
                      {
                          label: 'Target',
                          data: [150, 300, 200, 400, 250, 380, 350, 460, 420, 320, 380, 400],
                          borderColor: 'blue',
                          fill: false,
                          tension: 0.4
                      }
                  ]
              },
              options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                      y: { beginAtZero: true }
                  }
              }
          });
      });
  </script>

  <script>

    const swiper = new Swiper('.slider-wrapper  ', {
    loop: true,
    grabCursor: true,
    spaceBetween: 30,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
    clickable: true,
    dynamicBullets: true,
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  breakpoints: {
    0: {
        slidesPerView: 1
    },
    620: {
        slidesPerView: 1
    },
    1024: {
        slidesPerView: 1
    }
  }
});

  </script>
  @endpush

@endsection
