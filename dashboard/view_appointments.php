<?php
include("./base/header.php");

// admin only  
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Access Denied', 'text' => 'Only admins can access this page.'];
    header("Location: ./auth/sign-in.php");
    exit();
}

// status filter
$allowed_statuses = ['pending', 'approved', 'completed', 'rejected'];
$status_filter = '';
$active_status = '';
if (isset($_GET['status']) && in_array($_GET['status'], $allowed_statuses)) {
    $active_status = $_GET['status'];
    $status_filter = "AND a.appointment_status = '$active_status'";
}

// stats
$stats_query = "SELECT COUNT(*) AS total_all,SUM(CASE WHEN appointment_status = 'pending' THEN 1 ELSE 0 END) AS pending,SUM(CASE WHEN appointment_status = 'approved' THEN 1 ELSE 0 END) AS approved,SUM(CASE WHEN appointment_status = 'completed' THEN 1 ELSE 0 END) AS completed,SUM(CASE WHEN appointment_status = 'rejected' THEN 1 ELSE 0 END) AS rejected FROM appointments";
$stats_result = mysqli_query($connection, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// select appointments query 
$query = "SELECT a.*, cu.user_name  AS customer_name, cu.user_email AS customer_email, lu.user_name  AS lawyer_name, pa.practice_area_name, ts.slot_date, ts.slot_time FROM appointments a JOIN users cu          ON a.customer_id      = cu.user_id JOIN lawyer_profiles lp ON a.lawyer_profile_id = lp.lawyer_profile_id JOIN users lu           ON lp.user_id          = lu.user_id JOIN lawyer_services ls ON a.service_id        = ls.service_id JOIN practice_areas  pa ON ls.practice_area_id = pa.practice_area_id JOIN time_slots      ts ON a.slot_id           = ts.slot_id WHERE 1=1 $status_filter ORDER BY ts.slot_date DESC, ts.slot_time DESC";
$result = mysqli_query($connection, $query);
$showing_count = mysqli_num_rows($result);
?>

<div class="container-fluid py-4">

  <!-- ── PAGE TITLE ── -->
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h5 class="mb-0 text-dark font-weight-bold">Appointment Monitor</h5>
        <p class="text-sm text-secondary mb-0">Platform-wide overview of all client–lawyer appointments.</p>
      </div>
    </div>
  </div>

  <!-- ── STAT CARDS ── -->
  <div class="row mb-4">

    <!-- Total -->
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white">event_note</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Total</p>
            <h4 class="mb-0"><?php echo $stats['total_all']; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">All</span> appointments</p>
        </div>
      </div>
    </div>

    <!-- Pending -->
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-warning">
            <span class="material-symbols-rounded text-white">schedule</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Pending</p>
            <h4 class="mb-0"><?php echo $stats['pending']; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-warning font-weight-bolder">Awaiting</span> lawyer action</p>
        </div>
      </div>
    </div>

    <!-- Approved -->
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-success">
            <span class="material-symbols-rounded text-white">check_circle</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Approved</p>
            <h4 class="mb-0"><?php echo $stats['approved']; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">On-going</span> sessions</p>
        </div>
      </div>
    </div>

    <!-- Completed -->
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center bg-info">
            <span class="material-symbols-rounded text-white">task_alt</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0">Completed</p>
            <h4 class="mb-0"><?php echo $stats['completed']; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-info font-weight-bolder">Finished</span> consultations</p>
        </div>
      </div>
    </div>

  </div>
  <!-- /stat cards -->

  <!-- ── TABLE CARD ── -->
  <div class="row">
    <div class="col-12">
      <div class="card">

        <!-- card header -->
        <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">

          <div>
            <h5 class="mb-0">All Appointments</h5>
            <p class="text-sm mb-0">
              Showing <strong><?php echo $showing_count; ?></strong> record<?php echo $showing_count !== 1 ? 's' : ''; ?>
              <?php if ($active_status) echo ' &mdash; filtered by <strong>' . ucfirst($active_status) . '</strong>'; ?>
            </p>
          </div>

          <!-- search + filters -->
          <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mt-md-0">

            <!-- status filter buttons -->
            <div class="d-flex gap-1 flex-wrap">
              <a href="view_appointments.php" class="btn btn-sm mb-0 <?php echo !$active_status ? 'btn-dark' : 'btn-outline-dark'; ?>">
                All
              </a>
              <a href="?status=pending"
                 class="btn btn-sm mb-0 <?php echo $active_status === 'pending' ? 'btn-warning' : 'btn-outline-warning'; ?>">
                Pending
              </a>
              <a href="?status=approved"
                 class="btn btn-sm mb-0 <?php echo $active_status === 'approved' ? 'btn-success' : 'btn-outline-success'; ?>">
                Approved
              </a>
              <a href="?status=completed"
                 class="btn btn-sm mb-0 <?php echo $active_status === 'completed' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                Completed
              </a>
              <a href="?status=rejected"
                 class="btn btn-sm mb-0 <?php echo $active_status === 'rejected' ? 'btn-danger' : 'btn-outline-danger'; ?>">
                Rejected
              </a>
            </div>

          </div>
        </div>
        <!-- /card header -->

        <!-- table body -->
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="apptTable">

              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">#</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lawyer</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Service</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date &amp; Time</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Place</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Details</th>
                </tr>
              </thead>

              <tbody>
              <?php

              $row_num = 1;
              if ($showing_count > 0) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      $modal_id = 'apptModal' . $row['appointment_id'];
              ?>
                <tr>

                  <!-- # -->
                  <td class="ps-3">
                    <span class="text-secondary text-xs font-weight-bold"><?php echo $row_num++; ?></span>
                  </td>

                  <!-- CLIENT -->
                  <td>
                    <div class="d-flex px-2 py-1 align-items-center gap-2">
                      <div style="
                        width:34px;height:34px;border-radius:50%;flex-shrink:0;
                        background:linear-gradient(135deg,#007acc,#0056b3);
                        color:#fff;font-size:13px;font-weight:700;
                        display:flex;align-items:center;justify-content:center;
                        box-shadow:0 3px 8px rgba(0,122,204,.3)">
                        <?php echo mb_substr($row['customer_name'], 0, 1); ?>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['customer_name']; ?></h6>
                        <p class="text-xs text-secondary mb-0"><?php echo $row['customer_email']; ?></p>
                      </div>
                    </div>
                  </td>

                  <!-- LAWYER -->
                  <td>
                    <div class="d-flex px-2 py-1 align-items-center gap-2">
                      <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;background:linear-gradient(135deg,#2d6a4f,#40916c);color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 8px rgba(40,167,69,.3)">
                        <?php echo mb_substr($row['lawyer_name'], 0, 1); ?>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['lawyer_name']; ?></h6>
                        <p class="text-xs text-secondary mb-0">Attorney</p>
                      </div>
                    </div>
                  </td>

                  <!-- SERVICE -->
                  <td>
                    <p class="text-sm font-weight-bold mb-0"><?php echo $row['practice_area_name']; ?></p>
                  </td>

                  <!-- DATE & TIME -->
                  <td>
                    <p class="text-sm mb-0 font-weight-bold"><?php echo date('d M Y', strtotime($row['slot_date'])); ?></p>
                    <p class="text-xs text-secondary mb-0"><?php echo date('h:i A', strtotime($row['slot_time'])); ?></p>
                  </td>

                  <!-- PLACE -->
                  <td>
                    <span class="text-secondary text-sm"><?php echo $row['appointment_place']; ?></span>
                  </td>

                  <!-- STATUS -->
                  <td class="text-center">
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

                  <!-- DETAILS -->
                  <td class="text-center">
                    <button
                      class="btn btn-sm bg-gradient-info mb-0"
                      data-bs-toggle="modal"
                      data-bs-target="#<?php echo $modal_id; ?>"
                      title="View details">
                      <span class="material-symbols-rounded" style="font-size:15px;vertical-align:middle">visibility</span>
                      View
                    </button>
                  </td>

                </tr>

                <!-- ── Detail modal ── -->
                <div class="modal fade" id="<?php echo $modal_id;?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.18)">

                      <!-- header -->
                      <div style="background:linear-gradient(135deg,#005fa3,#007acc);padding:22px 26px 18px">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h5 class="text-white mb-1" style="font-size:1.05rem">Appointment Details</h5>
                            <?php if ($row['appointment_status'] == 'pending') { ?>
                      <span class="badge badge-sm bg-gradient-primary">
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
                      <span class="badge badge-sm bg-gradient-warning">
                        Completed
                      </span>
                    <?php } ?>
                          </div>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                      </div>

                      <!-- body -->
                      <div class="modal-body p-4">
                        <div class="row g-3">

                          <!-- client -->
                          <div class="col-md-6">
                            <div style="background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0;padding:16px">
                              <p class="text-xs text-secondary text-uppercase mb-2" style="letter-spacing:.6px;font-weight:600">Client</p>
                              <div class="d-flex align-items-center gap-2">
                                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#007acc,#0056b3);color:#fff;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;">
                                  <?php echo strtoupper(mb_substr($row['customer_name'], 0, 1)); ?>
                                </div>
                                <div>
                                  <p class="mb-0 text-sm font-weight-bold"><?php echo $row['customer_name']; ?></p>
                                  <p class="mb-0 text-xs text-secondary"><?php echo $row['customer_email']; ?></p>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- lawyer -->
                          <div class="col-md-6">
                            <div style="background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0;padding:16px">
                              <p class="text-xs text-secondary text-uppercase mb-2" style="letter-spacing:.6px;font-weight:600">Lawyer (Attorney)</p>
                              <div class="d-flex align-items-center gap-2">
                                <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#2d6a4f,#40916c);color:#fff;font-size:15px;font-weight:700;display:flex;align-items:center;justify-content:center;">
                                  <?php echo strtoupper(mb_substr($row['lawyer_name'], 0, 1)); ?>
                                </div>
                                <div>
                                  <p class="mb-0 text-sm font-weight-bold"><?php echo $row['lawyer_name']; ?></p>
                                  <p class="mb-0 text-xs text-secondary">Attorney</p>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- service -->
                          <div class="col-md-4">
                            <div style="background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0;padding:16px;height:100%">
                              <p class="text-xs text-secondary text-uppercase mb-1" style="letter-spacing:.6px;font-weight:600">Service</p>
                              <p class="mb-0 text-sm font-weight-bold">
                                <span class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;color:#007acc">gavel</span>
                                <?php echo $row['practice_area_name']; ?>
                              </p>
                            </div>
                          </div>

                          <!-- date & time -->
                          <div class="col-md-4">
                            <div style="background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0;padding:16px;height:100%">
                              <p class="text-xs text-secondary text-uppercase mb-1" style="letter-spacing:.6px;font-weight:600">Date &amp; Time</p>
                              <p class="mb-0 text-sm font-weight-bold">
                                <span class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;color:#007acc">event</span>
                                <?php echo date('d M Y', strtotime($row['slot_date'])); ?>
                              </p>
                              <p class="mb-0 text-xs text-secondary mt-1">
                                <?php echo date('h:i A', strtotime($row['slot_time'])); ?>
                              </p>
                            </div>
                          </div>

                          <!-- place -->
                          <div class="col-md-4">
                            <div style="background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0;padding:16px;height:100%">
                              <p class="text-xs text-secondary text-uppercase mb-1" style="letter-spacing:.6px;font-weight:600">Location</p>
                              <p class="mb-0 text-sm font-weight-bold">
                                <span class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;color:#007acc">location_on</span>
                                <?php echo $row['appointment_place']; ?>
                              </p>
                            </div>
                          </div>

                          <!-- notes -->
                          <?php if (!empty($row['appointment_notes'])){ ?>
                          <div class="col-12">
                            <div style="background:#fffbeb;border-radius:10px;border:1.5px solid #fde68a;padding:16px">
                              <p class="text-xs text-uppercase mb-1" style="letter-spacing:.6px;font-weight:600;color:#92400e">Lawyer Notes</p>
                              <p class="mb-0 text-sm" style="color:#78350f;white-space:pre-wrap"><?php echo $row['appointment_notes']; ?></p>
                            </div>
                          </div>
                          <?php } ?>

                          <!-- booked on -->
                          <div class="col-12">
                            <p class="text-xs text-secondary mb-0">
                              <span class="material-symbols-rounded me-1" style="font-size:14px;vertical-align:middle">history</span>
                              Booked on <?php echo date('d M Y \a\t h:i A', strtotime($row['appointment_created'])); ?>
                            </p>
                          </div>

                        </div>
                      </div>

                      <!-- footer -->
                      <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-outline-secondary mb-0" data-bs-dismiss="modal">Close</button>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- /modal -->

              <?php
                  }
              } else {
              ?>
                <tr>
                  <td colspan="8" class="text-center py-5">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:10px">
                      <span class="material-symbols-rounded" style="font-size:52px;color:#d2d6da">event_busy</span>
                      <h6 class="text-secondary mb-0">
                        <?php echo ($active_status) ? 'No appointments matched your filter.' : 'No appointments on the platform yet.'; ?>
                      </h6>
                    </div>
                  </td>
                </tr>
              <?php } ?>
              </tbody>

            </table>
          </div>
        </div>
        <!-- /card-body -->

      </div>
    </div>
  </div>
  <!-- /table card -->

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Flash SweetAlert (PRG)
    <?php if (isset($_SESSION['_swal'])) { ?>
    Swal.fire(<?php echo json_encode([
        'icon'  => $_SESSION['_swal']['icon'],
        'title' => $_SESSION['_swal']['title'],
        'text'  => $_SESSION['_swal']['text'],
    ]); ?>);
    <?php unset($_SESSION['_swal']); } ?>
});
</script>

<?php include("./base/footer.php"); ?>
