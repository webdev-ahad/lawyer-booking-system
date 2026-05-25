<?php
include("./base/header.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $select_query = "SELECT lp.*, u.user_name, u.user_email FROM lawyer_profiles lp INNER JOIN users u ON lp.user_id = u.user_id WHERE lp.lawyer_profile_id = '$id'";
    $result = mysqli_query($connection, $select_query);
    $lawyer = mysqli_fetch_assoc($result);

    if(!$lawyer){
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Lawyer not found', 'text' => 'The requested lawyer does not exist.'];
        header("Location: ./attorneys.php");
        exit();
    }
}else{
    $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Invalid request', 'text' => 'Invalid request.'];
    header("Location: ./attorneys.php");
    exit();
}
?>
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
            <h1 class="mb-3 bread"><?php echo $lawyer['user_name']; ?></h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span class="mr-2"><a href="attorneys.php">Attorneys <i class="ion-ios-arrow-forward"></i></a></span> <span>Profile <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>

    <!-- ── Premium Profile Section ─────────────────────────────────────────── -->
    <style>
      /* Profile page extras (scoped) */
      .lp-profile-section { padding: 70px 0; }

      /* Sidebar card */
      .lp-sidebar-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 20px 60px rgba(0,122,204,.12), 0 4px 20px rgba(0,0,0,.06);
        overflow: hidden;
      }
      .lp-sidebar-photo {
        width: 100%; aspect-ratio: 4/4;
        background: #ddeeff;
        position: relative; overflow: hidden;
      }
      .lp-sidebar-photo img {
        width: 100%; height: 100%; object-fit: cover; display: block;
      }
      .lp-sidebar-photo-overlay {
        position: absolute; bottom: 0; left: 0; right: 0;
        background: linear-gradient(0deg, rgba(0,15,35,.72) 0%, transparent 100%);
        padding: 18px 18px 14px;
        display: flex; align-items: flex-end; justify-content: space-between;
      }
      .lp-city-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(0,122,204,.85); color: #fff;
        font-size: 11px; font-weight: 600; letter-spacing: .08em;
        text-transform: uppercase; padding: 5px 12px;
        border-radius: 50px; backdrop-filter: blur(4px);
      }
      .lp-sidebar-body { padding: 22px 22px 26px; }
      .lp-sidebar-name {
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px; font-weight: 700; color: #1a1a2e; margin: 0 0 4px;
      }
      .lp-sidebar-role {
        font-size: 11px; font-weight: 600; letter-spacing: .1em;
        text-transform: uppercase; color: #007acc; margin-bottom: 16px;
      }

      /* Stat chips */
      .lp-stat-row { display: flex; gap: 10px; margin-bottom: 18px; }
      .lp-stat-chip {
        flex: 1; background: linear-gradient(135deg,#f0f8ff,#e8f4fd);
        border: 1.5px solid rgba(0,122,204,.14);
        border-radius: 12px; padding: 12px 10px; text-align: center;
      }
      .lp-stat-chip-num {
        display: block; font-size: 18px; font-weight: 800; color: #007acc; line-height: 1;
      }
      .lp-stat-chip-lbl {
        display: block; font-size: 9px; text-transform: uppercase;
        letter-spacing: .1em; color: #7a90a8; margin-top: 4px; font-weight: 600;
      }

      /* Quick detail rows */
      .lp-detail-item {
        display: flex; align-items: flex-start; gap: 11px;
        padding: 11px 0; border-bottom: 1px solid rgba(0,122,204,.07);
      }
      .lp-detail-item:last-child { border-bottom: none; }
      .lp-detail-icon {
        width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
        background: rgba(0,122,204,.1); color: #007acc;
        display: flex; align-items: center; justify-content: center; font-size: 14px;
      }
      .lp-detail-lbl {
        font-size: 10px; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: #007acc; display: block; margin-bottom: 2px;
      }
      .lp-detail-val {
        font-size: 14px; font-weight: 500; color: #1a1a2e; display: block;
      }
      .lp-detail-fee { font-size: 16px; font-weight: 800; color: #007acc; }

      /* Back link */
      .lp-back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 11px; font-weight: 600; letter-spacing: .1em;
        text-transform: uppercase; color: #007acc; text-decoration: none;
        transition: gap .2s, color .2s;
      }
      .lp-back-link:hover { gap: 10px; color: #005fa3; text-decoration: none; }
      .lp-back-link svg { width: 13px; height: 13px; stroke: currentColor; fill: none;
        stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }

      /* Right column */
      .lp-right-section { }
      .lp-section-eyebrow {
        font-size: 11px; font-weight: 700; letter-spacing: .14em;
        text-transform: uppercase; color: #007acc; margin-bottom: 6px;
      }
      .lp-section-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 30px; font-weight: 700; color: #1a1a2e; margin-bottom: 16px; line-height: 1.2;
      }
      .lp-bio {
        font-size: 15px; line-height: 1.85; color: #4a4a5a;
        font-weight: 400; margin-bottom: 28px;
      }

      /* Info card (right) */
      .lp-info-card {
        background: linear-gradient(135deg,#f8fbff,#eef5ff);
        border: 1.5px solid rgba(0,122,204,.12);
        border-radius: 18px; padding: 26px 28px 20px; margin-bottom: 28px;
      }
      .lp-info-card-title {
        font-size: 11px; font-weight: 700; letter-spacing: .12em;
        text-transform: uppercase; color: #7a90a8; margin-bottom: 18px;
      }
      .lp-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px 24px; }
      @media(max-width:576px){ .lp-info-grid { grid-template-columns: 1fr; } }
      .lp-info-item { }
      .lp-info-lbl {
        font-size: 10px; font-weight: 700; letter-spacing: .1em;
        text-transform: uppercase; color: #007acc; display: block; margin-bottom: 3px;
      }
      .lp-info-val {
        font-size: 15px; font-weight: 500; color: #1a1a2e; display: block;
      }
      .lp-fee-val { color: #007acc; font-weight: 800; font-size: 17px; }

      /* Practice area tags */
      .lp-tags-block { margin-bottom: 10px; }
      .lp-tags-title {
        font-size: 11px; font-weight: 700; letter-spacing: .12em;
        text-transform: uppercase; color: #7a90a8; margin-bottom: 12px;
      }
      .lp-tags { display: flex; flex-wrap: wrap; gap: 8px; }
      .lp-tag {
        display: inline-block; padding: 6px 14px; font-size: 12px; font-weight: 600;
        color: #007acc; background: rgba(0,122,204,.08);
        border: 1.5px solid rgba(0,122,204,.22); border-radius: 50px;
        transition: background .2s, border-color .2s, transform .15s;
      }
      .lp-tag:hover {
        background: rgba(0,122,204,.16); border-color: #007acc; transform: translateY(-1px);
      }
    </style>

    <section class="lp-profile-section">
      <div class="container">
        <div class="row">

          <!-- ── LEFT: Sidebar card ── -->
          <div class="col-lg-4 mb-5 mb-lg-0 lp-profile-sticky">
            <div class="lp-sidebar-card">

              <!-- Photo -->
              <div class="lp-sidebar-photo">
                <img src="uploads/<?php echo $lawyer['lawyer_profile_photo']; ?>"
                     alt="<?php echo $lawyer['user_name']; ?>">
                <div class="lp-sidebar-photo-overlay">
                  <span class="lp-city-chip">
                    <i class="ion-ios-pin" style="font-size:12px"></i>
                    <?php echo $lawyer['lawyer_city']; ?>
                  </span>
                </div>
              </div>

              <!-- Body -->
              <div class="lp-sidebar-body">
                <p class="lp-sidebar-name"><?php echo $lawyer['user_name']; ?></p>
                <p class="lp-sidebar-role">Legalcare Attorney</p>

                <!-- Stats -->
                <div class="lp-stat-row">
                  <div class="lp-stat-chip">
                    <span class="lp-stat-chip-num"><?php echo $lawyer['lawyer_experience_years']; ?>+</span>
                    <span class="lp-stat-chip-lbl">Yrs Exp.</span>
                  </div>
                  <div class="lp-stat-chip">
                    <span class="lp-stat-chip-num" style="font-size:13px">Rs <?php echo number_format($lawyer['lawyer_consultation_fee']); ?></span>
                    <span class="lp-stat-chip-lbl">Fee</span>
                  </div>
                </div>

                <!-- Back link -->
                <div class="mt-4 pt-2" style="border-top:1.5px solid rgba(0,122,204,.1)">
                  <a href="attorneys.php" class="lp-back-link">
                    <svg viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="11 6 5 12 11 18"/></svg>
                    All attorneys
                  </a>
                </div>

              </div>
            </div>
          </div>
          <!-- /sidebar -->

          <!-- ── RIGHT: Detail content ── -->
          <div class="col-lg-8 pl-lg-5">

            <p class="lp-section-eyebrow">Legalcare Attorney</p>
            <h2 class="lp-section-title"><?php echo $lawyer['user_name']; ?></h2>
            <p class="lp-bio"><?php echo $lawyer['lawyer_bio']; ?></p>

            <!-- Info card -->
            <div class="lp-info-card">
              <p class="lp-info-card-title">Office &amp; Consultation Details</p>
              <div class="lp-info-grid">
                <div class="lp-info-item">
                  <span class="lp-info-lbl">City</span>
                  <span class="lp-info-val"><?php echo $lawyer['lawyer_city']; ?></span>
                </div>
                <div class="lp-info-item">
                  <span class="lp-info-lbl">Experience</span>
                  <span class="lp-info-val"><?php echo $lawyer['lawyer_experience_years']; ?> years</span>
                </div>
                <div class="lp-info-item">
                  <span class="lp-info-lbl">Consultation Fee</span>
                  <span class="lp-info-val lp-fee-val">Rs <?php echo number_format($lawyer['lawyer_consultation_fee']); ?></span>
                </div>
                <div class="lp-info-item">
                  <span class="lp-info-lbl">Address</span>
                  <span class="lp-info-val"><?php echo $lawyer['lawyer_address']; ?></span>
                </div>
              </div>
            </div>

            <!-- Practice areas -->
            <?php
            $services_query = "SELECT pa.practice_area_name FROM lawyer_services ls INNER JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id WHERE ls.lawyer_profile_id = '$id'";
            $services = mysqli_query($connection, $services_query);
            ?>
            <div class="lp-tags-block">
              <p class="lp-tags-title">Practice Areas</p>
              <div class="lp-tags">
                <?php
                if(mysqli_num_rows($services) == 0){
                    echo "<p class='text-muted' style='font-size:14px'>No services added yet.</p>";
                }else{
                    while($s = mysqli_fetch_assoc($services)){
                        echo '<span class="lp-tag">' . $s['practice_area_name'] . '</span>';
                    }
                }
                ?>
              </div>
            </div>

            <div class="lp-tags-block">
              <p class="lp-tags-title">Book Availiable Slots</p>
              <div class="lp-tags">
                  <a class="btn btn-primary w-100 px-3 py-3" href="#slots">View Availiable Slots
                    <i class="ion-ios-arrow-right ml-2" style="font-size:1.2rem"></i>
                  </a>
              </div>
            </div>
          </div>
          <!-- /right col -->

        </div>
      </div>
    </section>

    <!-- ── Available Slots ─────────────────────────────────────────────────── -->
<?php
$today = date("Y-m-d");
$current_time = date("H:i:s");

$slots_query = "SELECT * FROM time_slots WHERE lawyer_profile_id = '$id' AND slot_status = 'available' AND (slot_date > '$today' OR (slot_date = '$today' AND slot_time > '$current_time')) ORDER BY slot_date, slot_time";
$slots = mysqli_query($connection, $slots_query);
?>
    <section class="ftco-section bg-light" id="slots">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-10 col-lg-8 text-center heading-section ftco-animate">
            <span class="subheading">Book a Consultation</span>
            <h2 class="mb-3">Available Time Slots</h2>
            <p class="mb-0 lp-profile-slots-intro">Reserve a session with <?php echo $lawyer['user_name']; ?> at a time that suits you.</p>
          </div>
        </div>
        <div class="row justify-content-center">
        <?php
        if(mysqli_num_rows($slots) == 0){
          echo "<div class='col-lg-8 text-center ftco-animate'>
            <div class='lp-profile-slots-empty bg-white rounded p-5'>
              <div class='lp-profile-slots-empty-ico' aria-hidden='true'><span class='flaticon-lawyer'></span></div>
              <p class='lp-profile-slots-empty-title mb-2'>No open slots at the moment</p>
              <p class='text-muted mb-0'>Please try again later or contact another attorney from our directory.</p>
              <p class='mb-0 mt-4'><a href='attorneys.php' class='btn btn-primary py-2 px-4'>Browse attorneys</a></p>
            </div>
          </div>";
        }
        while($slot = mysqli_fetch_assoc($slots)){
        ?>
          <div class="col-md-6 col-lg-4 ftco-animate mb-4">
            <div class="lp-profile-slot bg-white rounded h-100 d-flex flex-column">
              <div class="lp-profile-slot-bar"></div>
              <div class="p-4 d-flex flex-column flex-grow-1">
                <div class="lp-profile-slot-field mb-3">
                  <span class="lp-profile-slot-label">Date</span>
                  <span class="lp-profile-slot-value"><?php echo date("d M Y", strtotime($slot['slot_date'])); ?></span>
                </div>
                <div class="lp-profile-slot-field mb-4">
                  <span class="lp-profile-slot-label">Time</span>
                  <span class="lp-profile-slot-value"><?php echo date("h:i A", strtotime($slot['slot_time'])); ?></span>
                </div>
                <a href="appointments.php?slot_id=<?php echo $slot['slot_id']; ?>" class="btn btn-primary mt-auto align-self-start py-2 px-4">
                  Book Now <span class="ion-ios-arrow-forward"></span>
                </a>
              </div>
            </div>
          </div>
          <?php
            }
          ?>
        </div>
      </div>
    </section>

<?php include("./base/footer.php"); ?>