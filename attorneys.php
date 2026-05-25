<?php
		include("./base/header.php");

	?>
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
            <h1 class="mb-3 bread">Expert Attorneys</h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>Attorneys <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
    <?php
    // get city and service from url
        $city = isset($_GET['city']) ? $_GET['city'] : '';
        $service = isset($_GET['service']) ? $_GET['service'] : '';
        
        // $query to get all lawyers with their services and practice areas
        $query = "SELECT DISTINCT lp.*, u.user_name FROM lawyer_profiles lp INNER JOIN users u ON lp.user_id = u.user_id LEFT JOIN lawyer_services ls ON lp.lawyer_profile_id = ls.lawyer_profile_id WHERE 1=1";

        if($city != ''){
            $query .= " AND lp.lawyer_city = '".$city."'";
        }

        if($service != ''){
            $query .= " AND ls.practice_area_id = '".$service."'";
        }

        $query_result = mysqli_query($connection, $query);



    ?>
    <section class="at-section">
  <div class="container">

  <!-- ── Premium Search Filter Bar ─────────────────────────────── -->
  <div class="at-filter-bar">
    <div class="at-filter-bar-inner">
      <div class="at-filter-title">
        <i class="ion-ios-search"></i>
        <span>Find an Attorney</span>
      </div>
      <form method="GET" class="at-filter-form">
        <div class="at-filter-row">

          <!-- CITY -->
          <div class="at-filter-field">
            <label class="at-filter-label"><i class="ion-ios-pin"></i> City</label>
            <div class="at-select-wrap">
              <select name="city" class="form-control at-select">
                <option value="">All Cities</option>
                <option value="Karachi">Karachi</option>
                <option value="Lahore">Lahore</option>
                <option value="Islamabad">Islamabad</option>
                <option value="Multan">Multan</option>
                <option value="Peshawar">Peshawar</option>
                <option value="Faisalabad">Faisalabad</option>
              </select>
              <span class="at-select-arrow"><i class="ion-ios-arrow-down"></i></span>
            </div>
          </div>

          <!-- SERVICE -->
          <div class="at-filter-field">
            <label class="at-filter-label"><i class="ion-ios-briefcase"></i> Practice Area</label>
            <div class="at-select-wrap">
              <select name="service" class="form-control at-select">
                <option value="">All Services</option>
                <?php
                $services_query = "SELECT * FROM practice_areas";
                $services_result = mysqli_query($connection, $services_query);
                while($s = mysqli_fetch_assoc($services_result)){
                    echo "<option value='".$s['practice_area_id']."'>".$s['practice_area_name']."</option>";
                }
                ?>
              </select>
              <span class="at-select-arrow"><i class="ion-ios-arrow-down"></i></span>
            </div>
          </div>

          <!-- BUTTON -->
          <div class="at-filter-field at-filter-btn-field">
            <label class="at-filter-label">&nbsp;</label>
            <button type="submit" class="btn btn-primary at-filter-btn">
              <i class="ion-ios-search"></i> Search Attorneys
            </button>
          </div>

        </div>
      </form>
    </div>
  </div>

    <!-- Header -->
    <div class="at-header">
      <span class="subheading">Our Attorney</span>
      <h2 class="at-title">Meet Our Legal Team</h2>
      <p class="at-subtitle">Experienced attorneys dedicated to justice &amp; results</p>
    </div>
 
    <div class="at-grid">
     <?php if(mysqli_num_rows($query_result) > 0) {?>
     <?php while($row = mysqli_fetch_assoc($query_result)) { ?>

  <div class="at-card">
    <!-- Photo -->
    <div class="at-photo">
        <img src="uploads/<?php echo $row['lawyer_profile_photo']; ?>"
             alt="<?php echo $row['user_name']; ?>"
             style="width:100%;height:100%;object-fit:cover;">
        <div class="at-badge">
            <span class="at-badge-role">
                <i class="ion-ios-pin" style="font-size:11px;margin-right:3px"></i>
                <?php echo $row['lawyer_city']; ?>
            </span>
        </div>
    </div>

    <!-- Body -->
    <div class="at-body">
        <p class="at-name"><?php echo $row['user_name']; ?></p>
        <div class="at-sep"></div>

        <p class="at-quote">
            <?php echo mb_substr($row['lawyer_bio'], 0, 90); ?>…
        </p>

        <!-- Stats row -->
        <div class="at-stats">
            <div class="at-stat">
                <span class="at-stat-num"><?php echo $row['lawyer_experience_years']; ?>+</span>
                <span class="at-stat-lbl">Yrs exp.</span>
            </div>
            <div class="at-stat">
                <span class="at-stat-num">Rs <?php echo number_format($row['lawyer_consultation_fee']); ?></span>
                <span class="at-stat-lbl">Fee</span>
            </div>
        </div>

        <!-- Practice area tags -->
        <?php
        $services_query = "SELECT pa.practice_area_name FROM lawyer_services ls JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id WHERE ls.lawyer_profile_id = " . (int)$row['lawyer_profile_id'] . " LIMIT 2";
        $services = mysqli_query($connection, $services_query);
        ?>
        <div class="lp-profile-tags" style="margin-bottom:16px">
        <?php if(mysqli_num_rows($services) == 0){ ?>
            <span style="font-size:12px;color:#aaa">No services yet</span>
        <?php } else { while($s = mysqli_fetch_assoc($services)) { ?>
            <span class="lp-profile-tag"><?php echo $s['practice_area_name']; ?></span>
        <?php }} ?>
        </div>

        <!-- View Profile — pinned to bottom via mt-auto -->
        <a href="lawyer_profile.php?id=<?php echo (int)$row['lawyer_profile_id']; ?>"
           class="btn btn-primary mt-auto w-100 py-2"
           style="display:flex;align-items:center;justify-content:center;gap:7px;font-weight:600;letter-spacing:.03em;border-radius:10px;">
            View Profile
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/>
            </svg>
        </a>
    </div>
  </div>


<?php } ?> 
<?php 
    } else {
?>
 <!-- ── not found message ── -->
  <?php
  // getting id from get and fetching name 
    $select_service = "SELECT * FROM practice_areas WHERE practice_area_id = '$service'";
    $select_service_result = mysqli_query($connection, $select_service);
    $select_service_row = mysqli_fetch_assoc($select_service_result);
  ?>
        <div class="at-no-results">
            <div class="at-no-results-icon">
                <i class="ion-ios-search"></i>
            </div>
            <h3>No Attorneys Found</h3>
            <p>We couldn't find any lawyers matching "<?php echo $city ?>" and "<?php echo $select_service_row['practice_area_name'] ?>".</p>
            <a href="attorneys.php" class="btn btn-primary mt-auto align-self-start py-2 px-3">Clear All Filters</a>
        </div>
<?php
}
?>
    </div>
  </div>
</section>
<?php
include("./base/footer.php");
?>

