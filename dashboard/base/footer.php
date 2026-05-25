<footer class="footer py-4">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-5 mb-lg-0 mb-3">
              <div class="d-flex align-items-center gap-2 mb-1 justify-content-center justify-content-lg-start">
                <span class="text-dark font-weight-bold text-sm">Legalcare</span>
                <span class="badge badge-sm" style="background:#007acc;color:#fff;font-size:10px;padding:3px 8px;border-radius:10px">Dashboard</span>
              </div>
              <div class="copyright text-center text-xs text-muted text-lg-start">
                © <script>document.write(new Date().getFullYear())</script> Legalcare. All rights reserved.
              </div>
            </div>
            <div class="col-lg-7">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end flex-wrap gap-1">
                <li class="nav-item">
                  <a href="index.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">dashboard</i> Dashboard
                  </a>
                </li>
                <li class="nav-item">
                  <a href="profile.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">person</i> Profile
                  </a>
                </li>
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'lawyer'): ?>
                <li class="nav-item">
                  <a href="manage_appointments.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">calendar_month</i> Appointments
                  </a>
                </li>
                <li class="nav-item">
                  <a href="manage_services.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">work</i> Services
                  </a>
                </li>
                <?php endif; ?>
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li class="nav-item">
                  <a href="lawyer_requests.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">table_view</i> Requests
                  </a>
                </li>
                <li class="nav-item">
                  <a href="view_lawyers.php" class="nav-link text-muted text-xs py-1 px-2">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">groups</i> Lawyers
                  </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                  <a href="../index.php" class="nav-link text-muted text-xs py-1 px-2" target="_blank">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">open_in_new</i> Website
                  </a>
                </li>
                <li class="nav-item">
                  <a href="auth/logout.php" class="nav-link text-xs py-1 px-2" style="color:#e53e3e">
                    <i class="material-symbols-rounded" style="font-size:13px;vertical-align:-3px">logout</i> Logout
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
          label: "Views",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#007acc",
          data: [50, 45, 22, 28, 50, 60, 76],
          barThickness: 'flex'
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
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: '#e5e5e5'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
              color: "#737373"
            },
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
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        datasets: [{
          label: "Sales",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: "#007acc",
          pointBorderColor: "transparent",
          borderColor: "#007acc",
          backgroundColor: "transparent",
          fill: true,
          data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              title: function(context) {
                const fullMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                return fullMonths[context[0].dataIndex];
              }
            }
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
              borderDash: [4, 4],
              color: '#e5e5e5'
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 12,
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
              color: '#737373',
              padding: 10,
              font: {
                size: 12,
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");

    new Chart(ctx3, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Tasks",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: "#007acc",
          pointBorderColor: "transparent",
          borderColor: "#007acc",
          backgroundColor: "transparent",
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6

        }],
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
              borderDash: [4, 4],
              color: '#e5e5e5'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#737373',
              font: {
                size: 14,
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
              borderDash: [4, 4]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
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
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

  <!-- ── SweetAlert2 session flash ─────────────────────────────── -->
  <?php if(isset($_SESSION['_swal'])): ?>
  <script>
    var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
    <?php unset($_SESSION['_swal']); ?>
    Swal.fire({
      icon:  _swal.icon,
      title: _swal.title,
      text:  _swal.text || ''
    }).then(function() {
      if(_swal.redirect) window.location.href = _swal.redirect;
    });
  </script>
  <?php endif; ?>

<?php ob_end_flush(); ?>
</body>

</html>