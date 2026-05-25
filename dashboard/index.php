<?php
  include("./base/header.php");
  include("./config/db_connection.php");

if(!isset($_SESSION['user_role'])){
  echo "<script>
  location.assign('./auth/sign-in.php');
  </script>";
  exit();
}


?>
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Welcome, <?php echo $_SESSION['user_name'] ?>!</h3>
          <p class="mb-4">
            Check the lawyers, users and appointments.
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <?php
                // total lawyer count
                  $lawyer_query = "SELECT * FROM lawyer_profiles";
                  $lawyer_result = mysqli_query($connection, $lawyer_query);
                  $lawyer_total = mysqli_num_rows($lawyer_result);
                ?>
                <div>
                  <p class="text-sm mb-0 text-capitalize">Our Lawyers</p>
                  <h4 class="mb-0"><?php echo $lawyer_total ?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-primary shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <?php
                  // total user count
                    $user_query = "SELECT * FROM users";
                    $user_result = mysqli_query($connection, $user_query);
                    $user_total  = mysqli_num_rows($user_result);
                ?>
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Users</p>
                  <h4 class="mb-0"><?php echo $user_total?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-primary shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">person</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <?php
                  // total appointment count (all appointments)
                  $app_query = "SELECT * FROM appointments";
                  $app_result = mysqli_query($connection, $app_query);
                  $app_total = mysqli_num_rows($app_result);

                ?>
                <div>
                  <p class="text-sm mb-0 text-capitalize">Appointments</p>
                  <h4 class="mb-0"><?php echo $app_total?></h4>
                </div>  
                <div class="icon icon-md icon-shape bg-gradient-primary shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">leaderboard</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>than yesterday</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <?php
                  // successful appointment count (completed appointments)
                    $success_app_query = "SELECT * FROM appointments WHERE appointment_status = 'completed'";
                    $success_app_result = mysqli_query($connection, $success_app_query);
                    $success_app_total = mysqli_num_rows($success_app_result);
                ?>
                <div>
                  <p class="text-sm mb-0 text-capitalize">Successful Appointments</p>
                  <h4 class="mb-0"><?php echo $success_app_total?></h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-primary shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span>than yesterday</p>
            </div>
          </div>
        </div>
      </div>
<?php
$role    = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];

if (isset($_SESSION['user_role'])) {

    // stats for chart footers
    $appt_month_query = "SELECT * FROM appointments WHERE MONTH(appointment_created)=MONTH(NOW()) AND YEAR(appointment_created)=YEAR(NOW())";
    $appt_month_result = mysqli_query($connection, $appt_month_query);
    $appt_month = mysqli_num_rows($appt_month_result);

    $pending_query = "SELECT * FROM appointments WHERE appointment_status='pending'";
    $pending_result = mysqli_query($connection, $pending_query);
    $pending_cnt = mysqli_num_rows($pending_result);

    $active_query = "SELECT * FROM appointments WHERE appointment_status='approved'";
    $active_result = mysqli_query($connection, $active_query);
    $active_cnt = mysqli_num_rows($active_result);

    $new_users_query = "SELECT * FROM users WHERE WEEK(created_at)=WEEK(NOW())";
    $new_users_result = mysqli_query($connection, $new_users_query);
    $new_users_cnt = mysqli_num_rows($new_users_result);

    // admin/lawyer appointments table
    $appt_query = "SELECT a.appointment_id, a.appointment_status, a.appointment_created, cu.user_name AS client_name, cu.user_email AS client_email, lu.user_name AS lawyer_name, ts.slot_date, ts.slot_time FROM appointments a JOIN users cu ON a.customer_id = cu.user_id JOIN lawyer_profiles lp ON a.lawyer_profile_id = lp.lawyer_profile_id JOIN users lu ON lp.user_id = lu.user_id JOIN time_slots ts ON a.slot_id = ts.slot_id ORDER BY a.appointment_created DESC LIMIT 8";
    $appt_result = mysqli_query($connection, $appt_query);

    // customer appointments table
    if ($role === 'customer') {
        $cust_total_query = mysqli_query($connection, "SELECT * FROM appointments WHERE customer_id='$user_id'");
        $cust_total = mysqli_num_rows($cust_total_query);

        $cust_query = "SELECT a.appointment_status, a.appointment_created, lu.user_name AS lawyer_name, pa.practice_area_name, ts.slot_date, ts.slot_time FROM appointments a JOIN lawyer_profiles lp ON a.lawyer_profile_id = lp.lawyer_profile_id JOIN users lu ON lp.user_id = lu.user_id JOIN lawyer_services ls ON a.service_id = ls.service_id JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id JOIN time_slots ts ON a.slot_id = ts.slot_id WHERE a.customer_id = '$user_id' ORDER BY a.appointment_created DESC LIMIT 8";
        $cust_result = mysqli_query($connection, $cust_query);
    } else {
        $cust_result = null;
    }

}
?>

<?php if ($role === 'admin' || $role === 'lawyer') { ?>

      <div class="row">

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card h-100">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <div class="icon icon-md icon-shape bg-gradient-success shadow-success text-center border-radius-lg me-3">
                  <i class="material-symbols-rounded opacity-10">calendar_month</i>
                </div>
                <div>
                  <h6 class="mb-0">Consultation Volume</h6>
                  <p class="text-sm text-secondary mb-0">Monthly target vs actual allocations</p>
                </div>
              </div>
              <div class="pt-3">
                <div class="d-flex justify-content-between align-items-baseline mb-1">
                  <span class="text-xs text-secondary font-weight-bold">Active Month Progress</span>
                  <span class="text-sm font-weight-bolder text-dark"><?php echo $appt_month; ?> / 100 Bookings</span>
                </div>
                <div class="progress progress-md mb-0">
                  <div class="progress-bar bg-gradient-success" role="progressbar" style="width: <?php echo min(100, max(5, $appt_month)); ?>%" aria-valuenow="<?php echo $appt_month; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
              <hr class="dark horizontal mt-4 mb-3">
              <div class="d-flex align-items-center">
                <i class="material-symbols-rounded text-sm my-auto me-1 text-success">trending_up</i>
                <p class="mb-0 text-sm"><span class="font-weight-bold text-success"><?php echo $appt_month; ?></span> total appointments initialized.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card h-100">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <div class="icon icon-md icon-shape bg-gradient-warning shadow-warning text-center border-radius-lg me-3">
                  <i class="material-symbols-rounded opacity-10">gavel</i>
                </div>
                <div>
                  <h6 class="mb-0">Case Status Distribution</h6>
                  <p class="text-sm text-secondary mb-0">Pending vs Approved active files</p>
                </div>
              </div>
              <div class="row pt-3 text-center">
                <div class="col-6 border-end border-light">
                  <h4 class="text-warning mb-0 font-weight-bolder"><?php echo $pending_cnt; ?></h4>
                  <p class="text-xs text-secondary font-weight-bold uppercase mb-0">Awaiting Action</p>
                </div>
                <div class="col-6">
                  <h4 class="text-success mb-0 font-weight-bolder"><?php echo $active_cnt; ?></h4>
                  <p class="text-xs text-secondary font-weight-bold uppercase mb-0">Confirmed Sessions</p>
                </div>
              </div>
              <hr class="dark horizontal mt-3 mb-3">
              <div class="d-flex align-items-center">
                <i class="material-symbols-rounded text-sm my-auto me-1 text-warning">schedule</i>
                <p class="mb-0 text-sm">Actionable pipeline distribution index.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4 mb-3">
          <div class="card h-100">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <div class="icon icon-md icon-shape bg-gradient-info shadow-info text-center border-radius-lg me-3">
                  <i class="material-symbols-rounded opacity-10">group_add</i>
                </div>
                <div>
                  <h6 class="mb-0">New Client Onboarding</h6>
                  <p class="text-sm text-secondary mb-0">Weekly user registration logs</p>
                </div>
              </div>
              <div class="pt-2">
                <div class="bg-light border-radius-lg p-3 d-flex justify-content-between align-items-center">
                  <div>
                    <p class="text-xs font-weight-bold text-secondary mb-0">Current Week Interval</p>
                    <h5 class="text-info font-weight-bolder mb-0">+<?php echo $new_users_cnt; ?> Accounts</h5>
                  </div>
                  <span class="badge bg-gradient-info text-xxs">Live Pulse</span>
                </div>
              </div>
              <hr class="dark horizontal mt-3 mb-3">
              <div class="d-flex align-items-center">
                <i class="material-symbols-rounded text-sm my-auto me-1 text-info">person_add</i>
                <p class="mb-0 text-sm">Onboarding verification operational standards met.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row mb-4">

        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Active Legal Consultations Queue</h6>
                  <p class="text-sm mb-0 text-secondary">Live view of recent appointment requests</p>
                </div>
                <a href="view_appointments.php" class="btn btn-sm bg-gradient-dark mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">open_in_new</i>View All
                </a>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client Profile</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Appointed Counsel</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Target Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if ($appt_result && mysqli_num_rows($appt_result) > 0){ ?>
                    <?php while ($row = mysqli_fetch_assoc($appt_result)){
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#344767,#526373);color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <?php echo mb_substr($row['client_name'], 0, 1); ?>
                          </div>
                          <div class="d-flex flex-column justify-content-center ms-2">
                            <h6 class="mb-0 text-sm"><?php echo $row['client_name']; ?></h6>
                            <p class="text-xs text-secondary mb-0"><?php echo $row['client_email']; ?></p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $row['lawyer_name']; ?></p>
                        <p class="text-xs text-secondary mb-0">Legal Counsel</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo date('d M Y', strtotime($row['slot_date'])); ?></span><br>
                        <span class="text-secondary text-xs"><?php echo date('h:i A', strtotime($row['slot_time'])); ?></span>
                      </td>
                      <?php if ($row['appointment_status'] === 'approved') { ?>
                      <td class="align-middle text-center">
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      </td>
                      <?php } elseif ($row['appointment_status'] === 'pending') { ?>
                      <td class="align-middle text-center">
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      </td>
                      <?php } elseif ($row['appointment_status'] === 'rejected') { ?>
                      <td class="align-middle text-center">
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      </td>
                      <?php } elseif ($row['appointment_status'] === 'completed') { ?>
                      <td class="align-middle text-center">
                        <span class="badge badge-sm bg-gradient-primary">Completed</span>
                      </td>
                      <?php } ?>
                    </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td colspan="4" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center gap-2">
                          <i class="material-symbols-rounded" style="font-size:42px;color:#d2d6da">event_busy</i>
                          <h6 class="text-secondary mb-0">No consultations in the queue</h6>
                          <p class="text-xs text-secondary mb-0">Appointments will appear here once clients book sessions.</p>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="card h-100">
            <div class="card-header pb-0">
              <h6 class="mb-0">Recent Contact Messages</h6>
              <p class="text-sm text-secondary">Latest inquiries from the public</p>
            </div>
            <div class="card-body p-3">
              <?php
                $contact_q = mysqli_query($connection, "SELECT contact_name, contact_subject, contact_status, contact_email FROM contact ORDER BY contact_id DESC LIMIT 6");
              ?>
              <?php if ($contact_q && mysqli_num_rows($contact_q) > 0) { ?>
                <?php while ($cm = mysqli_fetch_assoc($contact_q)) { ?>
                <div class="d-flex align-items-center mb-3">
                  <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#344767,#526373);color:#fff;font-size:14px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <?php echo strtoupper(substr($cm['contact_name'], 0, 1)); ?>
                  </div>
                  <div class="ms-3 flex-grow-1">
                    <h6 class="mb-0 text-sm"><?php echo $cm['contact_name']; ?></h6>
                    <p class="text-xs text-secondary mb-0"><?php echo $cm['contact_subject']; ?></p>
                  </div>
                  <div>
                    <?php if ($cm['contact_status'] == 'unread') { ?>
                      <span class="badge bg-gradient-warning text-xxs">Unread</span>
                    <?php } elseif ($cm['contact_status'] == 'read') { ?>
                      <span class="badge bg-gradient-info text-xxs">Read</span>
                    <?php } else { ?>
                      <span class="badge bg-gradient-success text-xxs">Replied</span>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
                <a href="view_contacts.php" class="btn btn-sm bg-gradient-dark w-100 mb-0 mt-2">View All Messages</a>
              <?php } else { ?>
                <div class="text-center py-4">
                  <i class="material-symbols-rounded" style="font-size:36px;color:#d2d6da">mail</i>
                  <p class="text-secondary text-sm mt-2 mb-0">No messages yet.</p>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>

      </div>
      <?php } else { ?>

      <style>
        .lc-action-card { transition: transform .22s ease, box-shadow .22s ease; border-radius: 16px !important; }
        .lc-action-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,.13) !important; }
        .lc-action-icon { width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
      </style>

      <div class="row mt-3 mb-2">

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card lc-action-card h-100">
            <div class="card-body p-4 d-flex flex-column">
              <div class="lc-action-icon bg-gradient-primary shadow-primary">
                <i class="material-symbols-rounded text-white" style="font-size:30px">gavel</i>
              </div>
              <h6 class="font-weight-bolder mb-1">Request Legal Counsel</h6>
              <p class="text-sm text-secondary mb-4">Browse certified specialists and book a consultation slot.</p>
              <div class="mt-auto">
                <a href="../attorneys.php" class="btn bg-gradient-primary w-100 mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">add_circle</i>Book Now
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card lc-action-card h-100">
            <div class="card-body p-4 d-flex flex-column">
              <div class="lc-action-icon bg-gradient-info shadow-info">
                <i class="material-symbols-rounded text-white" style="font-size:30px">account_balance</i>
              </div>
              <h6 class="font-weight-bolder mb-1">Practice Areas</h6>
              <p class="text-sm text-secondary mb-4">Explore our firm's core fields of law and specialist domains.</p>
              <div class="mt-auto">
                <a href="../practice_areas.php" class="btn bg-gradient-info w-100 mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">visibility</i>View Domains
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4 mb-4">
          <div class="card lc-action-card h-100">
            <div class="card-body p-4 d-flex flex-column">
              <div class="lc-action-icon bg-gradient-warning shadow-warning">
                <i class="material-symbols-rounded text-white" style="font-size:30px">calendar_month</i>
              </div>
              <h6 class="font-weight-bolder mb-1">My Consultations</h6>
              <p class="text-sm text-secondary mb-4">Track your scheduled meetings, check updates, and view details.</p>
              <div class="mt-auto">
                <a href="my_appointments.php" class="btn bg-gradient-warning w-100 mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">event_note</i>View Status
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">Your Scheduled Consultations</h6>
                  <p class="text-sm mb-0 text-secondary">All your booked legal sessions at a glance</p>
                </div>
                <a href="my_appointments.php" class="btn btn-sm bg-gradient-dark mb-0">
                  <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">open_in_new</i>View All
                </a>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Specialist</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Practice Area</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Appointment Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if ($cust_result && mysqli_num_rows($cust_result) > 0) { ?>
                    <?php while ($row = mysqli_fetch_assoc($cust_result)) { ?>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1 align-items-center">
                          <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#007acc,#0056b3);color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <?php echo strtoupper(substr($row['lawyer_name'], 0, 1)); ?>
                          </div>
                          <div class="d-flex flex-column justify-content-center ms-2">
                            <h6 class="mb-0 text-sm"><?php echo $row['lawyer_name']; ?></h6>
                            <p class="text-xs text-secondary mb-0">Legal Specialist</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $row['practice_area_name']; ?></p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold"><?php echo date('d M Y', strtotime($row['slot_date'])); ?></span><br>
                        <span class="text-secondary text-xs"><?php echo date('h:i A', strtotime($row['slot_time'])); ?></span>
                      </td>
                      <td class="align-middle text-center">
                        <?php
                          $st = $row['appointment_status'];
                          if ($st == 'approved') { echo '<span class="badge badge-sm bg-gradient-success">Approved</span>'; }
                          elseif ($st == 'pending') { echo '<span class="badge badge-sm bg-gradient-warning">Pending</span>'; }
                          elseif ($st == 'completed') { echo '<span class="badge badge-sm bg-gradient-info">Completed</span>'; }
                          elseif ($st == 'rejected') { echo '<span class="badge badge-sm bg-gradient-danger">Rejected</span>'; }
                          else { echo '<span class="badge badge-sm bg-gradient-secondary">' . $st . '</span>'; }
                        ?>
                      </td>
                    </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td colspan="4" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center gap-2">
                          <i class="material-symbols-rounded" style="font-size:42px;color:#d2d6da">calendar_month</i>
                          <h6 class="text-secondary mb-0">No consultations booked yet</h6>
                          <p class="text-xs text-secondary mb-0">Use "Request Legal Counsel" above to book your first session.</p>
                        </div>
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
      <?php } ?>

    </div><?php include("./base/footer.php"); ?>