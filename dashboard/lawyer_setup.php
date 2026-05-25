<?php
include './base/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'lawyer'){
    header("Location: auth/sign-in.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// get lawyer profile

$select_query = "SELECT lawyer_profile_id FROM lawyer_profiles WHERE user_id = $user_id";
$result = mysqli_query($connection, $select_query);
$data = mysqli_fetch_assoc($result);

if(!$data){
    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Profile Not Found','text'=>'No profile exists for this user'];
    exit;
}

// redirect if profile already completed

$lawyer_id = $data['lawyer_profile_id'];

$check_setup = "SELECT lawyer_setup_completed FROM lawyer_profiles WHERE lawyer_profile_id = $lawyer_id LIMIT 1";
$result = mysqli_query($connection, $check_setup);

if(!$result || mysqli_num_rows($result) == 0){
    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Profile Not Found','text'=>'No profile exists for this user'];
    exit;
}

$row = mysqli_fetch_assoc($result);

// check if profile already completed
if($row['lawyer_setup_completed'] == 1){
    header("Location: index.php");
    exit;
}


// add service
if(isset($_POST['add_service'])){
    $practice_id = $_POST['practice_area_id'];

    if($practice_id === '' || $practice_id === null){

        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Selection','text'=>'Please select a practice area.'];
        header("Location: lawyer_setup.php"); exit;
    }

    // prevent duplicate service
    $check = "SELECT * FROM lawyer_services WHERE lawyer_profile_id = $lawyer_id AND practice_area_id = $practice_id";

    $exists = mysqli_query($connection, $check);

    if(mysqli_num_rows($exists) == 0){
        $insert_service = "INSERT INTO lawyer_services (lawyer_profile_id, practice_area_id) VALUES ($lawyer_id, $practice_id)";
        mysqli_query($connection, $insert_service);

        $_SESSION['_swal'] = ['icon'=>'success','title'=>'Service Added','text'=>'Practice area added to your profile.'];
        header("Location: lawyer_setup.php"); exit;
      
      } else {

        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Duplicate Service','text'=>'This service is already on your profile.'];
        header("Location: lawyer_setup.php"); exit;
    
      }
}


// add time slot
if(isset($_POST['add_slot'])){
    $date = $_POST['slot_date'];
    $time = $_POST['slot_time'];

    if($date == '' || $time == ''){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Fields','text'=>'Please select both date and time.'];
        header("Location: lawyer_setup.php"); exit;
    }

    $today = date('Y-m-d');
    if ($date <= $today) {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Date','text'=>'Choose a date from tomorrow onward. Same-day slots are not allowed.'];
        header("Location: lawyer_setup.php"); exit;
    }

    // prevent duplicate slot
    $check_slot = "SELECT * FROM time_slots WHERE lawyer_profile_id = $lawyer_id AND slot_date = '$date' AND slot_time = '$time'";
    $slot_exists = mysqli_query($connection, $check_slot);

    if(mysqli_num_rows($slot_exists) == 0){
        $insert_slot = "INSERT INTO time_slots (lawyer_profile_id, slot_date, slot_time) VALUES ($lawyer_id, '$date', '$time')";
        mysqli_query($connection, $insert_slot);

        $_SESSION['_swal'] = ['icon'=>'success','title'=>'Slot Added','text'=>'Your time slot has been saved.'];
    
      } else {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Duplicate Slot','text'=>'You already have a slot at this date and time.'];
    
      }
    header("Location: lawyer_setup.php"); exit;
}


// refresh data
$select_services = "SELECT * FROM lawyer_services WHERE lawyer_profile_id = $lawyer_id";
$services = mysqli_query($connection, $select_services);
$services_count = mysqli_num_rows($services);

$select_slots = "SELECT * FROM time_slots WHERE lawyer_profile_id = $lawyer_id";
$slots = mysqli_query($connection, $select_slots);
$slots_count = mysqli_num_rows($slots);


// finish setup
if(isset($_POST['finish_setup'])){

    if($services_count >= 1 && $slots_count >= 1){

        $update_query = "UPDATE lawyer_profiles SET lawyer_setup_completed = 1 WHERE lawyer_profile_id = $lawyer_id";
        $update_result = mysqli_query($connection, $update_query);

        if($update_result){
            $_SESSION['_swal'] = ['icon'=>'success','title'=>'Setup Complete','text'=>'Your profile is ready. Welcome to the dashboard.'];
            header("Location: index.php");
            exit;
        }

        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Error','text'=>'Could not save. Please try again.'];
        header("Location: lawyer_setup.php"); exit;

    } else {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Incomplete Setup','text'=>'Add at least one service and one time slot before finishing.'];
        header("Location: lawyer_setup.php"); exit;
    }
}
?>

<style>
  /* Restore full borders on inputs — Material Dashboard uses bottom-only by default */
  .lawyer-setup-wrap .form-control,
  .lawyer-setup-wrap select.form-control {
    border: 1px solid #d2d6da !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem 0.75rem !important;
    background-color: #fff !important;
    color: #344767 !important;
    box-shadow: none !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .lawyer-setup-wrap .form-control:focus,
  .lawyer-setup-wrap select.form-control:focus {
    border-color: #007acc !important;
    box-shadow: 0 0 0 2px rgba(0, 122, 204, 0.18) !important;
    outline: none !important;
  }
</style>

<div class="container-fluid py-2 lawyer-setup-wrap">

  <div class="alert alert-warning alert-dismissible text-white d-flex align-items-center mx-0 mb-3" role="alert">
    <i class="material-symbols-rounded me-2">warning</i>
    <span class="text-sm"><strong>Action Required:</strong> Please complete your profile setup to start receiving client appointments.</span>
    <button type="button" class="btn-close text-lg py-3 opacity-10 ms-auto" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>

  <div class="row">
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card h-100">

        <div class="card-header pb-0 p-3">
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-primary me-2">gavel</i>
            <h6 class="mb-0">Add Practice Area</h6>
          </div>
          <p class="text-sm mb-0 mt-1 text-secondary">Select the legal areas you specialize in.</p>
        </div>

        <div class="card-body p-3">

          <!-- Form -->
          <form method="POST" id="addServiceForm" class="mb-4" novalidate>
            <div class="mb-3">
              <label class="form-label text-sm font-weight-bold">Practice Area</label>
              <select name="practice_area_id" class="form-control" required>
                <option value="" disabled selected>— Select Practice Area —</option>
                <?php
                  $areas_query = "SELECT * FROM practice_areas";
                  $areas = mysqli_query($connection, $areas_query);
                  while($row = mysqli_fetch_assoc($areas)){
                    echo "<option value='" . $row['practice_area_id'] . "'>" . $row['practice_area_name'] . "</option>";
                  }
                ?>
              </select>
            </div>
            <button type="submit" name="add_service" class="btn bg-gradient-primary w-100 mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:16px;vertical-align:middle;">add_circle</i>
              Add Service
            </button>
          </form>

          <!-- Existing Services -->
          <hr class="horizontal dark my-3">
          <h6 class="text-sm font-weight-bold mb-2">Your Current Services</h6>

          <?php
            $list_query = "
              SELECT pa.practice_area_name
              FROM lawyer_services ls
              JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id
              WHERE ls.lawyer_profile_id = $lawyer_id";
            $list = mysqli_query($connection, $list_query);
            $count = mysqli_num_rows($list);
          ?>

          <?php if($count === 0){ ?>
            <p class="text-sm text-secondary mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;">info</i>
              No services added yet.
            </p>
          <?php }else{ ?>
            <ul class="list-group list-group-flush">
              <?php while($row = mysqli_fetch_assoc($list)){ ?>
                <li class="list-group-item border-0 ps-0 d-flex align-items-center text-sm">
                  <span class="badge badge-sm bg-gradient-success me-2">✓</span>
                  <?php echo $row['practice_area_name']; ?>
                </li>
              <?php } ?>
            </ul>
          <?php }?>

        </div>
      </div>
    </div>

    <!-- Add time slots-->
    <div class="col-lg-6 col-md-12 mb-4">
      <div class="card h-100">

        <div class="card-header pb-0 p-3">
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-success me-2">schedule</i>
            <h6 class="mb-0">Add Availability</h6>
          </div>
          <p class="text-sm mb-0 mt-1 text-secondary">Pick dates from tomorrow onward — same-day slots are not allowed.</p>
        </div>

        <div class="card-body p-3">

          <!-- Add slots -->
          <form method="POST" id="slotForm" class="mb-4" novalidate>
            <div class="mb-3">
              <label class="form-label text-sm font-weight-bold">Date</label>
              <input type="date" name="slot_date" id="slot_date" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label text-sm font-weight-bold">Time</label>
              <input type="time" name="slot_time" id="slot_time" class="form-control" required>
            </div>
            <button type="submit" name="add_slot" class="btn bg-gradient-success w-100 mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:16px;vertical-align:middle;">add_circle</i>
              Add Time Slot
            </button>
          </form>

          <!-- Current Slots -->
          <hr class="horizontal dark my-3">
          <h6 class="text-sm font-weight-bold mb-2">Your Current Slots</h6>

          <?php
            $slots_query = "
              SELECT * FROM time_slots
              WHERE lawyer_profile_id = $lawyer_id
              ORDER BY slot_date, slot_time";
            $slots = mysqli_query($connection, $slots_query);
            $slot_count = mysqli_num_rows($slots);
          ?>

          <?php if($slot_count === 0){ ?>
            <p class="text-sm text-secondary mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;">info</i>
              No time slots added yet.
            </p>
          <?php }else{ ?>
            <ul class="list-group list-group-flush">
              <?php while($row = mysqli_fetch_assoc($slots)){ ?>
                <li class="list-group-item border-0 ps-0 d-flex align-items-center text-sm">
                  <span class="badge badge-sm bg-gradient-info me-2">
                    <i class="material-symbols-rounded" style="font-size:12px;">event</i>
                  </span>
                  <span>
                    <strong><?php echo $row['slot_date']; ?></strong>
                    &nbsp;—&nbsp;
                    <?php echo $row['slot_time']; ?>
                  </span>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>

        </div>
      </div>
    </div>
    <form method="POST" id="finishSetupForm" novalidate
          data-services="<?php echo (int)$services_count; ?>"
          data-slots="<?php echo (int)$slots_count; ?>">
        <button type="submit" name="finish_setup" class="btn bg-gradient-dark w-100 mt-3">
            Finish Setup
        </button>
    </form>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function ymdLocal(d) {
        var y = d.getFullYear(), m = String(d.getMonth() + 1).padStart(2, "0"), day = String(d.getDate()).padStart(2, "0");
        return y + "-" + m + "-" + day;
    }

    var slotDate = document.getElementById("slot_date");
    if (slotDate) {
        var tMin = new Date();
        tMin.setDate(tMin.getDate() + 1);
        slotDate.setAttribute("min", ymdLocal(tMin));
    }

    var addSvc = document.getElementById("addServiceForm");
    if (addSvc) {
        addSvc.addEventListener("submit", function (e) {
            var sel = addSvc.querySelector('select[name="practice_area_id"]');
            if (!sel || !sel.value) {
                e.preventDefault();
                Swal.fire({ icon: "warning", title: "Select an Area", text: "Please choose a practice area to add." });
            }
        });
    }

    var slotForm = document.getElementById("slotForm");
    if (slotForm) {
        slotForm.addEventListener("submit", function (e) {
            var date = document.getElementById("slot_date").value;
            var time = document.getElementById("slot_time").value;

            if (!date || !time) {
                e.preventDefault();
                Swal.fire({ icon: "warning", title: "Missing Fields", text: "Please select both date and time." });
                return;
            }

            if (date <= ymdLocal(new Date())) {
                e.preventDefault();
                Swal.fire({ icon: "error", title: "Invalid Date", text: "Choose a date from tomorrow onward. Same-day slots are not allowed." });
                return;
            }
        });
    }

    var finishForm = document.getElementById("finishSetupForm");
    if (finishForm) {
        finishForm.addEventListener("submit", function (e) {
            var svc = parseInt(finishForm.getAttribute("data-services") || "0", 10);
            var slt = parseInt(finishForm.getAttribute("data-slots") || "0", 10);
            if (svc < 1 || slt < 1) {
                e.preventDefault();
                Swal.fire({ icon: "warning", title: "Incomplete Setup", text: "Add at least one service and one time slot first." });
            }
        });
    }

});
</script>
<?php include('./base/footer.php'); ?>