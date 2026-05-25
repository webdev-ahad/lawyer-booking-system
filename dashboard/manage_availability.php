<?php
include './base/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'lawyer'){
    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Access Denied','text'=>'Only lawyers are allowed to access this page.'];
    header("Location: auth/sign-in.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// get lawyer id
$lawyer_query = "SELECT lawyer_profile_id FROM lawyer_profiles WHERE user_id = $user_id";
$result = mysqli_query($connection, $lawyer_query);
$data = mysqli_fetch_assoc($result);

if(!$data){
    // if lawyer profile not found
    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Profile Not Found','text'=>'Your lawyer profile could not be found.'];
    header("Location: index.php");
    exit();
}

// get lawyer id
$lawyer_id = $data['lawyer_profile_id'];

// add time slot
if(isset($_POST['add_slot'])){
    $date = $_POST['slot_date'];
    $time = $_POST['slot_time'];

    if ($date === '' || $time === '') {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Missing Fields','text'=>'Please select both a date and a time.'];
        header("Location: manage_availability.php"); exit();
    }

    $today = date('Y-m-d');

    // lawyer can't add slot for today or previous days
    if ($date <= $today) {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Date','text'=>'Choose a date from tomorrow onward. Same-day slots are not allowed.'];
        header("Location: manage_availability.php"); exit();
    }

    // prevent duplicate slot
    $check_slot = "SELECT * FROM time_slots WHERE lawyer_profile_id = $lawyer_id AND slot_date = '$date' AND slot_time = '$time'";
    $slot_exists = mysqli_query($connection, $check_slot);

    if(mysqli_num_rows($slot_exists) == 0){

        $insert_slot = "INSERT INTO time_slots (lawyer_profile_id, slot_date, slot_time) VALUES ($lawyer_id, '$date', '$time')";
        mysqli_query($connection, $insert_slot);

        $_SESSION['_swal'] = ['icon'=>'success','title'=>'Slot Added','text'=>'Time slot has been added successfully.'];
    
      } else {
        
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Duplicate Slot','text'=>'This date and time slot already exists.'];
    }
    header("Location: manage_availability.php"); exit();
}
// deletee slot
if(isset($_GET['delete'])){
    $slot_id = $_GET['delete'];

    $delete = "DELETE FROM time_slots WHERE slot_id = $slot_id AND lawyer_profile_id = $lawyer_id";
    mysqli_query($connection, $delete);

    header("Location: manage_availability.php");
    exit;
}

// refresh data
$select_slots = "SELECT * FROM time_slots WHERE lawyer_profile_id = $lawyer_id";
$slots = mysqli_query($connection, $select_slots);
$slots_count = mysqli_num_rows($slots);
?>

<style>
  .manage-services-wrap .form-control,
  .manage-services-wrap select.form-control {
    border: 1px solid #d2d6da !important;
    border-radius: 0.5rem !important;
    padding: 0.5rem 0.75rem !important;
    background-color: #fff !important;
    color: #344767 !important;
    box-shadow: none !important;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .manage-services-wrap .form-control:focus {
    border-color: #007acc !important;
    box-shadow: 0 0 0 2px rgba(0, 122, 204, 0.18) !important;
    outline: none !important;
  }
  .slot-item {
    transition: background 0.15s ease;
  }
  .slot-item:hover {
    background: #f8f9fa;
  }
</style>

<div class="container-fluid py-2 manage-services-wrap">

  <!-- ROW 1: Add Time Slot -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header pb-0 p-3">
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-primary me-2">add_circle</i>
            <h6 class="mb-0">Add Time Slot</h6>
          </div>
          <p class="text-sm text-secondary mb-0 mt-1">Slots must start from tomorrow — same-day availability is not allowed.</p>
        </div>
        <div class="card-body p-3">
          <form method="POST" id="slotsForm" novalidate>
            <div class="row align-items-end">
              <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label text-sm font-weight-bold">Date</label>
                <input type="date" name="slot_date" id="slot_date" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3 mb-md-0">
                <label class="form-label text-sm font-weight-bold">Time</label>
                <input type="time" name="slot_time" id="slot_time" class="form-control" required>
              </div>
              <div class="col-md-4">
                <button type="submit" name="add_slot" class="btn bg-gradient-primary w-100 mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:16px;vertical-align:middle;">add</i>
                  Add Slot
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ROW 2: Time Slots List -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header pb-0 p-3">
          <?php
            $slots_query = "SELECT * FROM time_slots WHERE lawyer_profile_id = $lawyer_id ORDER BY slot_date, slot_time";
            $slots = mysqli_query($connection, $slots_query);
            $slot_count = mysqli_num_rows($slots);
          ?>
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-success me-2">schedule</i>
            <h6 class="mb-0">Your Time Slots</h6>
          </div>
          <p class="text-sm text-secondary mb-0 mt-1">Manage your available appointment slots.</p>
        </div>
        <div class="card-body p-3">
          <?php if($slot_count === 0){ ?>
          <!-- empty state -->
          <p class="text-sm text-secondary mb-0">
            <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;">info</i>
            No time slots added yet.
          </p>

        <?php } else {?>
          
          <!-- slots list -->
          
          <ul class="list-group list-group-flush">
              <?php while($row = mysqli_fetch_assoc($slots)){ ?>
            <li class="list-group-item border-0 ps-0 slot-item d-flex align-items-center justify-content-between text-sm py-2">
              <div class="d-flex align-items-center">
                <span class="badge badge-sm bg-gradient-info me-2">
                  <i class="material-symbols-rounded" style="font-size:12px;">event</i>
                </span>
                <span><strong><?php echo $row['slot_date']; ?></strong>
                 &nbsp;—&nbsp;
                 <?php echo $row['slot_time']; ?></span>
              </div>
              <a href="#"
                 class="btn btn-sm btn-link text-danger p-0 mb-0 swal-delete"
                 data-href="?delete=<?php echo $row['slot_id']; ?>"
                 title="Delete">
                <i class="material-symbols-rounded" style="font-size:18px;">delete</i>
              </a>
            </li>
             <?php }?>
          </ul>
         <?php }?>

        </div>
      </div>
    </div>
  </div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Earliest selectable date = tomorrow (no same-day slots)
    function ymdLocal(d) {
        var y = d.getFullYear(), m = String(d.getMonth() + 1).padStart(2, '0'), day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }
    var tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    var minDate = ymdLocal(tomorrow);
    document.getElementById("slot_date").setAttribute("min", minDate);

    // Client-side validation using SweetAlert2
    document.getElementById("slotsForm").addEventListener("submit", function(e) {
        let date = document.getElementById("slot_date").value;
        let time = document.getElementById("slot_time").value;

        if (!date || !time) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Missing Fields', text: 'Please select both a date and a time.' });
            return;
        }

        var todayStr = ymdLocal(new Date());
        if (date <= todayStr) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Choose a date from tomorrow onward. Same-day slots are not allowed.' });
            return;
        }
    });

    // SweetAlert2 delete confirmation for slots
    document.querySelectorAll('.swal-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            Swal.fire({
                icon: 'warning',
                title: 'Delete Slot?',
                text: 'This time slot will be permanently removed.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#e53e3e'
            }).then(function(result) {
                if (result.isConfirmed) window.location.href = href;
            });
        });
    });

});
</script>
<?php include './base/footer.php'; ?>
