<footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget">
              <h2 class="logo"><a href="index.php"><img src="./images/logo.png" alt="" height="150px" ></a></h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="https://x.com/"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="https://www.facebook.com"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="https://www.instagram.com"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Quick Links</h2>
              <ul class="list-unstyled">
                <li><a href="index.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Home</a></li>
                <li><a href="about.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>About Us</a></li>
                <li><a href="attorneys.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Attorneys</a></li>
                <li><a href="contact.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Contact</a></li>
                <?php
                  if($_SESSION['user_role'] == "admin"){
                ?>
                  <li><a href="./dashboard/index.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Dashboard</a></li>
                  <li><a href="./dashboard/view_lawyers.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>View Lawyers</a></li>
                  <li><a href="./dashboard/view_requests.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>View Requests</a></li>
                <?php
                  }else if($_SESSION['user_role'] == "lawyer"){
                ?>
                  <li><a href="./dashboard/manage_appointments.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Appointments</a></li>
                  <li><a href="./dashboard/manage_services.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Services</a></li>
                  <li><a href="./dashboard/manage_availablity.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Availablity</a></li>
                  <li><a href="./dashboard/profile.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Profile</a></li>
                <?php
                  }else if($_SESSION['user_role'] == "customer") {
                ?>
                  <li><a href="./dashboard/profile.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Profile</a></li>
                  <li><a href="./dashboard/edit_profile.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>Edit Profile</a></li>
                  <li><a href="./dashboard/my_appointments.php" class="py-1 d-block"><span class="ion-ios-arrow-forward mr-3"></span>My Appointments</a></li>
                <?php
                  }
                ?>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">Karachi, Pakistan</span></li>
	                <li><a href="tel:+923160204264"><span class="icon icon-phone"></span><span class="text">+92 312 34567</span></a></li>
	                <li><a href="mailto:[EMAIL_ADDRESS]"><span class="icon icon-envelope"></span><span class="text">Legalcare@gmail.com</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Business Hours</h2>
              <div class="opening-hours">
              	<h4>Opening Days:</h4>
              	<p class="pl-3">
              		<span>Monday – Friday : 9am to 20 pm</span>
              		<span>Saturday : 9am to 17 pm</span>
              	</p>
              	<h4>Vacations:</h4>
              	<p class="pl-3">
              		<span>All Sunday Days</span>
              		<span>All Official Holidays</span>
              	</p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">

            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This website is made with <i style="color: #f70e0eff;" class="icon-heart" aria-hidden="true"></i> by <a style="color: #007acc;" href="index.php" target="_blank">LegalCare</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js?v=1"></script>
  <script src="js/jquery-migrate-3.0.1.min.js?v=1"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js?v=1"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>