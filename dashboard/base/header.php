<?php
ob_start(); 
session_start();
include("config/db_connection.php");

// auth check
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Login Required','text'=>'Please sign in to access this dashboard.'];
        header('Location: ./auth/sign-in.php'); 
        exit();
    }

// user id and role from sessions
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// fetch user from database
$user_query = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($connection, $user_query);
$user = mysqli_fetch_assoc($result);

// fetch lawyer profile data if user is lawyer
$lawyer_profile = [];
if ($user['user_role'] === 'lawyer') {
  $lp_query = "SELECT * FROM lawyer_profiles WHERE user_id = '$user_id'";
  $lp_result = mysqli_query($connection, $lp_query);
  $lawyer_profile = mysqli_fetch_assoc($lp_result);
}

$currentPage = basename($_SERVER['PHP_SELF']);
function navItemClass($page)
{
  global $currentPage;
  return $currentPage === $page ? 'nav-link active bg-gradient-dark text-white' : 'nav-link text-dark';
}
?>
<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
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
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Lawyer Admin Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* ── Profile Dropdown ── */
    /* ── Role Badges ── */
.role-pill {
  display: inline-flex; align-items: center; gap: .25rem;
  font-size: .68rem; font-weight: 700; color: #fff;
  padding: .22rem .6rem; border-radius: 20px; letter-spacing: .04em;
}
.pill-admin   { background: linear-gradient(135deg,#3a86ff,#0057cc); }
.pill-lawyer  { background: linear-gradient(135deg,#2dce89,#0b7a4e); }
.pill-pending { background: linear-gradient(135deg,#fb8c00,#e65100); }
    .profile-dd-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      background: none;
      border: none;
      padding: 4px 8px;
      border-radius: 10px;
      cursor: pointer;
      transition: background .2s;
      text-decoration: none;
    }

    .profile-dd-btn:hover {
      background: rgba(0, 0, 0, .05);
    }

    .profile-dd-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: linear-gradient(135deg, #007acc, #0056b3);
      color: #fff;
      font-size: 13px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 3px 8px rgba(0, 122, 204, .35);
      flex-shrink: 0;
    }

    .profile-dd-role {
      font-size: 10px;
      font-weight: 600;
      background: rgba(0, 122, 204, .12);
      color: #007acc;
      padding: 2px 8px;
      border-radius: 20px;
      text-transform: capitalize;
    }

    .profile-dd-menu {
      display: none;
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      min-width: 215px;
      border-radius: 14px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, .15);
      border: 1px solid rgba(0, 0, 0, .08);
      padding: 6px 0;
      overflow: hidden;
      background: #fff;
      z-index: 9999;
      animation: ddFadeIn .15s ease;
    }

    .profile-dd-menu.open {
      display: block;
    }

    @keyframes ddFadeIn {
      from {
        opacity: 0;
        transform: translateY(-6px)
      }

      to {
        opacity: 1;
        transform: translateY(0)
      }
    }

    .profile-dd-item {
      padding: 10px 16px;
      font-size: 13px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 9px;
      color: #344767;
      transition: background .15s;
      text-decoration: none;
      cursor: pointer;
    }

    .profile-dd-item:hover {
      background: #f0f7ff;
      color: #007acc;
      text-decoration: none;
    }

    .profile-dd-item .material-symbols-rounded,
    .profile-dd-item span.material-symbols-rounded {
      font-size: 17px;
      color: #007acc;
    }

    .profile-dd-head {
      padding: 12px 16px 10px;
      border-bottom: 1px solid #f0f0f0;
      margin-bottom: 4px;
    }

    /* ── Appointment count badge ── */
    .appt-badge {
      display: inline-flex !important;
      align-items: center;
      justify-content: center;
      margin-left: auto !important;
      min-width: 20px;
      height: 18px;
      padding: 0 7px;
      background: #f59e0b !important;
      color: #fff !important;
      font-size: 10px !important;
      font-weight: 700 !important;
      border-radius: 20px !important;
      line-height: 1 !important;
      flex-shrink: 0;
      visibility: visible !important;
      opacity: 1 !important;
    }

    /* ── Search ── */
    .dash-search-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }

    .dash-search-input {
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      padding: 7px 14px 7px 36px;
      font-size: 13px;
      width: 190px;
      background: #f8fafc;
      color: #344767;
      transition: border-color .2s, box-shadow .2s, width .3s;
      outline: none;
    }

    .dash-search-input:focus {
      border-color: #007acc;
      box-shadow: 0 0 0 3px rgba(0, 122, 204, .12);
      width: 230px;
      background: #fff;
    }

    .dash-search-icon {
      position: absolute;
      left: 10px;
      font-size: 16px;
      color: #94a3b8;
      pointer-events: none;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main" data-color="primary">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="../index.php">
        <img src="../images/logo.png" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">LegalCare</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="<?php echo navItemClass('index.php'); ?>" href="index.php">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <?php
        $pending_appointments = [];
        $my_appointments = [];

           // count pending request

        if($_SESSION['user_role'] === "admin"){
          $pending_requests_query = "SELECT * FROM lawyer_requests WHERE request_status = 'pending'";
          $pending_requests_result = mysqli_query($connection, $pending_requests_query);
          $pending_requests = mysqli_num_rows($pending_requests_result);
        }
        
          // count pending appointments for lawyer

        if ($_SESSION['user_role'] === 'lawyer') {
          $lawyer_profile_id = $lawyer_profile['lawyer_profile_id'];
          $pending_appointments_query = "SELECT * FROM appointments WHERE appointment_status = 'pending' AND lawyer_profile_id = '$lawyer_profile_id'";
          $pending_appointments_result = mysqli_query($connection, $pending_appointments_query);
          $pending_appointments = mysqli_num_rows($pending_appointments_result);
        }

          // count pending appointments for customer

        if ($_SESSION['user_role'] === 'customer') {
          $my_appointments_query = "SELECT * FROM appointments WHERE appointment_status = 'pending' AND customer_id = '$user_id'";
          $my_appointments_result = mysqli_query($connection, $my_appointments_query);
          $my_appointments = mysqli_num_rows($my_appointments_result);
        }
        ?>
        
        <!-- CUSTOMER MODULES -->
        <?php if ($_SESSION['user_role'] === "customer") { ?>
          <li class="nav-item">
            <a class="<?php echo navItemClass('my_appointments.php'); ?>" href="my_appointments.php">
              <i class="material-symbols-rounded opacity-5">work</i>
              <span class="nav-link-text ms-1">My Bookings</span>
              
              <?php if (isset($my_appointments) && $my_appointments > 0) { ?>
              <b class="appt-badge"><?php echo $my_appointments; ?></b>
              <?php } ?>
            
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('../attorneys.php'); ?>" href="../attorneys.php">
              <i class="material-symbols-rounded opacity-5">calendar_month</i>
              <span class="nav-link-text ms-1">Book Appointment</span>
            </a>
          </li>
        <?php } ?>

        <!-- LAWYER MODULES -->
        <?php if ($_SESSION['user_role'] === "lawyer") { ?>
          <li class="nav-item">
            <a class="<?php echo navItemClass('manage_services.php'); ?>" href="manage_services.php">
              <i class="material-symbols-rounded opacity-5">work</i>
              <span class="nav-link-text ms-1">Service Catalog</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="<?php echo navItemClass('manage_availability.php'); ?>" href="manage_availability.php">
              <i class="material-symbols-rounded opacity-5">calendar_today</i>
              <span class="nav-link-text ms-1">Manage Availability</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('manage_appointments.php'); ?>" href="manage_appointments.php">
              <i class="material-symbols-rounded opacity-5">calendar_month</i>
              <span class="nav-link-text ms-1">Appointments</span>
            
              <?php if (isset($pending_appointments) && $pending_appointments > 0) { ?>
                <b class="appt-badge"><?php echo $pending_appointments; ?></b>     
              <?php } ?>
            
            </a>
          </li>
        <?php } ?>


        <!-- ADMIN MODULES -->
        <?php if ($_SESSION['user_role'] === "admin") { ?>
          <li class="nav-item">
            <a class="<?php echo navItemClass('lawyer_requests.php'); ?>" href="lawyer_requests.php">
              <i class="material-symbols-rounded opacity-5">pending_actions</i>
              <span class="nav-link-text ms-1">Applications</span>

              <?php if (isset($pending_requests) && $pending_requests > 0) { ?>

                <b class="appt-badge"><?php echo $pending_requests; ?></b>
              
                <?php } ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('view_lawyers.php'); ?>" href="view_lawyers.php">
              <i class="material-symbols-rounded opacity-5">gavel</i>
              <span class="nav-link-text ms-1">Attorney Directory</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('manage_homepage.php'); ?>" href="manage_homepage.php">
              <i class="material-symbols-rounded opacity-5">home</i>
              <span class="nav-link-text ms-1">Manage Homepage</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('view_appointments.php'); ?>" href="view_appointments.php">
              <i class="material-symbols-rounded opacity-5">schedule</i>
              <span class="nav-link-text ms-1">Master Schedule</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="<?php echo navItemClass('view_contacts.php'); ?>" href="view_contacts.php">
              <i class="material-symbols-rounded opacity-5">mail</i>
              <span class="nav-link-text ms-1">Inquiries</span>
            </a>
          </li>
        <?php } ?>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
        </li>
        <?php
        $current_user_id = $_SESSION['user_id'];
        ?>
        <li class="nav-item">
          <a class="<?php echo navItemClass('profile.php'); ?>" href="profile.php">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="<?php echo navItemClass('edit_profile.php'); ?>" href="edit_profile.php">
            <i class="material-symbols-rounded opacity-5">edit</i>
            <span class="nav-link-text ms-1">Edit Profile</span>
          </a>
        </li>
        <!-- Add this at the bottom of your sidebar container -->
        <div class="mt-auto p-3">
          <div class="card bg-light border-0 rounded-3 p-3 mb-3">
            <h6 class="small fw-bold">Need Help?</h6>
            <p class="text-muted small mb-2">Check our legal guide for experts.</p>
            <a href="../doc/Lawyers_Website_Requirements.docx" class="btn btn-sm btn-primary bg-gradient-primary w-100">Read Docs</a>
          </div>
        </div>
      </ul>
    </div>
    </div>

  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active me-3" aria-current="page">Dashboard</li>
            <?php if ($_SESSION['user_role'] === 'admin') { ?>
            <span class="role-pill pill-admin">
              <span class="material-symbols-rounded" style="font-size:1rem">admin_panel_settings</span> Admin
            </span>
          <?php } ?>

          <?php if ($_SESSION['user_role'] === 'lawyer') { ?>
            <span class="role-pill pill-lawyer">
              <span class="material-symbols-rounded" style="font-size:1rem">verified</span> Verified Lawyer
            </span>
          <?php } ?>

          <?php if ($_SESSION['user_role'] === 'customer') { ?>
            <span class="role-pill pill-admin" style="background:linear-gradient(135deg,#8392ab,#495361)">
              <span class="material-symbols-rounded" style="font-size:1rem">person</span> Customer
            </span>
          <?php } ?>
          </ol>
        </nav>
          <ul class="navbar-nav d-flex align-items-center  justify-content-end">
            <?php if ($_SESSION['user_role'] === "admin") { ?>
              <!-- Quick Action Button -->
              <a href="lawyer_requests.php" class="btn bg-gradient-dark w-80 mb-0 me-3 rounded-pill">
                <i class="material-symbols-rounded me-1">pending_actions</i>
                View Applications
              </a>

            <?php } ?>
            <?php if ($_SESSION['user_role'] === "lawyer") { ?>
              <!-- Quick Action Button -->
              <a href="manage_availability.php" class="btn bg-gradient-primary w-80 mb-0 me-3 rounded-pill">
                <i class="material-symbols-rounded me-1">event</i>
                Availability
              </a>

            <?php } ?>

            <?php if ($_SESSION['user_role'] === "customer") { ?>
              <!-- Quick Action Button -->
              <a href="../attorneys.php" class="btn bg-gradient-primary w-80 mb-0 me-3 rounded-pill">
                <i class="material-symbols-rounded me-1">calendar_month</i>
                Book Appointment
              </a>

            <?php } ?>
            
            <div class="d-none d-md-block me-3 text-muted small text-nowrap">
              <i class="fa fa-calendar-alt me-1"></i>
              <span id="current-date" class="fw-bold"><?php echo date("M d, Y"); ?></span>
            </div>
            <!-- Profile Dropdown (vanilla JS) -->
            <li class="nav-item d-flex align-items-center" style="position:relative">
              <button class="profile-dd-btn" id="profileDdBtn" type="button" onclick="toggleProfileDd(event)">
                <div class="profile-dd-avatar">
                  <?php if(!empty($lawyer_profile['lawyer_profile_photo'])){ ?>
                    <img src="../uploads/<?php echo $lawyer_profile['lawyer_profile_photo'] ?>" height="30px" style="object-fit: cover;width: 30px;height: 30px; border-radius: 50%;">
                  <?php } else { ?>
                    <?php echo substr($_SESSION['user_name'], 0, 1) ?>
                  <?php } ?>
                </div>
                <span class="material-symbols-rounded" style="font-size:16px;color:#94a3b8;line-height:1">expand_more</span>
              </button>

              <div class="profile-dd-menu" id="profileDdMenu">
                <div class="profile-dd-head">
                  <div class="d-flex align-items-center gap-2">
                    <div class="profile-dd-avatar">
                  <?php if(!empty($lawyer_profile['lawyer_profile_photo'])){ ?>
                    <img src="../uploads/<?php echo $lawyer_profile['lawyer_profile_photo'] ?>" height="30px" style="object-fit: cover;width: 30px;height: 30px; border-radius: 50%;">
                  <?php } else { ?>
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
                  <?php } ?>
                </div>

                    <div class="text-sm font-weight-bold" style="color:#344767"><?php echo $_SESSION['user_name']; ?></div>
                  </div>
                </div>

                <a class="profile-dd-item" href="profile.php">
                  <span class="material-symbols-rounded">person</span> My Profile
                </a>

                <a class="profile-dd-item" href="edit_profile.php">
                  <span class="material-symbols-rounded">edit</span> Edit Profile
                </a>
                <!-- user modules -->
                <?php if ($_SESSION['user_role'] === 'customer') { ?>
                  <a class="profile-dd-item" href="my_appointments.php">
                    <span class="material-symbols-rounded">calendar_month</span>
                    Appointments
                    <?php if (isset($my_appointments) && $my_appointments > 0) { ?>

                      <b class="appt-badge"><?php echo $my_appointments; ?></b>

                    <?php } ?>
                  </a>
                <?php } ?>
                <!-- lawyer modules -->
                <?php if ($_SESSION['user_role'] === 'lawyer') { ?>
                  <a class="profile-dd-item" href="manage_appointments.php">
                    <span class="material-symbols-rounded">calendar_month</span>
                    Appointments
                    <?php if (isset($pending_appointments) && $pending_appointments > 0) { ?>
                      
                      <b class="appt-badge"><?php echo $pending_appointments; ?></b>
                    
                      <?php } ?>
                  </a>

                <?php } ?>
                <!-- admin modules -->
                <?php if ($_SESSION['user_role'] === 'admin') { ?>
                  <a class="profile-dd-item" href="lawyer_requests.php">
                    <span class="material-symbols-rounded">pending_actions</span>
                    Applications
                    <?php if (isset($pending_requests) && $pending_requests > 0) { ?>

                      <b class="appt-badge"><?php echo $pending_requests; ?></b>

                    <?php } ?>
                  </a>

                  <a class="profile-dd-item" href="view_contact.php">
                    <span class="material-symbols-rounded">mail</span>
                    Inquiries
                  </a>

                <?php } ?>
                <hr style="margin:4px 0;border-color:#f0f0f0">
                <a class="profile-dd-item" href="auth/logout.php" style="color:#e53e3e">
                  <span class="material-symbols-rounded" style="color:#e53e3e">logout</span> Log Out
                </a>

              </div>
            </li>
            <li class="nav-item d-xl-none ps-3 mb-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <script>
      function toggleProfileDd(e) {
        e.stopPropagation();
        var m = document.getElementById('profileDdMenu');
        m.classList.toggle('open');
      }
      document.addEventListener('click', function() {
        var m = document.getElementById('profileDdMenu');
        if (m) m.classList.remove('open');
      });
    </script>