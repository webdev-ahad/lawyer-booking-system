<?php
include("./base/header.php");

// only lawyers allowed
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'lawyer'){
    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Access Denied','text'=>'Only lawyers can access this page.'];
    header("Location: ./auth/sign-in.php"); exit();
}

$user_id = $_SESSION['user_id'];

// get lawyer profile
$lawyer_query = "SELECT * FROM lawyer_profiles WHERE user_id = '$user_id'";
$lawyer_result = mysqli_query($connection, $lawyer_query);
$lawyer = mysqli_fetch_assoc($lawyer_result);

if(!$lawyer){

    $_SESSION['_swal'] = ['icon'=>'error','title'=>'Profile Not Found','text'=>'Your lawyer profile could not be found.'];
    header("Location: ./index.php"); exit();
}

$lawyer_profile_id = $lawyer['lawyer_profile_id'];

if(isset($_GET['action']) && isset($_GET['appointment_id'])){

    $appointment_id = $_GET['appointment_id'];
    $action = $_GET['action'];

    $allowed = ['approved', 'rejected', 'completed'];

    if(in_array($action, $allowed)){

        // get appointment first
        $appointment_query = "SELECT * FROM appointments WHERE appointment_id = '$appointment_id' AND lawyer_profile_id = '$lawyer_profile_id'";
        $appointment_result = mysqli_query($connection, $appointment_query);
        $appointment = mysqli_fetch_assoc($appointment_result);

        if($appointment){

            $current_status = $appointment['appointment_status'];

            $valid = false;

            // valid status flow
            if($current_status == 'pending' && ($action == 'approved' || $action == 'rejected')){
                $valid = true;
            }

            if($current_status == 'approved' && $action == 'completed'){
                $valid = true;
            }

            if($valid){

                // update appointment status
                $update_query = "UPDATE appointments SET appointment_status = '$action' WHERE appointment_id = '$appointment_id'";
                mysqli_query($connection, $update_query);

                // if rejected free slot again
                if($action == 'rejected'){

                    $slot_id = $appointment['slot_id'];
                    mysqli_query($connection, "UPDATE time_slots SET slot_status='available' WHERE slot_id='$slot_id'");
                }

                $_SESSION['_swal'] = ['icon'=>'success','title'=>'Updated!','text'=>'Appointment status has been updated.','redirect'=>'manage_appointments.php'];
                header("Location: manage_appointments.php"); exit();

            } else {

                $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Invalid Action','text'=>'This status transition is not allowed.','redirect'=>'manage_appointments.php'];
                header("Location: manage_appointments.php"); exit();
  
              }
        }
    }
}


// filters
$allowed_statuses = ['pending', 'approved', 'completed', 'rejected'];
$status_filter    = '';
$active_status    = '';
if(isset($_GET['status']) && !empty($_GET['status'])){

    if(in_array($_GET['status'], $allowed_statuses)){
        $active_status = $_GET['status'];
        $status_filter = " AND a.appointment_status = '$active_status' ";
    }
}


// get appointment
$query = "SELECT a.*, u.user_name, u.user_email, pa.practice_area_name, ts.slot_date, ts.slot_time
          FROM appointments a
          JOIN users u
          ON a.customer_id = u.user_id

          JOIN lawyer_services ls
          ON a.service_id = ls.service_id

          JOIN practice_areas pa
          ON ls.practice_area_id = pa.practice_area_id

          JOIN time_slots ts
          ON a.slot_id = ts.slot_id WHERE a.lawyer_profile_id = '$lawyer_profile_id' $status_filter ORDER BY ts.slot_date DESC, ts.slot_time DESC";
          $result = mysqli_query($connection, $query);

// update notes
if(isset($_POST['save_notes'])){

    $appointment_id = $_POST['appointment_id'];
    $notes = mysqli_real_escape_string($connection, $_POST['appointment_notes']);

    // minimum length 
    if(strlen($notes) < 8){
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Notes Too Short','text'=>'Notes must be at least 8 characters.'];
        header("Location: manage_appointments.php");
        exit();
    } 

    // update query
    $update_notes = "UPDATE appointments SET appointment_notes = '$notes' WHERE appointment_id = '$appointment_id' AND lawyer_profile_id = '$lawyer_profile_id'";
    $update_result = mysqli_query($connection, $update_notes);
    
    // if notes updated successfully redirect to manage appointments page
    if($update_result){
        $_SESSION['_swal'] = ['icon'=>'success','title'=>'Notes Saved','text'=>'Appointment notes have been updated.'];
        header("Location: manage_appointments.php");
        exit();

    // if notes not updated successfully redirect to manage appointments page with error message
    }else{

        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Failed to Save','text'=>'Something went wrong. Please try again.'];
        header("Location: manage_appointments.php");
        exit();
   
      }
  
  }

?>

<div class="container-fluid py-4">

  <!-- STATS -->
  <div class="row mb-4">

    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white">event_note</span>
          </div>
          <?php
            $stats_total_query = "SELECT * FROM appointments WHERE lawyer_profile_id = '$lawyer_profile_id'";
            $stats_total_result = mysqli_query($connection, $stats_total_query);
            $stats_total = mysqli_num_rows($stats_total_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Total</p>
            <h4 class="mb-0"><?php echo $stats_total; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-warning">
            <span class="material-symbols-rounded text-white">schedule</span>
          </div>
          <?php
            $stats_pending_query = "SELECT * FROM appointments WHERE lawyer_profile_id = '$lawyer_profile_id' AND appointment_status = 'pending'";
            $stats_pending_result = mysqli_query($connection, $stats_pending_query);
            $stats_pending = mysqli_num_rows($stats_pending_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Pending</p>
            <h4 class="mb-0"><?php echo $stats_pending; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-success">
            <span class="material-symbols-rounded text-white">check_circle</span>
          </div>
          <?php
            $stats_approved_query = "SELECT * FROM appointments WHERE lawyer_profile_id = '$lawyer_profile_id' AND appointment_status = 'approved'";
            $stats_approved_result = mysqli_query($connection, $stats_approved_query);
            $stats_approved = mysqli_num_rows($stats_approved_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Approved</p>
            <h4 class="mb-0"><?php echo $stats_approved; ?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-info">
            <span class="material-symbols-rounded text-white">task_alt</span>
          </div>
          <?php
            $stats_completed_query = "SELECT * FROM appointments WHERE lawyer_profile_id = '$lawyer_profile_id' AND appointment_status = 'completed'";
            $stats_completed_result = mysqli_query($connection, $stats_completed_query);
            $stats_completed = mysqli_num_rows($stats_completed_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Completed</p>
            <h4 class="mb-0"><?php echo $stats_completed; ?></h4>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- TABLE -->
  <div class="row">
    <div class="col-12">

      <div class="card">

        <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap">

          <div>
            <h5 class="mb-0">Manage Appointments</h5>
            <p class="text-sm mb-0">Review and manage bookings</p>
          </div>

          <!-- FILTERS -->
          <div class="d-flex gap-2 flex-wrap mt-3 mt-md-0">

            <a href="manage_appointments.php"
              class="btn btn-sm mb-0 <?php echo !$active_status ? 'btn-dark' : 'btn-outline-dark'; ?>">
              All
            </a>

            <a href="?status=pending"
              class="btn btn-sm mb-0 <?php echo $active_status == 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
              Pending
            </a>

            <a href="?status=approved"
              class="btn btn-sm mb-0 <?php echo $active_status == 'approved' ? 'btn-success' : 'btn-outline-success'; ?>">
              Approved
            </a>

            <a href="?status=completed"
              class="btn btn-sm mb-0 <?php echo $active_status == 'completed' ? 'btn-primary' : 'btn-outline-primary'; ?>">
              Completed
            </a>

            <a href="?status=rejected"
              class="btn btn-sm mb-0 <?php echo $active_status == 'rejected' ? 'btn-danger' : 'btn-outline-danger'; ?>">
              Rejected
            </a>

          </div>

        </div>

        <div class="card-body px-0 pt-0 pb-2">

          <div class="table-responsive p-0">

            <table class="table align-items-center mb-0">

              <thead>

                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                    Client
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Service
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Date & Time
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Place
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Status
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Created
                  </th>

                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Actions
                  </th>
                </tr>

              </thead>

              <tbody>

              <?php
              if(mysqli_num_rows($result) > 0){

                  while($row = mysqli_fetch_assoc($result)){

              ?>

                <tr>

                  <!-- CLIENT -->
                  <td>
                    <div class="d-flex px-3 py-2">

                      <div class="d-flex flex-column justify-content-center">

                        <h6 class="mb-0 text-sm">
                          <?php echo $row['user_name']; ?>
                        </h6>

                        <p class="text-xs text-secondary mb-0">
                          <?php echo $row['user_email']; ?>
                        </p>

                      </div>

                    </div>
                  </td>

                  <!-- SERVICE -->
                  <td>
                    <p class="text-sm font-weight-bold mb-0">
                      <?php echo $row['practice_area_name']; ?>
                    </p>
                  </td>

                  <!-- DATE -->
                  <td>
                    <p class="text-sm mb-0">
                      <?php echo date('d M Y', strtotime($row['slot_date'])); ?>
                    </p>

                    <p class="text-xs text-secondary mb-0">
                      <?php echo date('h:i A', strtotime($row['slot_time'])); ?>
                    </p>
                  </td>

                  <!-- PLACE -->
                  <td>
                    <span class="text-secondary text-sm">
                      <?php echo $row['appointment_place']; ?>
                    </span>
                  </td>

                  <!-- STATUS -->
                  <td>
                    <?php if ($row['appointment_status'] == 'pending') { ?>
                      <span class="badge badge-sm bg-gradient-warning">
                        Pending
                      </span>
                    <?php } else if ($row['appointment_status'] == 'approved') { ?>
                      <span class="badge badge-sm bg-gradient-success">
                        Approved
                      </span>
                    <?php } else if ($row['appointment_status'] == 'rejected') { ?>
                      <span class="badge badge-sm bg-gradient-danger">
                        Rejected
                      </span>
                    <?php } else if ($row['appointment_status'] == 'completed') { ?>
                      <span class="badge badge-sm bg-gradient-primary">
                        Completed
                      </span>
                    <?php } ?>
                  </td>

                  <!-- CREATED -->
                  <td>
                    <span class="text-secondary text-sm">
                      <?php echo date('d M Y', strtotime($row['appointment_created'])); ?>
                    </span>
                  </td>

                  <!-- ACTIONS -->
                  <td>

                    <?php if($row['appointment_status'] == 'pending'){ ?>
                      
                      <a href="#"
                        class="btn btn-outline-dark btn-sm mb-0"
                        data-bs-toggle="modal"
                        data-bs-target="#notesModal<?php echo $row['appointment_id']; ?>">
                        Add Notes
                      </a>

                      <a href="#"
                        class="btn btn-outline-success btn-sm mb-0 swal-approve"
                        data-href="?action=approved&appointment_id=<?php echo $row['appointment_id']; ?>">
                        Approve
                      </a>

                      <a href="#"
                        class="btn btn-outline-danger btn-sm mb-0 swal-reject"
                        data-href="?action=rejected&appointment_id=<?php echo $row['appointment_id']; ?>">
                        Reject
                      </a>

                    <?php } elseif($row['appointment_status'] == 'approved'){ ?>

                      <a href="#"
                        class="btn btn-info btn-sm mb-0 swal-complete"
                        data-href="?action=completed&appointment_id=<?php echo $row['appointment_id']; ?>">
                        Complete
                      </a>

                    <?php } else { ?>

                      <span class="text-secondary text-xs">No actions</span>

                    <?php } ?>

                  </td>

                </tr>
                <div class="modal fade" id="notesModal<?php echo $row['appointment_id']; ?>">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Notes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <form method="POST" class="appt-notes-form" novalidate>
                                <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
                                <div class="mb-3">
                                  <label class="form-label">Notes</label>
                                  <textarea class="form-control" name="appointment_notes" rows="3" required><?php echo $row['appointment_notes']; ?></textarea>
                                </div>
                                <button type="submit" name="save_notes" class="btn btn-primary">Save Notes</button>
                                </form>
                              </div>
                        </div>
                    </div>
                </div>
              <?php
                  }

              } else {
              ?>

                <tr>

                  <td colspan="7" class="text-center py-5">

                    <h6 class="text-secondary">
                      No appointments found
                    </h6>

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

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Approve confirmation
    document.querySelectorAll('.swal-approve').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            Swal.fire({
                icon: 'question',
                title: 'Approve Appointment?',
                text: 'This will confirm the appointment for the client.',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2ecc71'
            }).then(function(r) { if(r.isConfirmed) window.location.href = href; });
        });
    });

    // Reject confirmation
    document.querySelectorAll('.swal-reject').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            Swal.fire({
                icon: 'warning',
                title: 'Reject Appointment?',
                text: 'The time slot will be freed and the appointment will be rejected.',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#e53e3e'
            }).then(function(r) { if(r.isConfirmed) window.location.href = href; });
        });
    });

    // Complete confirmation
    document.querySelectorAll('.swal-complete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            Swal.fire({
                icon: 'info',
                title: 'Mark as Completed?',
                text: 'This will mark the appointment as completed.',
                showCancelButton: true,
                confirmButtonText: 'Yes, complete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3498db'
            }).then(function(r) { if(r.isConfirmed) window.location.href = href; });
        });
    });

});
</script>

<?php include("./base/footer.php"); ?>