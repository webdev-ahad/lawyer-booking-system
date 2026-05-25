<?php
	session_start();
	include("./dashboard/config/db_connection.php");

    // auth check
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Login Required','text'=>'Please sign in to access this page.'];
        header('Location: ./dashboard/auth/sign-in.php'); exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Legalcare - Free Bootstrap 4 Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/forms-dashboard.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<!-- SweetAlert2 session flash -->
<?php if(isset($_SESSION['_swal'])){ ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
    <?php unset($_SESSION['_swal']); ?>
    Swal.fire({
      icon:  _swal.icon,
      title: _swal.title,
      text:  _swal.text || ''
    }).then(function() {
      if(_swal.redirect) window.location.href = _swal.redirect;
    });
  });
</script>
<?php } ?>

    <nav class="navbar px-md-0 navbar-expand-lg navbar-dark ftco_navbar ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="./images/logo.png" alt="Legalcare" height="60px"></a>
            <button class="navbar-toggler ftco-navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <?php
                    $user_role = $_SESSION['user_role'];
                ?>

<ul class="navbar-nav ml-auto align-items-center">

        <!-- common pages -->
            <li class="nav-item active"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>

        <?php if($user_role == 'customer'){ ?>

            <!-- customer modules -->
            <li class="nav-item"><a href="dashboard/my_appointments.php" class="nav-link">My Appointments</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="practice_areas.php" class="nav-link">Practice Areas</a></li>
            <li class="nav-item ftco-cta-btn">
                <a href="attorneys.php" class="btn btn-primary py-2 px-4">Consult a Lawyer</a>
            </li>

        <?php } elseif($user_role == 'lawyer'){ ?>

            <!-- lawyer modules -->
            <li class="nav-item"><a href="dashboard/index.php" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="dashboard/manage_availability.php" class="nav-link">Manage Availability</a></li>
            <li class="nav-item"><a href="dashboard/manage_appointments.php" class="nav-link">Appointments</a></li>
            <li class="nav-item"><a href="attorneys.php" class="nav-link">View Public Listing</a></li>

        <?php } elseif($user_role == 'admin'){ ?>

            <!-- admin modules -->
            <li class="nav-item"><a href="dashboard/index.php" class="nav-link">Admin Panel</a></li>
            <li class="nav-item"><a href="dashboard/view_lawyers.php" class="nav-link">Lawyers</a></li>
            <li class="nav-item"><a href="dashboard/lawyer_requests.php" class="nav-link">Requests</a></li>

        <?php } ?>

    <!-- user profile dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDrop" data-toggle="dropdown">
            <div class="user-avatar-sm mr-2"></div>
            <span>Account</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow border-0">
            <a class="dropdown-item" href="dashboard/profile.php"><i class="ion-ios-person mr-2"></i>My Profile</a>
            <a class="dropdown-item" href="dashboard/edit_profile.php"><i class="ion-ios-create mr-2"></i>Edit Profile</a>

            <?php if($user_role == 'customer'){ ?>

                <!-- customer module -->
                <a class="dropdown-item" href="dashboard/my_appointments.php"><i class="ion-ios-calendar mr-2"></i>My Appointments</a>
            <?php } ?>

            <?php if($user_role == 'lawyer'){ ?>

                <!-- lawyer module -->
                <a class="dropdown-item" href="dashboard/lawyer_appointments.php"><i class="ion-ios-calendar mr-2"></i>Appointments</a>
            <?php } ?>

            <?php if($user_role == 'admin'){ ?>

                <!-- admin module -->
                <a class="dropdown-item" href="dashboard/view_lawyers.php"><i class="ion-ios-calendar mr-2"></i>Lawyers</a>
                <a class="dropdown-item" href="dashboard/lawyer_requests.php"><i class="ion-ios-calendar mr-2"></i>Requests</a>
            <?php } ?>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="dashboard/auth/logout.php"><i class="ion-ios-log-out mr-2"></i>Logout</a>
        </div>
    </li>
</ul>
            </div>
        </div>
    </nav>
    <!-- End nav -->