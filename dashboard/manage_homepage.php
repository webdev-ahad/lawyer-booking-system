<?php
include("./base/header.php");

// admin only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Access Denied', 'text' => 'Only admins can manage homepage content.'];
    header("Location: ./auth/sign-in.php");
    exit();
}

// handle update
if (isset($_POST['update_content'])) {
    $content_id = (int)$_POST['content_id'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $body = $_POST['body'];

    $update_query = "UPDATE homepage_content SET title='$title', subtitle='$subtitle', body='$body' WHERE content_id=$content_id";
    $update_result = mysqli_query($connection, $update_query);

    if($update_result){
    $_SESSION['_swal'] = ['icon' => 'success', 'title' => 'Saved!', 'text' => 'Homepage section updated successfully.'];
    }else{
      $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Error!', 'text' => 'Homepage section not updated.'];
    }
    header("Location: manage_homepage.php");
    exit();
}

// fetch all sections
$fetch_query = "SELECT * FROM homepage_content ORDER BY content_id ASC";
$fetch_result = mysqli_query($connection, $fetch_query);
?>

<div class="container-fluid py-4">

  <!-- page title -->
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div>
        <h5 class="mb-0 text-dark font-weight-bold">Homepage Content Manager</h5>
        <p class="text-sm text-secondary mb-0">Edit the titles, subtitles, and body text displayed on the public website.</p>
      </div>
      <a href="../index.php" target="_blank" class="btn bg-gradient-info mb-0">
        <span class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">open_in_new</span>
        Preview Site
      </a>
    </div>
  </div>

  <!-- section cards -->
  <div class="row">

    <?php if ($fetch_result && mysqli_num_rows($fetch_result) > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($fetch_result)) { ?>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card h-100">

        <!-- card header -->
        <div class="card-header pb-0 d-flex align-items-center gap-2">
          <div class="icon icon-sm icon-shape bg-gradient-primary shadow-primary text-center border-radius-lg">
            <i class="material-symbols-rounded opacity-10" style="font-size:16px">edit_note</i>
          </div>
          <div>
            <h6 class="mb-0 text-capitalize"><?php echo str_replace('_', ' ', $row['section']); ?></h6>
            <p class="text-xs text-secondary mb-0">Section ID #<?php echo $row['content_id']; ?></p>
          </div>
        </div>

        <!-- card body -->
        <div class="card-body pt-3">
          <form action="manage_homepage.php" method="POST">
            <input type="hidden" name="content_id" value="<?php echo $row['content_id']; ?>">

            <!-- title -->
            <div class="mb-3">
              <label class="form-label text-xs font-weight-bold text-uppercase text-secondary">Title / Heading</label>
              <input type="text" name="title" class="form-control form-control-sm"
                     value="<?php echo $row['title']; ?>" required>
            </div>

            <!-- subtitle -->
            <div class="mb-3">
              <label class="form-label text-xs font-weight-bold text-uppercase text-secondary">Subtitle / Caption</label>
              <input type="text" name="subtitle" class="form-control form-control-sm"
                     value="<?php echo $row['subtitle']; ?>">
            </div>

            <!-- body -->
            <div class="mb-4">
              <label class="form-label text-xs font-weight-bold text-uppercase text-secondary">Body Text / Description</label>
              <textarea name="body" class="form-control form-control-sm" rows="4"><?php echo $row['body']; ?></textarea>
            </div>

            <button type="submit" name="update_content" class="btn bg-gradient-dark w-100 mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle">save</i>
              Save Changes
            </button>
          </form>
        </div>

      </div>
    </div>

    <?php } ?>
    <?php } else { ?>

    <div class="col-12">
      <div class="card">
        <div class="card-body text-center py-5">
          <i class="material-symbols-rounded" style="font-size:48px;color:#d2d6da">article</i>
          <h6 class="text-secondary mt-3 mb-1">No content sections found</h6>
          <p class="text-xs text-secondary">Add rows to the <code>homepage_content</code> table to manage them here.</p>
        </div>
      </div>
    </div>

    <?php } ?>

  </div>
  <!-- /cards -->

</div>

<?php include("./base/footer.php"); ?>