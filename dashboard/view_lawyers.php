<?php
include("./base/header.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "admin") {
    header("Location: ./index.php");
    exit();
}
// lawyer profiles query with join 
$query = "SELECT lp.*, u.user_name, u.user_email FROM lawyer_profiles lp JOIN users u ON lp.user_id = u.user_id WHERE lp.lawyer_setup_completed = 1 ORDER BY lp.lawyer_profile_id DESC";
$result = mysqli_query($connection, $query);
$total  = mysqli_num_rows($result);

// avg lawyer experience
$avg_exp_query = "SELECT AVG(lawyer_experience_years) AS avg_exp FROM lawyer_profiles WHERE lawyer_setup_completed = 1";
$avg_exp_result = mysqli_query($connection, $avg_exp_query);
$avg_exp_fetch = mysqli_fetch_assoc($avg_exp_result);
$avg_exp = round($avg_exp_fetch['avg_exp'], 2);

// avg lawyer consultation fee
$avg_fee_query = "SELECT AVG(lawyer_consultation_fee) AS avg_fee FROM lawyer_profiles WHERE lawyer_setup_completed = 1";
$avg_fee_result = mysqli_query($connection, $avg_fee_query);
$avg_fee_fetch = mysqli_fetch_assoc($avg_fee_result);
$avg_fee = round($avg_fee_fetch['avg_fee'], 2);

// total unique cities
$city_query = "SELECT COUNT(DISTINCT lawyer_city) AS city_count FROM lawyer_profiles WHERE lawyer_setup_completed = 1";
$city_result = mysqli_query($connection, $city_query);
$city_fetch = mysqli_fetch_assoc($city_result);
$cities = $city_fetch['city_count'];
?>

<div class="container-fluid py-2">

  <!-- Stats Cards -->
  <div class="row mb-2">
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white" style="font-size:22px">groups</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Lawyers</p>
            <h4 class="mb-0"><?php echo $total; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-dark font-weight-bolder">Verified</span> &amp; active</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#42a5f5,#1565c0);box-shadow:0 4px 20px rgba(21,101,192,.35)">
            <span class="material-symbols-rounded text-white" style="font-size:22px">workspace_premium</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Avg Experience</p>
            <h4 class="mb-0"><?php echo $avg_exp; ?> yrs</h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#1565c0" class="font-weight-bolder">Years</span> on average</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#66bb6a,#388e3c);box-shadow:0 4px 20px rgba(56,142,60,.4)">
            <span class="material-symbols-rounded text-white" style="font-size:22px">payments</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Avg Fee</p>
            <h4 class="mb-0">$<?php echo $avg_fee; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#388e3c" class="font-weight-bolder">Consultation</span> rate</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#f5a623,#f07c00);box-shadow:0 4px 20px rgba(245,166,35,.4)">
            <span class="material-symbols-rounded text-white" style="font-size:22px">location_city</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Cities Covered</p>
            <h4 class="mb-0"><?php echo $cities; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#f07c00" class="font-weight-bolder">Unique</span> locations</p>
        </div>
      </div>
    </div>
  </div>

    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <!-- Card header -->
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex align-items-center justify-content-between px-3">
                        <h6 class="text-white text-capitalize mb-0">
                            <span class="material-symbols-rounded" style="font-size:.75rem">verified</span> Verified Lawyers</h6>
                        <span class="text-white text-xs opacity-8"><?= $total ?> total</span>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lawyer</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">City</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Consultation Fee</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Experience</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bar Number</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if ($total == 0) { ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary text-sm">
                                            No approved lawyers found.
                                        </td>
                                    </tr>
                                <?php } ?>

                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="../uploads/<?= $row['lawyer_profile_photo'] ?>"
                                                     class="avatar avatar-sm me-3 border-radius-lg"
                                                     alt="lawyer">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= $row['user_name'] ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?= $row['user_email'] ?></p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="align-middle text-sm">
                                        <p class="text-xs font-weight-bold mb-0"><?= $row['lawyer_city'] ?></p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            <?= $row['lawyer_consultation_fee'] ?>
                                        </span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-info"><?= $row['lawyer_experience_years'] ?> yrs</span>
                                    </td>

                                    <td class="align-middle text-center text-sm">
                                        <p class="text-xs font-weight-bold mb-0"><?= $row['lawyer_bar_number'] ?></p>
                                    </td>

                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include("./base/footer.php"); ?>