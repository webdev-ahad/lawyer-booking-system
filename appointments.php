<?php
include("./base/header.php");

    if(!isset($_SESSION['user_id'])){

        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Login Required','text'=>'You must be logged in to book an appointment.'];
        header("Location: ./dashboard/auth/sign-in.php");
        exit();
    }
    // only customers can book appointments
    if($_SESSION['user_role'] != 'customer'){

        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Access Denied','text'=>'Only customers can book appointments.'];
        header("Location: ./attorneys.php");
        exit();
    }
    // check slots
    if(!isset($_GET['slot_id'])){

        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Slot','text'=>'The selected slot is not valid or has been booked.'];
        header("Location: ./attorneys.php");
        exit();
    }

    // get slot
    $slot_id = (int) $_GET['slot_id'];

    // check slot is valid
    if($slot_id <= 0 || !is_numeric($slot_id)){
      
      $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Slot','text'=>'The selected slot is not valid or has been booked.'];
      header("Location: ./attorneys.php");
      exit();
    }

    // get slot and lawyer info
    $query = "SELECT ts.*, lp.lawyer_profile_id, u.user_name, u.user_id FROM time_slots ts JOIN lawyer_profiles lp ON ts.lawyer_profile_id = lp.lawyer_profile_id JOIN users u ON lp.user_id = u.user_id WHERE ts.slot_id = '$slot_id' AND ts.slot_status = 'available'";
    $result = mysqli_query($connection, $query);
    $slot = mysqli_fetch_assoc($result);

    // check if slot is available
    if(!$slot){

      $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Slot','text'=>'The selected slot is not valid or has been booked.'];
      header("Location: ./attorneys.php"); 
      exit();
    }

    // handle booking
    if(isset($_POST['book'])){
    
      $customer_id = $_SESSION['user_id'];
      $lawyer_profile_id = $slot['lawyer_profile_id'];
      $service_id = $_POST['service'];
      $place = $_POST['place'];
      
      // validate service
      if(empty($service_id) || !is_numeric($service_id) || $service_id <= 0){
        
          $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Service','text'=>'Please select a valid service.'];
          header("Location: ./appointments.php?slot_id=".$slot_id); exit();
      }

      // validate selected service belongs to this lawyer
      $service_query = "SELECT * FROM lawyer_services WHERE service_id = '$service_id' AND lawyer_profile_id = '$lawyer_profile_id'";
      $service_result = mysqli_query($connection, $service_query);

      // if service not found
      if(mysqli_num_rows($service_result) == 0){

          $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Service','text'=>'The selected service is not valid for this lawyer.'];
          header("Location: ./attorneys.php"); exit();
      }

      // check if slot is still available
      $check_query = "SELECT * FROM time_slots WHERE slot_id = '$slot_id' AND slot_status = 'available'";
      $check_result = mysqli_query($connection, $check_query);

      // if slot is not available
      if(mysqli_num_rows($check_result) == 0){

          $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Slot Unavailable','text'=>'This slot has already been booked. Please choose another.'];
          header("Location: ./attorneys.php"); exit();
      }

      // appointment place validations
      if(empty($place) || strlen($place) < 3 || strlen($place) > 500){

          $_SESSION['_swal'] = ['icon'=>'error','title'=>'Invalid Place','text'=>'Please enter a valid place for the appointment.'];
          header("Location: ./appointments.php?slot_id=".$slot_id); exit();
      }

      // make sure that the customer has not booked this appointment before
      $duplicate_query = "SELECT * FROM appointments WHERE customer_id = '$customer_id' AND slot_id = '$slot_id'";
      $duplicate_result = mysqli_query($connection, $duplicate_query);

      // if customer has already booked this appointment
      if(mysqli_num_rows($duplicate_result) > 0){

        $_SESSION['_swal'] = ['icon'=>'warning','title'=>'Already Booked','text'=>'You have already booked this appointment.'];
        header("Location: ./appointments.php?slot_id=".$slot_id);
        exit();
      }

      // insert appointment
      $appointment_query = "INSERT INTO appointments (customer_id, lawyer_profile_id, service_id, slot_id, appointment_place) VALUES ('$customer_id', '$lawyer_profile_id', '$service_id', '$slot_id', '$place')";
      $appointment_result = mysqli_query($connection, $appointment_query);

      // if appointment not inserted
      if(!$appointment_result){

        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Booking Failed','text'=>'Something went wrong. Please try again later.'];
        header("Location: ./appointments.php?slot_id=".$slot_id); exit();
      }

      // mark slot as booked
      $update_query = "UPDATE time_slots SET slot_status='booked' WHERE slot_id='$slot_id'";
      $update_result = mysqli_query($connection, $update_query);
       
      // if slot not booked
      if(!$update_result){

        $_SESSION['_swal'] = ['icon'=>'error','title'=>'Booking Failed','text'=>'Something went wrong. Please try again later.'];
        header("Location: ./appointments.php?slot_id=".$slot_id); exit();
      }

      // appointment booked successfully
      $_SESSION['_swal'] = ['icon'=>'success','title'=>'Booking Confirmed!','text'=>'Your appointment has been booked successfully.'];
      header("Location: ./dashboard/my_appointments.php"); exit();
    }
?>

<!-- ══ APPOINTMENT BOOKING PAGE ══════════════════════════════════ -->
<section class="appt-hero">
  <div class="appt-hero-overlay"></div>
  <div class="container appt-hero-inner">
    <div class="appt-hero-badge">
      <span>Appointment Booking</span>
    </div>
    <h1 class="at-hero-title text-white">Reserve Your Consultation</h1>
    <p class="appt-hero-sub">Secure your slot with a trusted legal professional in just a few steps.</p>
  </div>
</section>

<section class="appt-section">
  <div class="container">
    <div class="row justify-content-center">

      <!-- LEFT — Slot Summary Card -->
      <div class="col-lg-4 col-md-5 mb-4 mb-md-0">
        <div class="appt-summary-card">
          <div class="appt-summary-header">
            <div class="appt-summary-avatar">
              <span class="flaticon-lawyer"></span>
            </div>
            <div>
              <p class="appt-summary-role">Your Attorney</p>
              <h3 class="appt-summary-name"><?php echo $slot['user_name']; ?></h3>
            </div>
          </div>

          <div class="appt-summary-divider"></div>

          <ul class="appt-summary-list">
            <li class="appt-summary-item">
              <div class="appt-summary-item-icon">
                <i class="ion-ios-calendar"></i>
              </div>
              <div class="appt-summary-item-body">
                <span class="appt-summary-item-label">Appointment Date</span>
                <span class="appt-summary-item-value"><?php echo date('d M Y', strtotime($slot['slot_date'])); ?></span>
              </div>
            </li>
            <li class="appt-summary-item">
              <div class="appt-summary-item-icon">
                <i class="ion-ios-time"></i>
              </div>
              <div class="appt-summary-item-body">
                <span class="appt-summary-item-label">Appointment Time</span>
                <span class="appt-summary-item-value"><?php echo date('h:i A', strtotime($slot['slot_time'])); ?></span>
              </div>
            </li>
            <li class="appt-summary-item">
              <div class="appt-summary-item-icon">
                <i class="ion-ios-checkmark-circle"></i>
              </div>
              <div class="appt-summary-item-body">
                <span class="appt-summary-item-label">Slot Status</span>
                <span class="appt-status-badge">Available</span>
              </div>
            </li>
          </ul>

          <div class="appt-summary-note">
            <i class="ion-ios-information-circle-outline"></i>
            Slots are reserved immediately upon booking. Please arrive 5 minutes early.
          </div>
        </div>
      </div>

      <!-- RIGHT — Booking Form Card -->
      <div class="col-lg-7 col-md-7 offset-lg-1">
        <div class="appt-form-card">
          <div class="appt-form-card-header">
            <h2 class="appt-form-title">Complete Your Booking</h2>
            <p class="appt-form-subtitle">Fill in the details below to confirm your appointment.</p>
          </div>

          <form method="POST" class="appt-form" id="apptBookForm" novalidate>

            <!-- SERVICE SELECT -->
            <div class="appt-form-group">
              <label class="appt-form-label" for="appt-service">
                <i class="ion-ios-briefcase"></i> Select Service
              </label>
              <div class="appt-select-wrap">
                <select id="appt-service" name="service" class="form-control appt-select" required>
                  <option value="" disabled selected>Choose a practice area…</option>
                  <?php
                  $services_query = "
                      SELECT ls.service_id, pa.practice_area_name
                      FROM lawyer_services ls
                      JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id
                      WHERE ls.lawyer_profile_id = ".$slot['lawyer_profile_id']."";
                  $services = mysqli_query($connection, $services_query);
                  while($s = mysqli_fetch_assoc($services)){
                      echo "<option value='".$s['service_id']."'>".$s['practice_area_name']."</option>";
                  }
                  ?>
                </select>
                <span class="appt-select-arrow"><i class="ion-ios-arrow-down"></i></span>
              </div>
            </div>

            <!-- MEETING PLACE -->
            <div class="appt-form-group">
              <label class="appt-form-label" for="appt-place">
                <i class="ion-ios-pin"></i> Meeting Place / Location
              </label>
              <input
                type="text"
                id="appt-place"
                name="place"
                class="form-control appt-input"
                placeholder="e.g. Office, Online (Zoom), Court etc."
                required
              >
            </div>

            <!-- TERMS NOTE -->
            <div class="appt-terms-note">
              <i class="ion-ios-shield-checkmark"></i>
              By confirming, you agree that this booking is binding and the consultation fee applies.
            </div>

            <div class="appt-form-actions">
              <a href="attorneys.php" class="appt-btn-back">
                <i class="ion-ios-arrow-back"></i> Back to Attorneys
              </a>
              <button type="submit" name="book" id="appt-submit" class="btn btn-primary appt-btn-confirm">
                <i class="ion-ios-checkmark-circle-outline"></i> Confirm Booking
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<?php if(isset($_SESSION['_swal'])){ ?>
<script>
  var _swal = <?php echo json_encode($_SESSION['_swal']); ?>;
  <?php unset($_SESSION['_swal']); ?>
  Swal.fire({
    icon:  _swal.icon,
    title: _swal.title,
    text:  _swal.text
  }).then(function(){
    if(_swal.redirect) window.location.href = _swal.redirect;
  });
</script>
<?php } ?>

<?php include("./base/footer.php"); ?>