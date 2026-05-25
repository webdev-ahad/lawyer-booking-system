<?php
include './base/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'lawyer'){
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


$lawyer_id = $data['lawyer_profile_id'];

// add service
if(isset($_POST['add_service'])){
    $practice_id = $_POST['practice_area_id'];

    // prevent duplicates
    $check = "SELECT * FROM lawyer_services WHERE lawyer_profile_id = $lawyer_id AND practice_area_id = $practice_id";
    $exists = mysqli_query($connection, $check);

    if(mysqli_num_rows($exists) == 0){
        $insert_query = "INSERT INTO lawyer_services (lawyer_profile_id, practice_area_id) VALUES ($lawyer_id, $practice_id)";
        $insert_result = mysqli_query($connection, $insert_query);
        if($insert_result){
            $_SESSION['_swal'] = ['icon'=>'success','title'=>'Service Added','text'=>'Practice area added to your profile.'];
        }else{
            $_SESSION['_swal'] = ['icon'=>'error','title'=>'Failed to Add','text'=>'Something went wrong. Please try again.'];
        }
        
    } else {
        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Already Exists','text'=>'This service is already on your profile.'];
    
      }
    header("Location: manage_services.php");
    exit();
}
// delete service
if(isset($_GET['delete'])){
    $service_id = $_GET['delete'];

    $delete = "DELETE FROM lawyer_services WHERE service_id = $service_id AND lawyer_profile_id = $lawyer_id";
    mysqli_query($connection, $delete);
    header("Location: manage_services.php");
    exit();
}

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
  .manage-services-wrap .form-control:focus,
  .manage-services-wrap select.form-control:focus {
    border-color: #007acc !important;
    box-shadow: 0 0 0 2px rgba(0, 122, 204, 0.18) !important;
    outline: none !important;
  }
  .service-item {
    transition: background 0.15s ease;
  }
  .service-item:hover {
    background: #f8f9fa;
  }
</style>

<div class="container-fluid py-2 manage-services-wrap">
    <!-- Add Service Form -->
  <div class="row mb-4">
    <div class="col-lg-12 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-primary me-2">add_circle</i>
            <h6 class="mb-0">Add Practice Area</h6>
          </div>
          <p class="text-sm text-secondary mb-0 mt-1">Select a legal area to add to your profile.</p>
        </div>
        <div class="card-body p-3">
          <form method="POST" id="addServiceForm" novalidate>
            <div class="mb-3">
              <label class="form-label text-sm font-weight-bold">Practice Area</label>
              <select name="practice_area_id" class="form-control" required>
                <option value="" disabled selected>— Select an area —</option>
                <?php
                  $areas = mysqli_query($connection, "SELECT * FROM practice_areas ORDER BY practice_area_name ASC");
                  while($area = mysqli_fetch_assoc($areas)){
                    echo "<option value='" . $area['practice_area_id'] . "'>" . $area['practice_area_name'] . "</option>";
                  }
                ?>
              </select>
            </div>
            <button type="submit" name="add_service" class="btn bg-gradient-primary w-100 mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:16px;vertical-align:middle;">add</i>
              Add Service
            </button>
          </form>
        </div>
        </div>
      </div>

    <!-- Current Services List -->
    <div class="col-lg-12 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <div class="d-flex align-items-center">
            <i class="material-symbols-rounded text-success me-2">gavel</i>
            <h6 class="mb-0">Your Services</h6>
          </div>
          <p class="text-sm text-secondary mb-0 mt-1">Manage the legal areas you currently offer.</p>
        </div>
        <div class="card-body p-3">

          <?php
            $query = "SELECT ls.service_id, pa.practice_area_name
                      FROM lawyer_services ls
                      JOIN practice_areas pa
                      ON ls.practice_area_id = pa.practice_area_id WHERE ls.lawyer_profile_id = $lawyer_id";
            $result = mysqli_query($connection, $query);

            if(mysqli_num_rows($result) == 0){
          ?>
            <p class="text-sm text-secondary mb-0">
              <i class="material-symbols-rounded me-1" style="font-size:15px;vertical-align:middle;">info</i>
              No services added yet.
            </p>
          <?php } else { ?>
            <ul class="list-group list-group-flush">
              <?php while($row = mysqli_fetch_assoc($result)){ ?>
                <li class="list-group-item border-0 ps-0 service-item d-flex align-items-center justify-content-between text-sm py-2">
                  <div class="d-flex align-items-center">
                    <span class="badge badge-sm bg-gradient-success me-2">✓</span>
                    <span><?php echo $row['practice_area_name']; ?></span>
                  </div>
                  <a href="#"
                     class="btn btn-sm btn-link text-danger p-0 mb-0 swal-delete"
                     data-href="?delete=<?php echo $row['service_id']; ?>"
                     title="Delete">
                    <i class="material-symbols-rounded" style="font-size:18px;">delete</i>
                  </a>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>

        </div>
      </div>
    </div>

  </div>
</div>

<script>
// SweetAlert2 delete confirmation for services
document.querySelectorAll('.swal-delete').forEach(function(btn) {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    var href = this.getAttribute('data-href');
    Swal.fire({
      icon: 'warning',
      title: 'Delete Service?',
      text: 'This practice area will be removed from your profile.',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#e53e3e'
    }).then(function(result) {
      if (result.isConfirmed) window.location.href = href;
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('addServiceForm');
  if (!form) return;
  form.addEventListener('submit', function (e) {
    var sel = form.querySelector('select[name="practice_area_id"]');
    if (!sel || !sel.value) {
      e.preventDefault();
      Swal.fire({ icon: 'warning', title: 'Select an Area', text: 'Please choose a practice area to add.' });
    }
  });
});
</script>

<?php include './base/footer.php'; ?>