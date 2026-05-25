<?php
include("./base/header.php");

if (!isset($_SESSION['user_id'])) {
  header("Location: auth/sign-in.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// fetch user info
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($connection, $user_query);

// user not found validation 
if (!$result || mysqli_num_rows($result) == 0) {
  $_SESSION['_swal'] = ['icon'=>'error','title'=>'User Not Found','text'=>'Something went wrong. Please try again.'];
  header("Location: ./auth/sign-in.php");
  exit();
}

$user = mysqli_fetch_assoc($result);

// if user is lawyer then fetch lawyer profile info 
$lawyer_profile = []; // default empty array prevents from errors if no profile found

if ($user['user_role'] === 'lawyer') {
  $lp_query = "SELECT * FROM lawyer_profiles WHERE user_id = $user_id";
  $lp_result = mysqli_query($connection, $lp_query);
  $lawyer_profile = mysqli_fetch_assoc($lp_result) ?? [];
}
?>

<style>
/* ── Cover Banner ── */
.profile-cover-wrap {
  position: relative;
  margin-top: 1.5rem;
  padding-bottom: 60px; /* space for avatar to hang below */
}
.profile-cover {
  height: 240px;
  border-radius: 1.25rem;
  background-image: url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1600&q=80');
  background-size: cover;
  background-position: center 30%;
  position: relative;
  overflow: hidden;
  box-shadow: 0 10px 40px rgba(0,100,180,.22);
}
.profile-cover .gradient-mask {
  position: absolute; inset: 0;
  background: linear-gradient(
    130deg,
    rgba(0,80,150,.55) 0%,
    rgba(0,30,70,.80) 100%
  );
}
/* subtle grid pattern overlay */
.profile-cover .pattern {
  position: absolute; inset: 0; z-index: 1;
  background-image: radial-gradient(rgba(255,255,255,.08) 1px, transparent 1px);
  background-size: 24px 24px;
}
/* decorative circles */
.profile-cover::after {
  content: ''; position: absolute;
  width: 320px; height: 320px; border-radius: 50%;
  background: rgba(255,255,255,.06);
  right: -80px; bottom: -120px; z-index: 1;
}
.profile-cover::before {
  content: ''; position: absolute;
  width: 180px; height: 180px; border-radius: 50%;
  background: rgba(255,255,255,.05);
  right: 120px; top: -60px; z-index: 1;
}

/* ── Avatar ── */
.profile-avatar {
  position: absolute;
  bottom: 0;
  left: 2rem;
  z-index: 10;
}
.profile-avatar img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 5px solid #fff;
  box-shadow: 0 8px 28px rgba(0,100,180,.3);
  object-fit: cover;
  display: block;
  background: #e8f0fe;
}
.profile-avatar .online-dot {
  position: absolute;
  bottom: 8px; right: 8px;
  width: 16px; height: 16px;
  border-radius: 50%;
  background: #2dce89;
  border: 3px solid #fff;
  box-shadow: 0 2px 6px rgba(45,206,137,.4);
}

/* ── Main Card ── */
.profile-card {
  border-radius: 1.25rem;
  border: none;
  box-shadow: 0 4px 30px rgba(0,0,0,.09);
  background: #fff;
  overflow: visible;
}

/* ── Identity Header (inside card) ── */
.profile-card-header {
  display: flex;
  align-items: flex-end;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 1rem 1.5rem 1.2rem calc(2rem + 132px); /* left-pad = avatar left + avatar width + gap */
  border-bottom: 1px solid rgba(0,0,0,.07);
  min-height: 72px;
}
.profile-identity {
  flex: 1;
}
.profile-identity h3 {
  font-size: 1.3rem;
  font-weight: 800;
  color: #1a2035;
  margin: 0 0 .25rem;
  line-height: 1.2;
}
.profile-identity .identity-meta {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: .45rem;
}
.profile-identity .identity-email {
  font-size: .78rem;
  color: #8392ab;
}

/* ── Role Badges ── */
.role-pill {
  display: inline-flex; align-items: center; gap: .25rem;
  font-size: .68rem; font-weight: 700; color: #fff;
  padding: .22rem .6rem; border-radius: 20px; letter-spacing: .04em;
}
.pill-admin   { background: linear-gradient(135deg,#3a86ff,#0057cc); }
.pill-lawyer  { background: linear-gradient(135deg,#2dce89,#0b7a4e); }
.pill-pending { background: linear-gradient(135deg,#fb8c00,#e65100); }

/* ── Action Buttons ── */
.profile-actions {
  display: flex;
  align-items: center;
  gap: .5rem;
  flex-wrap: wrap;
  padding-bottom: .25rem;
}
.btn-edit-profile {
  display: inline-flex; align-items: center; gap: .3rem;
  font-size: .78rem; font-weight: 600; color: #007acc;
  text-decoration: none; padding: .38rem .9rem;
  border-radius: 9px; border: 1.5px solid rgba(0,122,204,.25);
  background: rgba(0,122,204,.06);
  transition: all .2s;
  white-space: nowrap;
}
.btn-edit-profile:hover { background: rgba(0,122,204,.13); color: #005fa3; border-color: rgba(0,122,204,.45); }
.btn-edit-profile .material-symbols-rounded { font-size: .95rem; }

.btn-apply {
  display: inline-flex; align-items: center; gap: .3rem;
  font-size: .78rem; font-weight: 700; color: #fff;
  border: none; border-radius: 9px; padding: .38rem 1rem;
  text-decoration: none; transition: box-shadow .2s, transform .15s;
}
.btn-apply-blue { background: linear-gradient(135deg,#007acc,#005fa3); box-shadow: 0 4px 14px rgba(0,122,204,.3); }
.btn-apply-blue:hover  { box-shadow: 0 6px 20px rgba(0,122,204,.44); transform: translateY(-1px); color:#fff; }
.btn-apply-red  { background: linear-gradient(135deg,#ea2c62,#c0003f); box-shadow: 0 4px 14px rgba(234,44,98,.25); }
.btn-apply-red:hover   { box-shadow: 0 6px 20px rgba(234,44,98,.4);  transform: translateY(-1px); color:#fff; }

/* ── Stats Strip ── */
.profile-stats {
  display: flex; gap: 0;
  border-bottom: 1px solid rgba(0,0,0,.07);
}
.stat-item {
  flex: 1;
  text-align: center;
  padding: .9rem .5rem;
  border-right: 1px solid rgba(0,0,0,.07);
  transition: background .15s;
}
.stat-item:last-child { border-right: none; }
.stat-item:hover { background: rgba(0,122,204,.03); }
.stat-item strong {
  display: block;
  font-size: 1.1rem; font-weight: 800; color: #1a2035;
}
.stat-item span {
  font-size: .68rem; color: #8392ab; font-weight: 600;
  text-transform: uppercase; letter-spacing: .05em;
}

/* ── Info Card ── */
.info-section-title {
  font-size: .7rem; font-weight: 800; color: #007acc;
  text-transform: uppercase; letter-spacing: .08em;
  margin-bottom: .75rem;
  display: flex; align-items: center; gap: .3rem;
}
.info-section-title .material-symbols-rounded { font-size: .9rem; }
.info-row {
  display: flex; align-items: flex-start; gap: .75rem;
  padding: .6rem 0;
  border-bottom: 1px solid rgba(0,0,0,.05);
}
.info-row:last-child { border-bottom: none; }
.info-row-icon {
  width: 32px; height: 32px; border-radius: 9px;
  background: rgba(0,122,204,.08);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.info-row-icon .material-symbols-rounded { font-size: .95rem; color: #007acc; }
.info-row-label { font-size: .72rem; font-weight: 700; color: #344767; display: block; }
.info-row-value { font-size: .8rem; color: #67748e; display: block; margin-top: 1px; }
.profile-initials {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid #fff;
    box-shadow: 0 8px 28px rgba(0,100,180,.3);
    background: linear-gradient(135deg, #007acc, #005fa3);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.7rem;
    font-weight: 800;
    letter-spacing: -1px;
    text-transform: uppercase;
}
</style>
<div class="container-fluid px-2 px-md-4">

  <!-- ══ COVER + AVATAR ══ -->
  <div class="profile-cover-wrap mx-2 mx-md-3">

    <!-- Cover banner -->
    <div class="profile-cover">
      <div class="gradient-mask"></div>
      <div class="pattern"></div>
    </div>

    <!-- Avatar sits below cover, never clipped -->
    <div class="profile-avatar">
    <?php 
    if (!empty($lawyer_profile['lawyer_profile_photo'])){ ?>
        <img src="../uploads/<?php echo $lawyer_profile['lawyer_profile_photo']; ?>" alt="Profile Photo">
    <?php } else{ ?>
        <div class="profile-initials">
            <?php echo substr($user['user_name'], 0, 1); ?>
        </div>
    <?php } ?>
    <span class="online-dot"></span>
</div>

  </div>

  <!-- ══ MAIN CARD ══ -->
  <div class="profile-card mx-2 mx-md-3 mb-4">

    <!-- Identity Header -->
    <div class="profile-card-header">

      <div class="profile-identity">
        <h3><?php echo $user['user_name']; ?></h3>
        <div class="identity-meta">

          <?php if ($_SESSION['user_role'] === 'admin') { ?>
            <span class="role-pill pill-admin">
              <span class="material-symbols-rounded" style="font-size:.75rem">admin_panel_settings</span> Admin
            </span>
          <?php } ?>

          <?php if ($_SESSION['user_role'] === 'lawyer') { ?>
            <span class="role-pill pill-lawyer">
              <span class="material-symbols-rounded" style="font-size:.75rem">verified</span> Verified Lawyer
            </span>
          <?php } ?>

          <?php if ($_SESSION['user_role'] === 'customer') { ?>
            <span class="role-pill pill-admin" style="background:linear-gradient(135deg,#8392ab,#495361)">
              <span class="material-symbols-rounded" style="font-size:.75rem">person</span> Customer
            </span>
          <?php } ?>

          <span class="identity-email">
            <span class="material-symbols-rounded" style="font-size:.8rem;vertical-align:middle;color:#8392ab">mail</span>
            <?php echo $user['user_email']; ?>
          </span>

        </div>
      </div>

      <!-- Actions -->
      <div class="profile-actions">

        <?php
        if ($user['user_role'] === 'customer') {
          $query  = "SELECT request_status FROM lawyer_requests WHERE user_id = $user_id ORDER BY request_id DESC LIMIT 1";
          $result = mysqli_query($connection, $query);
          if (mysqli_num_rows($result) == 0) {
            echo '<a class="btn-apply btn-apply-blue" href="apply_lawyer.php">
                    <span class="material-symbols-rounded" style="font-size:.9rem">gavel</span> Apply as Lawyer
                  </a>';
          } else {
            $data = mysqli_fetch_assoc($result);
            if ($data['request_status'] == 'pending') {
              echo '<span class="role-pill pill-pending" style="padding:.3rem .8rem;font-size:.72rem">
                      <span class="material-symbols-rounded" style="font-size:.8rem">schedule</span> Application Pending
                    </span>';
            } elseif ($data['request_status'] == 'rejected') {
              echo '<a class="btn-apply btn-apply-red" href="apply_lawyer.php">
                      <span class="material-symbols-rounded" style="font-size:.9rem">refresh</span> Re-Apply
                    </a>';
            }
          }
        }
        ?>

        <a class="btn-edit-profile" href="edit_profile.php">
          <span class="material-symbols-rounded">edit</span> Edit Profile
        </a>

      </div>
    </div>

    <!-- Stats Strip (lawyers only) -->
    <?php if ($user['user_role'] === 'lawyer') { ?>
    <div class="profile-stats">
      <div class="stat-item">
        <strong><?php echo ($lawyer_profile['lawyer_experience_years'] ?? 0); ?></strong>
        <span>Experience</span>
      </div>
      <div class="stat-item">
        <strong><?php echo !empty($lawyer_profile['lawyer_city']) ? $lawyer_profile['lawyer_city'] : '—'; ?></strong>
        <span>City</span>
      </div>
      <div class="stat-item">
        <strong>$<?php echo number_format($lawyer_profile['lawyer_consultation_fee'] ?? 0); ?></strong>
        <span>Consult Fee</span>
      </div>
      <div class="stat-item">
        <strong><?php echo $lawyer_profile['lawyer_bar_number'] ?? '—'; ?></strong>
        <span>Bar No.</span>
      </div>
    </div>
    <?php } ?>

    <!-- Info Section -->
    <div class="card-body p-4">
      <div class="row g-4">
        <div class="col-12 col-lg-7">

          <div class="info-section-title">
            <span class="material-symbols-rounded">person</span> Profile Information
          </div>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">badge</span></div>
            <div>
              <span class="info-row-label">Full Name</span>
              <span class="info-row-value"><?php echo $user['user_name']; ?></span>
            </div>
          </div>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">mail</span></div>
            <div>
              <span class="info-row-label">Email Address</span>
              <span class="info-row-value"><?php echo $user['user_email']; ?></span>
            </div>
          </div>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">phone</span></div>
            <div>
              <span class="info-row-label">Phone</span>
              <span class="info-row-value"><?php echo $user['user_phone'] ?? 'Not set'; ?></span>
            </div>  
          </div>

          <?php if ($user['user_role'] === 'lawyer') { ?>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">location_on</span></div>
            <div>
              <span class="info-row-label">Address</span>
              <span class="info-row-value"><?php echo $lawyer_profile['lawyer_address'] ?? 'Not set'; ?></span>
            </div>
          </div>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">description</span></div>
            <div>
              <span class="info-row-label">Bio</span>
              <span class="info-row-value"><?php echo $lawyer_profile['lawyer_bio'] ?? 'No bio added'; ?></span>
            </div>
          </div>

          <div class="info-row">
            <div class="info-row-icon"><span class="material-symbols-rounded">verified</span></div>
            <div>
              <span class="info-row-label">Status</span>
              <span class="info-row-value">
                <span class="role-pill pill-lawyer" style="font-size:.66rem;padding:.18rem .5rem">
                  <span class="material-symbols-rounded" style="font-size:.74rem">check_circle</span> Approved
                </span>
              </span>
            </div>
          </div>

          <?php } ?>

        </div>
      </div>
    </div>

  </div>
</div>
<?php include("./base/footer.php"); ?>