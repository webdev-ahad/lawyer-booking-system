<?php
ob_start();
session_start();
include("../config/db_connection.php");

if(isset($_POST['sign-up'])){

    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $user_phone = $_POST['user_phone'];
    $account_type = $_POST['account_type'];

    // validations
    if(empty($user_name) || empty($user_email) || empty($user_password) || empty($user_phone) || empty($account_type)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Fields','text'=>'All fields are required.'];
        header("Location: sign-up.php"); exit();
    }
    // name
    if(!preg_match("/^[a-zA-Z\s]+$/", $user_name) || strlen($user_name) < 3 || strlen($user_name) > 50){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Name','text'=>'Name must be 3-50 characters long and contain only letters and spaces.'];
        header("Location: sign-up.php"); exit();
    }

    // email validation
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Email','text'=>'Please enter a valid email address.'];
        header("Location: sign-up.php"); exit();
    }

    // password validation
    if(strlen($user_password) < 8){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Weak Password','text'=>'Password must be at least 8 characters.'];
        header("Location: sign-up.php"); exit();
    }

    if(!preg_match('/[A-Z]/', $user_password)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Weak Password','text'=>'Password must contain at least one uppercase letter.'];
        header("Location: sign-up.php"); exit();
    }

    if(!preg_match('/[0-9]/', $user_password)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Weak Password','text'=>'Password must contain at least one number.'];
        header("Location: sign-up.php"); exit();
    }
    
    // terms validations 
    if(!isset($_POST['terms'])){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Terms Required','text'=>'You must accept the Terms and Conditions to register.'];
        header("Location: sign-up.php"); exit();
    }

    // phone validation
    if(!preg_match("/^[0-9]{11}$/", $user_phone)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Phone','text'=>'Phone number must be exactly 11 digits long.'];
        header("Location: sign-up.php"); exit();
    }

    // account type validation 
    $allowed = ['customer', 'lawyer'];
    if(!in_array($account_type, $allowed)){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Role','text'=>'Please select a valid account type.'];
        header("Location: sign-up.php"); exit();
    }

    // check duplicate email
    $check_result = mysqli_query($connection, "SELECT user_id FROM users WHERE user_email = '$user_email'");
    if(mysqli_num_rows($check_result) > 0){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Email Taken','text'=>'This email is already registered. Please sign in.'];
        header("Location: sign-up.php"); exit();
    }

    // hash and insert
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO users (user_name, user_email, user_password, user_phone) VALUES ('$user_name', '$user_email', '$hashed_password', '$user_phone')";
    $execute = mysqli_query($connection, $insert_query);

    if($execute){
        // fetch created user
        $user_result = mysqli_query($connection, "SELECT * FROM users WHERE user_email = '$user_email'");
        $user = mysqli_fetch_assoc($user_result);

        // set session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['user_email'] = $user['user_email'];
        $_SESSION['user_role'] = $user['user_role'];

        // redirections according to roles
        if($account_type == 'lawyer'){
            $_SESSION['is_applying_as_lawyer'] = 1;
            $_SESSION['_swal'] = ['icon'=>'success','title'=>'Account Created!','text'=>'Please complete your lawyer application.','redirect'=>'../apply_lawyer.php'];
            header("Location: sign-up.php"); exit();
        } else {
            $_SESSION['is_applying_as_lawyer'] = 0;
            $_SESSION['_swal'] = ['icon'=>'success','title'=>'Welcome!','text'=>'Registration successful. Redirecting to home…','redirect'=>'../../index.php'];
            header("Location: sign-up.php"); exit();
        }
    } else {
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Server Error','text'=>'Something went wrong. Please try again.'];
        header("Location: sign-up.php"); exit();
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
  <title>Sign Up - Legalcare</title>
  <!-- Fonts -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- Material Dashboard CSS -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <!-- SweetAlert2 — MUST be in <head> so it's available for the flash script at page end -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="">
  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                   style="background-image: url('../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Sign Up</h4>
                  <p class="mb-0">Enter your details to create an account</p>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" id="signUpForm" novalidate>

                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Full Name</label>
                      <input type="text" class="form-control" name="user_name" id="user_name"
                             minlength="3" maxlength="50" required>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email Address</label>
                      <input type="email" class="form-control" name="user_email" id="user_email"
                             maxlength="100" required>
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" class="form-control" name="user_password" id="user_password" required>
                    </div>
                    <p class="text-xs text-secondary mb-3 ms-1">
                      Min 8 characters, one uppercase letter &amp; one number.
                    </p>

                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Phone</label>
                      <input type="tel" class="form-control" name="user_phone" id="user_phone" required maxlength="11">
                    </div>

                    <div class="input-group input-group-outline mb-3">
                      <select class="form-control" name="account_type" id="account_type" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="customer">Customer</option>
                        <option value="lawyer">Apply as Lawyer</option>
                      </select>
                    </div>

                    <div class="form-check form-check-info text-start ps-0">
                      <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1">
                      <label class="form-check-label" for="terms">
                        I agree to the
                        <a href="../terms.php" class="text-dark font-weight-bolder">Terms and Conditions</a>
                      </label>
                    </div>

                    <div class="text-center">
                      <button type="submit" class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0" name="sign-up" id="signUpBtn">
                        Sign Up
                      </button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-2 text-sm mx-auto">
                    Already have an account?
                    <a href="sign-in.php" class="text-primary text-gradient font-weight-bold">Sign in</a>
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
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Material Dashboard JS -->
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

  <!-- ── Session flash (rendered after Swal is loaded) ─────────── -->
  <?php if(isset($_SESSION['_swal'])): ?>
  <script>
    var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
    <?php unset($_SESSION['_swal']); ?>
    Swal.fire({
      icon:  _swal.icon,
      title: _swal.title,
      text:  _swal.text
    }).then(function() {
      if(_swal.redirect) window.location.href = _swal.redirect;
    });
  </script>
  <?php endif; ?>

</body>
</html>