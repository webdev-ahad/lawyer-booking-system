<?php
include("./base/header.php");

if($_SESSION['user_role'] != 'customer'){
    echo "<script>
    alert('Access denied');
    location.assign('./index.php');
    </script>";
    exit();
}

    $customer_id = $_SESSION['user_id'];

    $select_query = "SELECT a.*, u.user_name, pa.practice_area_name, ts.slot_date, ts.slot_time FROM appointments a JOIN lawyer_profiles lp ON a.lawyer_profile_id = lp.lawyer_profile_id JOIN users u ON lp.user_id = u.user_id JOIN lawyer_services ls ON a.service_id = ls.service_id JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id JOIN time_slots ts ON a.slot_id = ts.slot_id WHERE a.customer_id = '$customer_id' ORDER BY a.appointment_created DESC";
    $result = mysqli_query($connection, $select_query);

// Quick stats
    $total = mysqli_num_rows($result);

    $pending_query = "SELECT * FROM appointments WHERE customer_id = '$customer_id' AND appointment_status = 'pending'";
    $pending_result = mysqli_query($connection, $pending_query);
    $pending = mysqli_num_rows($pending_result);

    $approved_query = "SELECT * FROM appointments WHERE customer_id = '$customer_id' AND appointment_status = 'approved'";
    $approved_result = mysqli_query($connection, $approved_query);
    $approved = mysqli_num_rows($approved_result);
    
    $completed_query = "SELECT * FROM appointments WHERE customer_id = '$customer_id' AND appointment_status = 'completed'";
    $completed_result = mysqli_query($connection, $completed_query);
    $completed = mysqli_num_rows($completed_result);

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
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Total Bookings</p>
            <h4 class="mb-0"><?php echo $total; ?></h4>
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
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Pending</p>
            <h4 class="mb-0"><?php echo $pending; ?></h4>
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
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Approved</p>
            <h4 class="mb-0"><?php echo $approved; ?></h4>
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
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Completed</p>
            <h4 class="mb-0"><?php echo $completed; ?></h4>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- APPOINTMENTS CARDS -->
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h5 class="mb-0">My Appointments</h5>
          <p class="text-sm mb-0">Track all your legal consultations</p>
        </div>
        <div>
          <a href="../attorneys.php" class="btn btn-sm bg-gradient-primary mb-0">
            <i class="material-symbols-rounded text-sm">add</i>&nbsp;&nbsp;Book New
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <?php if($total > 0){ ?>
        <?php
            while($row = mysqli_fetch_assoc($result)){
        ?>
        <div class="col-lg-12 col-md-12 mb-4">
          <div class="card h-100">
            <div class="card-body p-3 pt-4">
              <!-- Service Category -->
              <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                <i class="material-symbols-rounded text-sm me-1 align-middle">gavel</i>
                <?php echo $row['practice_area_name']; ?>
              </h6>
              
              <!-- Details List -->
              <ul class="list-group">
                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                  <div class="avatar avatar-sm bg-gradient-info me-3 d-flex align-items-center justify-content-center">
                    <span class="text-white text-xs font-weight-bold">
                      <?php echo strtoupper(substr($row['user_name'],0,1)); ?>
                    </span>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-xs text-secondary">Lawyer</span>
                    <span class="text-sm font-weight-bold text-dark"><?php echo $row['user_name']; ?></span>
                  </div>
                </li>

                <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                  <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                    <i class="material-symbols-rounded text-white opacity-10" style="font-size: 16px;">event</i>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-xs text-secondary">Date & Time</span>
                    <span class="text-sm font-weight-bold text-dark">
                      <?php echo date('d M Y', strtotime($row['slot_date'])); ?> at <?php echo date('h:i A', strtotime($row['slot_time'])); ?>
                    </span>
                  </div>
                </li>

                <li class="list-group-item border-0 d-flex align-items-center px-0">
                  <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                    <i class="material-symbols-rounded text-white opacity-10" style="font-size: 16px;">location_on</i>
                  </div>
                  <div class="d-flex flex-column">
                    <span class="text-xs text-secondary">Place</span>
                    <span class="text-sm font-weight-bold text-dark"><?php echo $row['appointment_place']; ?></span>
                  </div>
                </li>
              </ul>

              <hr class="horizontal dark mt-4 mb-3">
              
              <!-- Footer with Status -->
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-xs text-secondary">Booked on <?php echo date('d M Y', strtotime($row['appointment_created'])); ?></span>
                <?php if($row['appointment_status'] == 'pending'){?>
                  <span class="badge badge-sm bg-gradient-warning">
                    Pending
                  </span>
                <?php }else if($row['appointment_status'] == 'approved'){?>
                  <span class="badge badge-sm bg-gradient-success">
                    Approved
                  </span>
                <?php }else if($row['appointment_status'] == 'completed'){?>
                  <span class="badge badge-sm bg-gradient-info">
                    Completed
                  </span>
                <?php }else if($row['appointment_status'] == 'rejected'){?>
                  <span class="badge badge-sm bg-gradient-danger">
                    Rejected
                  </span>
                <?php }?>
              </div>
            </div>  
          </div>
        </div>
      <?php
           }
        } else {
      ?>
        <div class="col-12">
          <div class="card text-center py-5">
            <div class="card-body">
              <h6 class="text-secondary mb-3">No Appointments Yet</h6>
              <p class="text-sm text-secondary mb-4">You haven't booked any consultations. Find a lawyer and schedule your first session.</p>
              <a href="../attorneys.php" class="btn btn-sm btn-outline-primary mb-0">Browse Attorneys</a>
            </div>
          </div>
        </div>
    <?php
       }
     ?>
  </div>

</div>

<?php include("./base/footer.php"); ?>