<?php
session_start();
include("../config/db_connection.php");

if (isset($_POST['sign-in'])) {

    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];

    // validations
    if ($user_email == '' || $user_password == '') {
        $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Missing Fields', 'text' => 'Please enter your email and password.'];
        header("Location: sign-in.php");
        exit();
    }

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Invalid Email', 'text' => 'Please enter a valid email address.'];
        header("Location: sign-in.php");
        exit();
    }

    // get user data
    
    $user_query = "SELECT * FROM users WHERE user_email = '$user_email'";
    $user_result = mysqli_query($connection, $user_query);
    
    if (!$user_result || mysqli_num_rows($user_result) !== 1) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'User Not Found', 'text' => 'No account exists with that email address.'];
        header("Location: sign-in.php");
        exit();
    }
    
    // get user data
    $user = mysqli_fetch_assoc($user_result);

    // verify password by hashing 

    if (!password_verify($user_password, $user['user_password'])) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Wrong Password', 'text' => 'The password you entered is incorrect.'];
        header("Location: sign-in.php");
        exit();
    }

    // set sessions

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['user_name'];
    $_SESSION['user_email'] = $user['user_email'];
    $_SESSION['user_role']  = $user['user_role'];
    $_SESSION['_swal'] = ['icon' => 'success', 'title' => 'Welcome back!', 'text' => 'Login successful.'];

    // if admin redirect to dashboard directly

    if ($user['user_role'] === 'admin') {
        header("Location: ../index.php");
        exit();
    }
    // if lawyer check setup completion if not then redirect him to setup page

    if ($user['user_role'] === 'lawyer') {
        $user_id = $user['user_id'];
        $setup_check_query = "SELECT lawyer_setup_completed FROM lawyer_profiles WHERE user_id = $user_id LIMIT 1";
        $setup_result = mysqli_query($connection, $setup_check_query);
        $setup = mysqli_fetch_assoc($setup_result);

        // no profile row yet, or setup not finished

        if (!$setup || $setup['lawyer_setup_completed'] === 0) {
            $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Profile Incomplete', 'text' => 'Please complete your lawyer application.'];
            header("Location: ../apply_lawyer.php");
            exit();
        }

        // if lawyer completed setup then redirect to dashboard
        header("Location: ../index.php");
        exit();
    }

    // check if they have a pending lawyer application
    if ($user['user_role'] === 'customer') {
    
        $user_id = $user['user_id'];
        $check_app = mysqli_query($connection, "SELECT request_status FROM lawyer_requests WHERE user_id = $user_id LIMIT 1");
        
        if (mysqli_num_rows($check_app) > 0) {
            $app = mysqli_fetch_assoc($check_app);
        
        if ($app['request_status'] === 'pending') {
            header("Location: ../profile.php");
            exit();
        }
    } 
    
    // redirect for normal customers
    header("Location: ../../index.php");
    exit();
}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Sign In - Legalcare</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- Material Dashboard CSS -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <style>
    
    .btn-signin {
      background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%) !important;
      color: #fff !important;
      border: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2) !important;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    .btn-signin:hover {
      box-shadow: 0 8px 15px rgba(59, 130, 246, 0.35) !important;
      transform: translateY(-2px);
    }

    .card-plain {
      background-color: #ffffff;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }
  </style>
</head>

<body>

  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                   style="background-image: url('../assets/img/illustrations/illustration-reset.jpg'); background-size: cover; background-position: center;">
              </div>
              
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto me-lg-auto ms-lg-5">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder text-dark">Sign In</h4>
                  <p class="mb-0 text-sm">Enter your email and password to sign in</p>
                </div>
                <div class="card-body">
                  <form role="form" class="text-start" method="POST" id="signInForm" novalidate>
                    <div class="input-group input-group-outline my-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control" name="user_email" id="si_email" required>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" class="form-control" name="user_password" id="si_password" required>
                    </div>
                    <div class="form-check form-switch d-flex align-items-center mb-3">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked>
                      <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary w-100 my-4 mb-2" name="sign-in">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Don't have an account?
                    <a href="sign-up.php" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Core JS -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
  <?php if (isset($_SESSION['_swal'])) { ?>
  <script>
    var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
    <?php unset($_SESSION['_swal']); ?>
    Swal.fire({
      icon:  _swal.icon,
      title: _swal.title,
      text:  _swal.text || ''
    }).then(function () {
      if (_swal.redirect) window.location.href = _swal.redirect;
    });
  </script>
  <?php } ?>

</body>
</html>
