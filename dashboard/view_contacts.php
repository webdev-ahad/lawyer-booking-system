<?php
include("./base/header.php");

// admin-only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Access Denied', 'text' => 'Only admins can view contact messages.'];
    header("Location: ./auth/sign-in.php");
    exit();
}

// update status
if (isset($_GET['action']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action_status = $_GET['action'];
    $valid_statuses = ['unread', 'read', 'replied'];
    if (in_array($action_status, $valid_statuses)) {
        $update_id = (int)$_GET['id'];
        $update_q = mysqli_query($connection, "UPDATE contact SET contact_status = '$action_status' WHERE contact_id = '$update_id'");
        if($update_q){
            $_SESSION['_swal'] = ['icon' => 'success', 'title' => 'Status Updated', 'text' => 'Contact message status has been updated.'];
        }else{
            $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Failed to update status.'];
        }
        header("Location: view_contacts.php");
        exit();
    }
}

// delete message 
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id = (int) $_GET['delete'];
    $del_q = mysqli_query($connection, "DELETE FROM contact WHERE contact_id = '$del_id'");
    if($del_q){
        $_SESSION['_swal'] = ['icon' => 'success', 'title' => 'Deleted', 'text' => 'Contact message has been removed.'];
    }else{
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Error', 'text' => 'Failed to delete contact message.'];
    }
    header("Location: view_contacts.php");
    exit();
}

// fetch messages 
$contacts_query  = "SELECT * FROM contact ORDER BY contact_id DESC";
$contacts_result = mysqli_query($connection, $contacts_query);
$total_contacts  = mysqli_num_rows($contacts_result);

// stats
$total_query = "SELECT * FROM contact";
$total_result = mysqli_query($connection, $total_query);
$total_all = mysqli_num_rows($total_result);
?>

<div class="container-fluid py-4">

  <!-- PAGE TITLE -->
  <div class="row mb-3">
    <div class="col-12">
      <h5 class="mb-0 text-dark font-weight-bold">Contact Messages</h5>
      <p class="text-sm text-secondary mb-0">All messages submitted through the website contact form.</p>
    </div>
  </div>

  <!-- STAT CARDS -->
  <div class="row mb-4">

    <!-- Total -->
    <div class="col-xl-4 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white">mail</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Messages</p>
            <h4 class="mb-0"><?php echo $total_all; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">All time</span> submissions</p>
        </div>
      </div>
    </div>

    <!-- Showing now -->
    <div class="col-xl-4 col-sm-6 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white">filter_list</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Showing Now</p>
            <h4 class="mb-0"><?php echo $total_contacts; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm">
            <span class='text-success font-weight-bolder'>All</span> messages displayed

          </p>
        </div>
      </div>
    </div>

    <!-- Latest -->
    <div class="col-xl-4 col-sm-6 mb-4">
      <?php
        $latest_q = mysqli_query($connection, "SELECT contact_name FROM contact ORDER BY contact_id DESC LIMIT 1");
        $latest   = mysqli_fetch_assoc($latest_q);
        $latest_name = $latest ? $latest['contact_name'] : 'N/A';
      ?>
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute d-flex align-items-center justify-content-center">
            <span class="material-symbols-rounded text-white">person</span>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Latest Sender</p>
            <h4 class="mb-0" style="font-size:1.1rem"><?php echo $latest_name; ?></h4>
          </div>
        </div>
        <hr class="dark horizontal my-0">
        <div class="card-footer p-3">
          <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">Most recent</span> contact</p>
        </div>
      </div>
    </div>

  </div>
  <!-- /stat cards -->

  <!-- ── TABLE CARD ── -->
  <div class="row">
    <div class="col-12">
      <div class="card">

        <!-- card header: title + search -->
        <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap gap-3">
          <div>
            <h5 class="mb-0">All Contact Messages</h5>
            <p class="text-sm mb-0">Click <strong>View</strong> to read the full message</p>
          </div>
        </div>

        <!-- table -->
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="contactsTable">

              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">#</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Sender</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Preview</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Submitted At</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                </tr>
              </thead>

              <tbody>
              <?php
              $row_num = 1;
              if ($total_contacts > 0){
                  while ($row = mysqli_fetch_assoc($contacts_result)){
                      $modal_id     = 'msgModal' . $row['contact_id'];
              ?>  
                <tr>

                  <!-- # -->
                  <td class="ps-3">
                    <span class="text-secondary text-xs font-weight-bold"><?php echo $row_num++; ?></span>
                  </td>

                  <!-- SENDER -->
                  <td>
                    <div class="d-flex px-2 py-1 align-items-center gap-2">
                      <!-- avatar initial -->
                      <div style="
                        width:36px;height:36px;border-radius:50%;flex-shrink:0;
                        background:linear-gradient(135deg,#007acc,#0056b3);
                        color:#fff;font-size:14px;font-weight:700;
                        display:flex;align-items:center;justify-content:center;
                        box-shadow:0 3px 8px rgba(0,122,204,.3)">
                        <?php echo mb_substr($row['contact_name'], 0, 1); ?>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm"><?php echo $row['contact_name']; ?></h6>
                        <p class="text-xs text-secondary mb-0"><?php echo $row['contact_email']; ?></p>
                      </div>
                    </div>
                  </td>

                  <!-- SUBJECT -->
                  <td>
                    <p class="text-sm font-weight-bold mb-0"><?php echo $row['contact_subject']; ?></p>
                  </td>

                  <!-- PREVIEW -->
                  <td>
                    <p class="text-xs text-secondary mb-0" style="max-width:240px"><?php echo mb_substr($row['contact_message'], 0, 50); ?></p>
                  </td>

                  <!-- STATUS -->
                  <td class="text-center">
                    <?php 
                      $status = $row['contact_status'] ?? 'unread';
                    ?>
                    <?php if($status == 'unread'){ ?>
                    <span class="badge badge-sm bg-gradient-warning">unread</span>
                    <?php } ?>
                    <?php if($status == 'read'){ ?>
                    <span class="badge badge-sm bg-gradient-info">read</span>
                    <?php } ?>
                    <?php if($status == 'replied'){ ?>
                    <span class="badge badge-sm bg-gradient-success">replied</span>
                    <?php } ?>
                  </td>

                  <!-- SUBMITTED AT -->
                  <td class="text-center">
                    <p class="text-xs text-secondary mb-0">
                      <?php echo date("d-m-Y", strtotime($row['created_at'])); ?>
                    </p>
                    <p class="text-xs text-secondary mb-0">
                      <?php echo date("h:i A", strtotime($row['created_at'])); ?>
                    </p>
                  </td>

                  <!-- ACTIONS -->
                  <td class="text-center">

                    <!-- Mark as Read -->
                    <?php if ($status !== 'read'){ ?>
                    <a href="view_contacts.php?action=read&id=<?php echo $row['contact_id']; ?>"
                       class="btn btn-sm bg-gradient-info mb-0"
                       title="Mark as Read">
                      <span class="material-symbols-rounded" style="font-size:15px;vertical-align:middle">mark_email_read</span>
                      Read
                    </a>
                    <?php } ?>

                    <!-- Mark as Replied -->
                    <?php if ($status !== 'replied'){ ?>
                    <a href="view_contacts.php?action=replied&id=<?php echo $row['contact_id']; ?>"
                       class="btn btn-sm bg-gradient-success mb-0"
                       title="Mark as Replied">
                      <span class="material-symbols-rounded" style="font-size:15px;vertical-align:middle">reply</span>
                      Replied
                    </a>
                    <?php } ?>

                    <!-- View btn -->
                    <button
                      class="btn btn-sm bg-gradient-info mb-0 me-1"
                      data-bs-toggle="modal"
                      data-bs-target="#<?php echo $modal_id; ?>"
                      title="Read full message">
                      <span class="material-symbols-rounded" style="font-size:15px;vertical-align:middle">visibility</span>
                      View
                    </button>

                    <!-- Delete btn -->
                    <a
                      href="#"
                      class="btn btn-sm btn-danger mb-0 swal-delete"
                      data-href="view_contacts.php?delete=<?php echo $row['contact_id']; ?>"
                      title="Delete message">
                      <span class="material-symbols-rounded" style="font-size:15px;vertical-align:middle">delete</span>
                      Delete
                    </a>

                  </td>
                </tr>

                <!-- ── Full-message modal ── -->
                <div class="modal fade" id="<?php echo $modal_id; ?>" tabindex="-1" aria-labelledby="<?php echo $modal_id; ?>Label" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.18)">

                      <!-- modal header -->
                      <div style="background:linear-gradient(135deg,#005fa3,#007acc);padding:20px 24px 16px">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <h5 class="text-white mb-1" style="font-size:1rem" id="<?php echo $modal_id; ?>Label">
                              <?php echo $row['contact_subject']; ?>
                            </h5>
                            <span class="text-white opacity-75" style="font-size:.8rem">
                              <span class="material-symbols-rounded" style="font-size:14px;vertical-align:middle">person</span>
                              <?php echo $row['contact_name']; ?>
                              &nbsp;&bull;&nbsp;
                              <span class="material-symbols-rounded" style="font-size:14px;vertical-align:middle">mail</span>
                              <a href="mailto:<?php echo $row['contact_email']; ?>" style="color:rgba(255,255,255,.85);text-decoration:none"><?php echo $row['contact_email']; ?></a>
                            </span>
                          </div>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                      </div>

                      <!-- modal body -->
                      <div class="modal-body p-4">
                        <p class="text-sm mb-3" style="color:#7b809a;text-transform:uppercase;letter-spacing:.6px;font-weight:600">Message</p>
                        <div style="
                          background:#f8fafc;
                          border-radius:10px;
                          border:1.5px solid #e2e8f0;
                          padding:18px 20px;
                          font-size:.9rem;
                          color:#344767;
                          line-height:1.7;
                          white-space:pre-wrap;
                          word-break:break-word">
                          <?php echo $row['contact_message']; ?>
                        </div>
                      </div>

                      <!-- modal footer -->
                      <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex gap-2">
                        <a href="mailto:<?php echo $row['contact_email']; ?>?subject=Re: <?php echo $row['contact_subject']; ?>"
                           class="btn bg-gradient-dark mb-0 flex-fill">
                          <span class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">reply</span>
                          Reply via Email
                        </a>
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
                  <td colspan="6" class="text-center py-5">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:10px">
                      <span class="material-symbols-rounded" style="font-size:48px;color:#d2d6da">inbox</span>
                      <h6 class="text-secondary mb-0">
                        <?php echo $total_contacts ? 'No messages matched your search.' : 'No contact messages yet.'; ?>
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

    // ── Delete confirmation ──────────────────────────────────────────────────
    document.querySelectorAll('.swal-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            Swal.fire({
                icon: 'warning',
                title: 'Delete Message?',
                text: 'This contact message will be permanently removed.',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#e53e3e'
            }).then(function (r) {
                if (r.isConfirmed) window.location.href = href;
            });
        });
    });

    // ── Flash SweetAlert (PRG pattern) ──────────────────────────────────────
    <?php if (isset($_SESSION['_swal'])): ?>
    Swal.fire(<?php echo json_encode([
        'icon'  => $_SESSION['_swal']['icon'],
        'title' => $_SESSION['_swal']['title'],
        'text'  => $_SESSION['_swal']['text'],
    ]); ?>);
    <?php unset($_SESSION['_swal']); endif; ?>

});
</script>

<?php include("./base/footer.php"); ?>