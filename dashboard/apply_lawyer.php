<?php
include("./base/header.php");

// validate lawyer application
if (isset($_POST['request_approval'])) {

    $user_id = $_SESSION['user_id'];
    $lawyer_bio = $_POST['lawyer_bio'];
    $lawyer_address = $_POST['lawyer_address'];
    $lawyer_city = $_POST['lawyer_city'];
    $lawyer_experience_years = $_POST['lawyer_experience_years'];
    $lawyer_consultation_fee = $_POST['lawyer_consultation_fee'];
    $lawyer_bar_number = $_POST['lawyer_bar_number'];
    
    // validations
    if(empty($lawyer_bio) || empty($lawyer_address) || empty($lawyer_city) || empty($lawyer_consultation_fee) || empty($lawyer_bar_number) || empty($lawyer_experience_years)){
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Check your form','text'=>'Please complete all fields correctly.'];
        header("Location: apply_lawyer.php");
        exit();
    }

    if(strlen($lawyer_bio) < 20){
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Check your form','text'=>'Please enter at least 20 characters in your bio.'];
        header("Location: apply_lawyer.php");
        exit();
    }

    if(strlen($lawyer_address) < 10){
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Check your form','text'=>'Please enter at least 10 characters in your address.'];
        header("Location: apply_lawyer.php");
        exit();
    }
    
    if(!preg_match("/^[a-zA-Z\s]+$/", $lawyer_city)){
        
        $_SESSION['_swal'] = ['icon'=>'warning', 'title'=>'Invalid City', 'text'=>'City name can only contain letters.'];
        header("Location: apply_lawyer.php");
        exit();
    }

    if(!is_numeric($lawyer_consultation_fee) || $lawyer_consultation_fee < 0){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Fee','text'=>'Please enter a valid consultation fee.'];
        header("Location: apply_lawyer.php");
        exit();
    }
    
    if(strlen($lawyer_bar_number) < 5 || strlen($lawyer_bar_number) > 50){
        
        $_SESSION['_swal'] = ['icon'=>'warning', 'title'=>'Invalid Bar Number', 'text'=>'Please enter a valid bar registration number.'];
        header("Location: apply_lawyer.php");
        exit();
    }

    if(strlen($lawyer_experience_years) < 1 || strlen($lawyer_experience_years) > 60){
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Check your form','text'=>'Please enter a valid number of years of experience (1-60).'];
        header("Location: apply_lawyer.php");
        exit();
    }
    
    if (empty($_FILES['lawyer_profile_photo']['name']) || empty($_FILES['lawyer_profile_photo']['tmp_name'])) {
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Photo Required','text'=>'Please upload a profile picture (JPG or PNG).'];
        header("Location: apply_lawyer.php");
        exit();
    }

    // image handling
    $photo_name = "";
    $ext = strtolower(pathinfo($_FILES['lawyer_profile_photo']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
        
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid File','text'=>'Only JPG, JPEG, and PNG images are allowed.'];
        header("Location: apply_lawyer.php");
        exit();
    }

    $upload_dir = "../uploads/";
    $photo_name = time() . "_" . rand(1000, 9999) . "." . $ext;

    if (!move_uploaded_file($_FILES['lawyer_profile_photo']['tmp_name'], $upload_dir . $photo_name)) {
        
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Upload Failed','text'=>'Profile photo upload failed. Please try again.'];
        header("Location: apply_lawyer.php"); 
        exit();
    }

    $insert_query = "INSERT INTO lawyer_requests (user_id, request_bar_number, request_consultation_fee, request_city, request_address, request_experience_years, request_bio, request_profile_photo) VALUES ($user_id, '$lawyer_bar_number', '$lawyer_consultation_fee', '$lawyer_city', '$lawyer_address', '$lawyer_experience_years', '$lawyer_bio', '$photo_name')";
    $result = mysqli_query($connection, $insert_query);

    if ($result) {
        
        $_SESSION['_swal'] = ['icon'=>'success','title'=>'Application Submitted!','text'=>'Your lawyer application has been sent for review.','redirect'=>'index.php'];
        header("Location: index.php"); exit();
    } else {
        
        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Database Error','text'=>'Something went wrong. Please try again.'];
        header("Location: apply_lawyer.php"); exit();
    }
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
<style>
/* ── Apply Lawyer Page ── */
body.apply-page {
  background: #f0f2f5;
  min-height: 100vh;
  font-family: 'Inter', sans-serif;
}
.apply-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
}
.apply-card {
  background: #fff;
  border-radius: 1.25rem;
  box-shadow: 0 12px 48px rgba(0,0,0,.12);
  overflow: hidden;
  width: 100%;
  max-width: 900px;
  display: flex;
}
/* Left hero panel */
.apply-hero {
  width: 360px;
  flex-shrink: 0;
  background: linear-gradient(160deg, #007acc 0%, #003d66 100%);
  padding: 3rem 2rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: relative;
  overflow: hidden;
}
.apply-hero::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 220px; height: 220px;
  border-radius: 50%;
  background: rgba(255,255,255,.07);
}
.apply-hero::after {
  content: '';
  position: absolute;
  bottom: -80px; left: -50px;
  width: 280px; height: 280px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
}
.apply-hero-icon {
  width: 60px; height: 60px;
  background: rgba(255,255,255,.15);
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 1.5rem;
}
.apply-hero-icon .material-symbols-rounded { font-size: 2rem; color: #fff; }
.apply-hero h2 { color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0 0 .5rem; position: relative; z-index: 1; }
.apply-hero p  { color: rgba(255,255,255,.72); font-size: .85rem; line-height: 1.6; position: relative; z-index: 1; }
.apply-hero-checklist {
  list-style: none; padding: 0; margin: 1.5rem 0 0;
  position: relative; z-index: 1;
}
.apply-hero-checklist li {
  display: flex; align-items: center; gap: .5rem;
  color: rgba(255,255,255,.85); font-size: .8rem;
  margin-bottom: .6rem;
}
.apply-hero-checklist li .material-symbols-rounded { font-size: 1rem; color: #7dd4fc; }
.apply-hero-back {
  display: inline-flex; align-items: center; gap: .4rem;
  color: rgba(255,255,255,.7); font-size: .78rem; text-decoration: none;
  position: relative; z-index: 1;
  transition: color .2s;
}
.apply-hero-back:hover { color: #fff; }
/* Right form panel */
.apply-form-panel {
  flex: 1;
  padding: 2.5rem 2rem;
  overflow-y: auto;
}
.apply-form-panel h5 {
  font-weight: 700; font-size: 1.1rem; color: #344767; margin-bottom: 1.5rem;
  padding-bottom: .75rem;
  border-bottom: 1px solid rgba(0,0,0,.07);
}
.af-field {
  margin-bottom: 1rem;
}
.af-field label {
  display: flex; align-items: center; gap: .35rem;
  font-size: .78rem; font-weight: 600; color: #344767;
  margin-bottom: .4rem;
}
.af-field label .material-symbols-rounded { font-size: .95rem; color: #007acc; }
.af-field .form-control {
  border: 1.5px solid rgba(0,0,0,.12);
  border-radius: 10px;
  font-size: .85rem;
  padding: .6rem .9rem;
  color: #344767;
  transition: border-color .2s, box-shadow .2s;
  background: #f8fafc;
}
.af-field .form-control:focus {
  border-color: #007acc;
  box-shadow: 0 0 0 3px rgba(0,122,204,.12);
  background: #fff;
  outline: none;
}
.af-field textarea.form-control { resize: vertical; min-height: 80px; }
.af-submit-btn {
  width: 100%;
  padding: .75rem;
  border: none;
  border-radius: 12px;
  background: linear-gradient(135deg, #007acc, #005fa3);
  color: #fff;
  font-size: .9rem;
  font-weight: 700;
  letter-spacing: .02em;
  box-shadow: 0 6px 20px rgba(0,122,204,.35);
  cursor: pointer;
  transition: box-shadow .2s, transform .15s;
  display: flex; align-items: center; justify-content: center; gap: .5rem;
}
.af-submit-btn:hover { box-shadow: 0 8px 28px rgba(0,122,204,.48); transform: translateY(-1px); }
.af-submit-btn .material-symbols-rounded { font-size: 1.1rem; }
@media (max-width: 700px) {
  .apply-card { flex-direction: column; }
  .apply-hero { width: 100%; padding: 2rem 1.5rem; }
}
</style>

<body class="apply-page">
  <div class="apply-wrapper">
    <div class="apply-card">

      <!-- LEFT HERO -->
      <div class="apply-hero">
        <div>
          <div class="apply-hero-icon">
            <span class="material-symbols-rounded">gavel</span>
          </div>
          <h2>Join as a Lawyer</h2>
          <p>Submit your application to become a verified legal professional on our platform.</p>
          <ul class="apply-hero-checklist">
            <li><span class="material-symbols-rounded">check_circle</span> Fill in your professional details</li>
            <li><span class="material-symbols-rounded">check_circle</span> Upload a profile photo</li>
            <li><span class="material-symbols-rounded">check_circle</span> Provide your bar number</li>
            <li><span class="material-symbols-rounded">check_circle</span> Await admin approval</li>
          </ul>
        </div>
        <a class="apply-hero-back" href="profile.php">
          <span class="material-symbols-rounded" style="font-size:.95rem">arrow_back</span>
          Back to Profile
        </a>
      </div>

      <!-- RIGHT FORM -->
      <div class="apply-form-panel">
        <h5>Application Form</h5>

        <form method="POST" enctype="multipart/form-data" id="applyLawyerForm" novalidate>

          <div class="af-field">
            <label><span class="material-symbols-rounded">description</span> Bio</label>
            <textarea class="form-control" name="lawyer_bio" id="lawyer_bio" placeholder="Tell clients about yourself..."></textarea>
          </div>

          <div class="af-field">
            <label><span class="material-symbols-rounded">location_on</span> Address</label>
            <textarea class="form-control" name="lawyer_address" id="lawyer_address" placeholder="Your office address"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="af-field">
                <label><span class="material-symbols-rounded">location_city</span> City</label>
                <input type="text" class="form-control" name="lawyer_city" id="lawyer_city" placeholder="City">
              </div>
            </div>
            <div class="col-md-6">
              <div class="af-field">
                <label><span class="material-symbols-rounded">workspace_premium</span> Experience (Years)</label>
                <input type="number" class="form-control" name="lawyer_experience_years" min="0" max="60" id="lawyer_experience_years" placeholder="e.g. 5">
              </div>
            </div>
          </div>

          <div class="af-field">
            <label><span class="material-symbols-rounded">badge</span> Bar Number</label>
            <input type="text" class="form-control" name="lawyer_bar_number" id="lawyer_bar_number" placeholder="Your bar registration number">
          </div>
          <div class="af-field">
            <label><span class="material-symbols-rounded">payments</span> Consultation Fee (Rs)</label>
            <input type="number" class="form-control" name="lawyer_consultation_fee" id="lawyer_consultation_fee" placeholder="e.g. Rs 4,000" required>
          </div>
          <div class="af-field">
            <label><span class="material-symbols-rounded">photo_camera</span> Profile picture <span style="color:#c0392b">*</span></label>
            <input type="file" class="form-control" name="lawyer_profile_photo" id="lawyer_profile_photo" accept="image/*">
          </div>

          <div style="margin-top:1.5rem">
            <button type="submit" name="request_approval" class="af-submit-btn">
              <span class="material-symbols-rounded">send</span>
              Submit Application
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</body>
    <script>
        document.querySelectorAll('.input-group input').forEach(input => {
            if (input.value.trim() !== "") {
                input.parentElement.classList.add('is-filled');
            }
        });
    </script>
    <?php if (isset($_SESSION['_swal'])){ ?>
<script>
(function () {
  var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
  <?php unset($_SESSION['_swal']); ?>
  Swal.fire({ icon: _swal.icon, title: _swal.title, text: _swal.text || '' }).then(function () {
    if (_swal.redirect) window.location.href = _swal.redirect;
  });
})();
</script>
<?php } ?>
<?php
include("./base/footer.php");
?>