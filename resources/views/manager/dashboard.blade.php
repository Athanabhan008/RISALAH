@extends('layouts.manager.template_manager')

@section('content')

@push('css')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-hidden">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
      </div>
    </nav>
    <!-- End Navbar -->

      <div class="row my-4">
        <div class="col-12">
              </div>
            </div>
                <!-- Grafik Overview Penjualan -->
                    <div class="container">
                        <img src="../../admin/assets/img/illustrations/image.jpg" width="500px" alt="main_logo" style="display: block; margin-left:auto; margin-right:auto; margin-top: 20px;">
                        <h3 style="text-align: center; color:#0c2e8a; margin-top: 20px;">Selamat Datang Di Sistem Aplikasi PR MBS</h3>
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
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Kodeks Digital</a>
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
