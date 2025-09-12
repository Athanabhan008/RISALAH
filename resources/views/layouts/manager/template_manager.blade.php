<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="apple-touch-icon" sizes="76x76" href="../../admin/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../admin/assets/img/logos/Logo MBS Corp.png">
  <title>
    SIM MBS
  </title>
  <!--     Fonts and icons     -->
  @stack('css')
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../../admin/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <style>
    .nav-item .collapse {
      transition: all 0.3s ease;
    }

    .nav-item .collapse .nav-link {
      padding-left: 1rem;
      font-size: 0.875rem;
    }

    .fa-angle-down {
      transition: transform 0.3s ease;
    }

    [aria-expanded="true"] .fa-angle-down {
      transform: rotate(180deg);
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <!-- Mobile menu button - positioned on the right -->
  <div class="position-fixed px-3 py-2 d-xl-none" style="z-index: 1030; right: 0;">
    <button class="btn btn-white shadow-none" type="button" onclick="toggleSidenav()">
      <i class="fa fa-bars"></i>
    </button>
  </div>

  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" style="background-color: white; background-size: cover; background-position: center;">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/soft-ui-dashboard/pages/dashboard.html " target="_blank">
        <img src="../../admin/assets/img/logos/Logo MBS Corp.png" class="navbar-brand-img h-800" alt="main_logo">
        <span class="ms-1 font-weight-bold">SIM MBS</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse h-100 w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav h-100">
        @if(Auth::user()->role === 'teknisi')
          <!-- Menu khusus untuk role teknisi - hanya Quality Control -->
          <li class="nav-item">
            <a class="nav-link {{ ($active === "qc") ? 'active' : '' }}" href="{{ url('/qc') }}">
              <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-screwdriver-wrench" style="color: {{ ($active === "qc") ? 'white' : 'black' }};"></i>
              </div>
              <span class="nav-link-text ms-1">Quality Control</span>
            </a>
          </li>
        @else
          <!-- Menu untuk role selain teknisi -->
          @if(Auth::user()->role !== 'staff')
          <li class="nav-item">
              <a class="nav-link {{ ($active === "manager") ? 'active' : '' }}" href="{{ url('/manager') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-house" style="color: {{ ($active === "manager") ? 'white' : 'black' }};"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
              </a>
            </li>
          @endif


            <li class="nav-item">
              <a class="nav-link {{ ($active === "pr_wapu") ? 'active' : '' }}" href="{{ url('/pr_wapu') }}">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-folder" style="color: {{ ($active === "pr_wapu") ? 'white' : 'black' }};"></i>
                </div>
                <span class="nav-link-text ms-1">PR</span>
              </a>
            </li>

            @if(Auth::user()->role !== 'sales')
              <li class="nav-item">
                  <a class="nav-link {{ ($active === "po") ? 'active' : '' }}" href="{{ url('/po_vendor') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-file-invoice" style="color: {{ ($active === "po") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">PO Vendor</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link {{ ($active === "do") ? 'active' : '' }}" href="{{ url('/delivery_order') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-boxes-stacked" style="color: {{ ($active === "do") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">Delivery Order</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link {{ ($active === "invoice") ? 'active' : '' }}" href="{{ url('/invoice') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-file-invoice-dollar" style="color: {{ ($active === "invoice") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">Invoice</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link {{ ($active === "qc") ? 'active' : '' }}" href="{{ url('/qc') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-screwdriver-wrench" style="color: {{ ($active === "qc") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">Quality Control</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link {{ ($active === "user") ? 'active' : '' }}" href="{{ url('/user') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-users" style="color: {{ ($active === "user") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">User</span>
                  </a>
                </li>

                <li class="nav-item">
                  <a class="nav-link {{ ($active === "approval") ? 'active' : '' }}" href="{{ url('/approval') }}">
                    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa-solid fa-list-check" style="color: {{ ($active === "approval") ? 'white' : 'black' }};"></i>
                    </div>
                    <span class="nav-link-text ms-1">Approval</span>
                  </a>
                </li>

                @endif
        @endif

        <li class="nav-item">
          <a class="nav-link" href="#" onclick="confirmLogout()">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-right-from-bracket fa-10x" style="color: black"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Out</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  @yield('content')

  @stack('scripts')
  <!--   Core JS Files   -->
  <script src="../../admin/assets/js/core/popper.min.js"></script>
  <script src="../../admin/assets/js/core/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  <script src="../../admin/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../admin/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../admin/assets/js/plugins/chartjs.min.js"></script>
  <script src="../../admin/assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="../../admin/assets/js/plugins/datatables/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
  <script src="../../admin/assets/js/core/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
  <script src="../../admin/assets/js/plugins/datatables/datatables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Inter",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="https://kit.fontawesome.com/90c4b6e831.js" crossorigin="anonymous"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../admin/assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
  <script>
    function toggleSidenav() {
      const body = document.getElementsByTagName('body')[0];
      const sidenav = document.getElementById('sidenav-main');

      if (body.classList.contains('g-sidenav-pinned')) {
        body.classList.remove('g-sidenav-pinned');
        setTimeout(function() {
          sidenav.classList.remove('bg-white');
        }, 100);
      } else {
        body.classList.add('g-sidenav-pinned');
        sidenav.classList.add('bg-white');
      }
    }
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const reportLink = document.querySelector('[data-bs-target="#reportSubmenu"]');
      const reportSubmenu = document.getElementById('reportSubmenu');

      reportLink.addEventListener('click', function(e) {
        e.preventDefault();
        const isExpanded = this.getAttribute('aria-expanded') === 'true';

        // Toggle aria-expanded attribute
        this.setAttribute('aria-expanded', !isExpanded);

        // Toggle collapse using Bootstrap's collapse API
        if (isExpanded) {
          $(reportSubmenu).collapse('hide');
        } else {
          $(reportSubmenu).collapse('show');
        }
      });
    });
  </script>
  <script>
    function confirmLogout() {
      Swal.fire({
        title: 'Apakah Anda yakin ingin keluar?',
        text: "Anda akan diarahkan ke halaman Login.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, keluar!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "{{ url('logout') }}";
        }
      });
    }
  </script>
</body>

</html>
