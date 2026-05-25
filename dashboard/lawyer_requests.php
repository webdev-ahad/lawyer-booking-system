<?php
include("./base/header.php");

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== "admin") {
    header("Location: ./index.php");
    exit();
}
$select_query = "SELECT lr.*, u.user_name, u.user_email FROM lawyer_requests lr JOIN users u ON lr.user_id = u.user_id ORDER BY lr.request_id DESC";
$result = mysqli_query($connection, $select_query);


?>

<div class="container-fluid py-2">

  <!-- Stats Cards -->
  <div class="row mb-2">
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white" style="font-size:22px">list_alt</span>
          </div>
          <?php
            // total requests
            $total_requests_query = "SELECT * FROM lawyer_requests";
            $total_requests_result = mysqli_query($connection, $total_requests_query);
            $total_requests_total = mysqli_num_rows($total_requests_result);

          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Requests</p>
            <h4 class="mb-0"><?php echo $total_requests_total; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-dark font-weight-bolder">All</span> submissions</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#f5a623,#f07c00)">
            <span class="material-symbols-rounded text-white" style="font-size:22px">schedule</span>
          </div>
          <?php
            // pending requests
            $pending_requests_query = "SELECT * FROM lawyer_requests WHERE request_status = 'pending'";
            $pending_requests_result = mysqli_query($connection, $pending_requests_query);
            $pending_requests_total = mysqli_num_rows($pending_requests_result);

          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Pending Requests</p>
            <h4 class="mb-0"><?php echo $pending_requests_total; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#f07c00" class="font-weight-bolder">Awaiting</span> review</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#66bb6a,#388e3c);">
            <span class="material-symbols-rounded text-white" style="font-size:22px">check_circle</span>
          </div>
          <?php
            // approved requests
            $approved_requests_query = "SELECT * FROM lawyer_requests WHERE request_status = 'approved'";
            $approved_requests_result = mysqli_query($connection, $approved_requests_query);
            $approved_requests_total = mysqli_num_rows($approved_requests_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Approved Requests</p>
            <h4 class="mb-0"><?php echo $approved_requests_total; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#388e3c" class="font-weight-bolder">Active</span> lawyers</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center" style="background:linear-gradient(195deg,#ef5350,#b71c1c)">
            <span class="material-symbols-rounded text-white" style="font-size:22px">cancel</span>
          </div>
          <?php
            // rejected requests
            $rejected_requests_query = "SELECT * FROM lawyer_requests WHERE request_status = 'rejected'";
            $rejected_requests_result = mysqli_query($connection, $rejected_requests_query);
            $rejected_requests_total = mysqli_num_rows($rejected_requests_result);
          ?>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Rejected</p>
            <h4 class="mb-0"><?php echo $rejected_requests_total; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span style="color:#b71c1c" class="font-weight-bolder">Declined</span> requests</p>
        </div>
      </div>
    </div>
  </div>

    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Lawyers Requests</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lawyers</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location / Experience</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bar Number</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Consultation Fee</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applied On</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) == 0) { ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            No lawyer requests found
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="../uploads/<?php echo $row['request_profile_photo'] ?>"
                                                        class="avatar avatar-sm me-3 border-radius-lg"
                                                        alt="lawyer">
                                                </div>

                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm"><?php echo $row['user_name'] ?></h6>
                                                    <p class="text-xs text-secondary mb-0"><?php echo $row['user_email'] ?></p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="align-middle text-sm">
                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['request_city'] ?></p>
                                            <p class="text-xs text-secondary mb-0"><?php echo $row['request_experience_years'] ?> yrs exp</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0"><?php echo $row['request_bar_number'] ?></p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-xs font-weight-bold mb-0">Rs. <?php echo $row['request_consultation_fee']; ?></p>
                                        </td>

                                        <td class="align-middle text-center text-sm">

                                            <?php if ($row['request_status'] == "pending") { ?>
                                                <span class="badge badge-sm bg-gradient-warning">Pending</span>

                                            <?php } elseif ($row['request_status'] == "approved") { ?>
                                                <span class="badge badge-sm bg-gradient-success">Approved</span>

                                            <?php } else { ?>
                                                <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                            <?php } ?>

                                        </td>

                                        <td class="align-middle text-center">
                                            <span class="text-secondary text-xs font-weight-bold">
                                                <?php echo date("d/m/y", strtotime($row['created_at'])) ?>
                                            </span>
                                        </td>

                                        <td class="align-middle text-center">

                                            <?php if ($row['request_status'] == "pending") { ?>

                                                <a href="update_request.php?id=<?php echo $row['request_id'] ?>&status=approved"
                                                    class="text-success font-weight-bold text-xs">
                                                    Approve
                                                </a>

                                                |

                                                <a href="update_request.php?id=<?php echo $row['request_id'] ?>&status=rejected"
                                                    class="text-danger font-weight-bold text-xs">
                                                    Reject
                                                </a>

                                            <?php } else { ?>
                                                <span class="text-muted text-xs">No action</span>
                                            <?php } ?>

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

    
    <?php
    include("./base/footer.php");
    ?>