<?php
include("./base/header.php");

if(isset($_POST['edit_profile'])){
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['user_role'];
    $user_name  = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_phone = $_POST['user_phone'];

    // validations
    if(empty($user_name) || empty($user_email)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Fields','text'=>'Name and email are required.'];
        header("Location: edit_profile.php");
         exit();
    }

    // name 
    if(!preg_match("/^[a-zA-Z\s]+$/", $user_name) || strlen($user_name) < 3 || strlen($user_name) > 50){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Name','text'=>'Name must be 3-50 characters, letters and spaces only.'];
        header("Location: edit_profile.php"); exit();
    }
    
    // email
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Email','text'=>'Please enter a valid email address.'];
        header("Location: edit_profile.php"); exit();
    }

    // phone optional but if provided must be valid
    if(!empty($user_phone) && !preg_match('/^[0-9]{11}$/', $user_phone)){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Phone','text'=>'Phone must be in format 11 digits.'];
        header("Location: edit_profile.php"); exit();
    }

    // check duplicate email (exclude current user)
    $check_query = "SELECT user_id FROM users WHERE user_email = '$user_email' AND user_id != $user_id";
    $check_result = mysqli_query($connection, $check_query);
    if(mysqli_num_rows($check_result) > 0){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Email Taken','text'=>'This email is already used by another account.'];
        header("Location: edit_profile.php"); exit();
    }

    // update users table
    $user_query  = "UPDATE users SET user_name='$user_name', user_email='$user_email', user_phone='$user_phone' WHERE user_id=$user_id";
    $user_result = mysqli_query($connection, $user_query);

    if(!$user_result){
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Database Error','text'=>'Could not update basic info. Please try again.'];
        header("Location: edit_profile.php"); exit();
    }

    // update session name so navbar updates immediately
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;

    // lawyer edit form
    if($role === 'lawyer'){
        $lawyer_address = $_POST['lawyer_address'];
        $lawyer_city = $_POST['lawyer_city'];
        $lawyer_bio = $_POST['lawyer_bio'];
        $lawyer_exp = $_POST['lawyer_experience_years'];
        $lawyer_fee = $_POST['lawyer_consultation_fee'];
        $lawyer_bar = $_POST['lawyer_bar_number'];

        // validations
        if(empty($lawyer_city) || empty($lawyer_bio) || empty($lawyer_bar) || empty($lawyer_address) || empty($lawyer_exp) || empty($lawyer_fee)){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Fields','text'=>'City, bio, bar number, address, experience and fee are required.'];
            header("Location: edit_profile.php"); exit();
        }
        
        if(strlen($lawyer_bio) < 20 || strlen($lawyer_bio) > 500){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Bio','text'=>'Bio must be between 20 and 500 characters.'];
            header("Location: edit_profile.php"); exit();
        } 

        if(strlen($lawyer_address) < 10 || strlen($lawyer_address) > 250){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Address','text'=>'Address must be between 10 and 250 characters.'];
            header("Location: edit_profile.php"); exit();
        }

        if(!preg_match("/^[a-zA-Z\s]+$/", $lawyer_city) || strlen($lawyer_city) < 3 || strlen($lawyer_city) > 50){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid City','text'=>'City must be 3-50 characters, letters and spaces only.'];
            header("Location: edit_profile.php"); exit();
        }

        if(strlen($lawyer_bar) < 3 || strlen($lawyer_bar) > 50){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Bar','text'=>'Bar must be 3-50 characters.'];
            header("Location: edit_profile.php"); exit();
        } 

        if(!is_numeric($lawyer_exp) || $lawyer_exp < 0 || $lawyer_exp > 60){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Experience','text'=>'Experience must be a number between 0 and 60.'];
            header("Location: edit_profile.php"); exit();
        }

        if(!is_numeric($lawyer_fee) || $lawyer_fee < 0){
            $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Fee','text'=>'Consultation fee must be a positive number.'];
            header("Location: edit_profile.php"); exit();
        }

        // image handling
        $photo_sql = "";
        if (!empty($_FILES['lawyer_profile_photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['lawyer_profile_photo']['name'], PATHINFO_EXTENSION));

            if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {

                $upload_dir = "../uploads/";

                $photo_name = time() . "_" . rand(1000, 9999) . "." . $ext;
                $target_path = $upload_dir . $photo_name;

                if (move_uploaded_file($_FILES['lawyer_profile_photo']['tmp_name'], $target_path)) {
                    $photo_sql = ", lawyer_profile_photo='$photo_name'";
                }
            }
        }
        

        $lawyer_query = "UPDATE lawyer_profiles SET lawyer_address='$lawyer_address', lawyer_city='$lawyer_city', lawyer_bio='$lawyer_bio', lawyer_experience_years='$lawyer_exp', lawyer_consultation_fee='$lawyer_fee', lawyer_bar_number='$lawyer_bar' $photo_sql WHERE user_id=$user_id";
        $lawyer_result = mysqli_query($connection, $lawyer_query);

        if (!$lawyer_result) {
            $_SESSION['_swal'] = ['icon'=>'error','title'=>'Database Error','text'=>'Something went wrong. Please try again.'];
            header("Location: edit_profile.php");
            exit();
        }
    }

    $_SESSION['_swal'] = ['icon'=>'success','title'=>'Profile Updated!','text'=>'Your profile has been updated successfully.','redirect'=>'profile.php'];
    header("Location: profile.php"); exit();
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
        Edit Profile - Lawyer Admin
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<style>
body.ep-page {
  background: #f0f2f5;
  min-height: 100vh;
  font-family: 'Inter', sans-serif;
}
.ep-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2.5rem 1rem;
}
.ep-card {
  background: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 12px 48px rgba(0,0,0,.13);
  overflow: hidden;
  width: 100%;
  max-width: 960px;
  display: flex;
}
/* Left hero */
.ep-hero {
  width: 320px;
  flex-shrink: 0;
  background: linear-gradient(160deg, #007acc 0%, #003d66 100%);
  padding: 2.5rem 1.75rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
}
.ep-hero::before {
  content: '';
  position: absolute;
  top: -50px; right: -50px;
  width: 190px; height: 190px;
  border-radius: 50%;
  background: rgba(255,255,255,.07);
}
.ep-hero::after {
  content: '';
  position: absolute;
  bottom: -70px; left: -40px;
  width: 240px; height: 240px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
}
.ep-hero-icon {
  width: 56px; height: 56px;
  background: rgba(255,255,255,.15);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 1.25rem;
  position: relative; z-index: 1;
}
.ep-hero-icon .material-symbols-rounded { font-size: 1.8rem; color: #fff; }
.ep-hero h2 {
  color: #fff; font-size: 1.45rem; font-weight: 800;
  margin: 0 0 .4rem;
  position: relative; z-index: 1;
}
.ep-hero p {
  color: rgba(255,255,255,.7); font-size: .82rem;
  line-height: 1.65; position: relative; z-index: 1;
}
.ep-checklist {
  list-style: none; padding: 0; margin: 1.25rem 0 0;
  position: relative; z-index: 1;
}
.ep-checklist li {
  display: flex; align-items: center; gap: .45rem;
  color: rgba(255,255,255,.82); font-size: .78rem;
  margin-bottom: .5rem;
}
.ep-checklist li .material-symbols-rounded { font-size: .95rem; color: #7dd4fc; }
.ep-back {
  display: inline-flex; align-items: center; gap: .35rem;
  color: rgba(255,255,255,.65); font-size: .76rem; text-decoration: none;
  position: relative; z-index: 1;
  transition: color .2s;
}
.ep-back:hover { color: #fff; }
/* Right form */
.ep-form-panel {
  flex: 1;
  padding: 2.25rem 2rem;
  overflow-y: auto;
  max-height: 90vh;
}
.ep-section-title {
  font-size: .72rem;
  font-weight: 800;
  color: #007acc;
  text-transform: uppercase;
  letter-spacing: .08em;
  margin: 1.5rem 0 .75rem;
  padding-bottom: .4rem;
  border-bottom: 2px solid rgba(0,122,204,.15);
}
.ep-section-title:first-child { margin-top: 0; }
.ep-field {
  margin-bottom: .85rem;
}
.ep-field label {
  display: flex; align-items: center; gap: .3rem;
  font-size: .75rem; font-weight: 600; color: #344767;
  margin-bottom: .35rem;
}
.ep-field label .material-symbols-rounded { font-size: .9rem; color: #007acc; }
.ep-field .form-control {
  border: 1.5px solid rgba(0,0,0,.12);
  border-radius: 10px;
  font-size: .84rem;
  padding: .55rem .85rem;
  color: #344767;
  background: #f8fafc;
  transition: border-color .2s, box-shadow .2s;
}
.ep-field .form-control:focus {
  border-color: #007acc;
  box-shadow: 0 0 0 3px rgba(0,122,204,.12);
  background: #fff;
  outline: none;
}
.ep-save-btn {
  width: 100%;
  padding: .7rem;
  border: none;
  border-radius: 11px;
  background: linear-gradient(135deg, #007acc, #005fa3);
  color: #fff;
  font-size: .88rem;
  font-weight: 700;
  letter-spacing: .02em;
  box-shadow: 0 5px 18px rgba(0,122,204,.32);
  cursor: pointer;
  margin-top: 1.5rem;
  transition: box-shadow .2s, transform .15s;
  display: flex; align-items: center; justify-content: center; gap: .45rem;
}
.ep-save-btn:hover { box-shadow: 0 7px 26px rgba(0,122,204,.45); transform: translateY(-1px); }
.ep-save-btn .material-symbols-rounded { font-size: 1rem; }
@media (max-width: 720px) {
  .ep-card { flex-direction: column; }
  .ep-hero { width: 100%; padding: 2rem 1.5rem; }
  .ep-form-panel { max-height: none; }
}
</style>

<body class="ep-page">
  <div class="ep-wrapper">
    <div class="ep-card">
      <?php
          // user data
          $user_id = $_SESSION['user_id'];
          $user_query = "SELECT * FROM users WHERE user_id = $user_id";
          $user_result = mysqli_query($connection, $user_query);
          $user = mysqli_fetch_assoc($user_result);

          // lawyer data (only if user is lawyer)
          $lawyer = [];
          if ($user['user_role'] === 'lawyer') {
              $lp_query = "SELECT * FROM lawyer_profiles WHERE user_id = $user_id";
              $lp_result = mysqli_query($connection, $lp_query);
              $lawyer = mysqli_fetch_assoc($lp_result);
          }

      ?>
      <!-- LEFT HERO -->
      <div class="ep-hero">
        <div>
          <div class="ep-hero-icon">
            <span class="material-symbols-rounded">manage_accounts</span>
          </div>
          <h2>Edit Profile</h2>
          <p>Update your personal and professional details below.</p>
          <ul class="ep-checklist">
            <li><span class="material-symbols-rounded">check_circle</span> Name &amp; contact info</li>
            <li><span class="material-symbols-rounded">check_circle</span> Address &amp; city</li>
            <?php if ($user['user_role'] === 'lawyer') { ?>
            <li><span class="material-symbols-rounded">check_circle</span> Bio &amp; experience</li>
            <li><span class="material-symbols-rounded">check_circle</span> Consultation fee</li>
            <li><span class="material-symbols-rounded">check_circle</span> Profile photo</li>
            <?php } ?>
          </ul>
        </div>
        <a class="ep-back" href="profile.php">
          <span class="material-symbols-rounded" style="font-size:.9rem">arrow_back</span>
          Back to Profile
        </a>
      </div>

      <!-- RIGHT FORM -->
      <div class="ep-form-panel">
        <form method="POST" enctype="multipart/form-data" id="editProfileForm" novalidate>
          <input type="hidden" name="user_id" value="<?php echo $user_id ?>">

          <!-- BASIC INFO -->
          <div class="ep-section-title">Basic Information</div>
          <div class="row">
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">badge</span> Full Name</label>
                <input type="text" class="form-control" name="user_name" id="ep_user_name" value="<?php echo $user['user_name'] ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">phone</span> Phone</label>
                <input type="text" class="form-control" name="user_phone" id="ep_user_phone" value="<?php echo $user['user_phone'] ?? '' ?>">
              </div>
            </div>
          </div>
          <div class="ep-field">
            <label><span class="material-symbols-rounded">mail</span> Email Address</label>
            <input type="email" class="form-control" name="user_email" id="ep_user_email" value="<?php echo $user['user_email'] ?>">
          </div>

          <?php if ($user['user_role'] === 'lawyer') { ?>

          <!-- LAWYER INFO -->
          <div class="ep-section-title">Professional Details</div>
          <div class="ep-field">
            <label><span class="material-symbols-rounded">description</span> Bio</label>
            <input type="text" class="form-control" name="lawyer_bio" id="ep_lawyer_bio" value="<?php echo $lawyer['lawyer_bio'] ?? '' ?>">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">location_on</span> Address</label>
                <input type="text" class="form-control" name="lawyer_address" id="ep_lawyer_address" value="<?php echo $lawyer['lawyer_address'] ?? '' ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">location_city</span> City</label>
                <input type="text" class="form-control" name="lawyer_city" id="ep_lawyer_city" value="<?php echo $lawyer['lawyer_city'] ?? '' ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">workspace_premium</span> Experience (Years)</label>
                <input type="number" class="form-control" name="lawyer_experience_years" id="ep_lawyer_experience_years" value="<?php echo $lawyer['lawyer_experience_years'] ?? 0 ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="ep-field">
                <label><span class="material-symbols-rounded">payments</span> Consultation Fee (Rs)</label>
                <input type="number" class="form-control" name="lawyer_consultation_fee" id="ep_lawyer_consultation_fee" value="<?php echo $lawyer['lawyer_consultation_fee'] ?? 0 ?>">
              </div>
            </div>
          </div>
          <div class="ep-field">
            <label><span class="material-symbols-rounded">id_card</span> Bar Number</label>
            <input type="text" class="form-control" name="lawyer_bar_number" id="ep_lawyer_bar_number" value="<?php echo $lawyer['lawyer_bar_number'] ?? '' ?>">
          </div>
          <div class="ep-field">
            <label><span class="material-symbols-rounded">photo_camera</span> Profile Picture</label>
            <input type="file" class="form-control" name="lawyer_profile_photo" id="ep_lawyer_profile_photo" accept="image/*">
          </div>

          <?php } ?>

          <button type="submit" name="edit_profile" class="ep-save-btn">
            <span class="material-symbols-rounded">save</span>
            Save Changes
          </button>

        </form>
      </div>

    </div>
  </div>
</body>

<?php if(isset($_SESSION['_swal'])){ ?>
<script>
  var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
  <?php unset($_SESSION['_swal']); ?>
  Swal.fire({
    icon:  _swal.icon,
    title: _swal.title,
    text:  _swal.text
  }).then(function(){
    if(_swal.redirect) window.location.href = _swal.redirect;
  });
</script>
<?php }?>

</html>
<?php
include("./base/footer.php");
?>