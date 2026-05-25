    <?php
		include("./base/header.php");
	
	$hero_query = "SELECT * FROM homepage_content WHERE section='hero'";
	$hero_result = mysqli_query($connection, $hero_query);
    $hero_row = mysqli_fetch_assoc($hero_result);	
    
    $lawyer_query = "SELECT lp.*, u.user_name FROM lawyer_profiles lp INNER JOIN users u ON lp.user_id = u.user_id ORDER BY rand() LIMIT 6";
    $lawyer_query_result = mysqli_query($connection, $lawyer_query);
	?>
    <div class="hero-wrap js-fullheight" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-6 ftco-animate">
          	<h2 class="subheading">Welcome To Legalcare</h2>
          	<h1>Attorneys Fighting For Your 
						  <span
						     class="txt-rotate"
						     data-period="2000"
						     data-rotate='[ "Freedom.", "Rights.", "Case.", "Custody." ]'></span>
						</h1>
            <!-- <h1 class="mb-4">Attorneys Fighting For Your Freedom</h1> -->
            <p class="mb-4"><?php echo $hero_row['subtitle']?></p>
            <p><a href="attorneys.php" class="btn btn-primary mr-md-4 py-3 px-5">Get Legal Advice <span class="ion-ios-arrow-forward"></span></a></p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-no-pt">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-3 py-5">
	          <div class="heading-section ftco-animate">
	          	<span class="subheading">Services</span>
	            <h2 class="mb-4">Why Select Us?</h2>
	            <p>LegalCare combines professional legal expertise with modern technology to make lawyer discovery and appointment booking simple, secure, and efficient for clients across multiple legal practice areas.</p>
	            <p><a href="attorneys.php" class="btn btn-primary py-3 px-4">Book a Consultation</a></p>
	          </div>
    			</div>
    			<div class="col-lg-9 services-wrap px-4 pt-5">
    				<div class="row pt-md-3">
    					<div class="col-md-4 d-flex align-items-stretch">
		    				<div class="services text-center">
		    					<div class="icon d-flex justify-content-center align-items-center">
		    						<span class="flaticon-lawyer"></span>
		    					</div>
		    					<div class="text">
		    						<h3>Fight for Justice</h3>
								<p>Our legal professionals are committed to protecting client rights and providing strong representation in criminal, civil, family, and corporate legal matters.</p>
								</div>
		    					<a href="attorneys.php" class="btn-custom d-flex align-items-center justify-content-center"><span class="ion-ios-arrow-round-forward"></span></a>
		    				</div>
		    			</div>
		    			<div class="col-md-4 d-flex align-items-stretch">
		    				<div class="services text-center">
		    					<div class="icon d-flex justify-content-center align-items-center">
		    						<span class="flaticon-lawyer"></span>
		    					</div>
		    					<div class="text">
		    						<h3>Best Case Strategy</h3>
		    						<p>Every case is approached with careful analysis and strategic planning to ensure clients receive practical legal solutions tailored to their situation.</p>
		    					</div>
		    					<a href="attorneys.php" class="btn-custom d-flex align-items-center justify-content-center"><span class="ion-ios-arrow-round-forward"></span></a>
		    				</div>
		    			</div>
		    			<div class="col-md-4 d-flex align-items-stretch">
		    				<div class="services text-center">
		    					<div class="icon d-flex justify-content-center align-items-center">
		    						<span class="flaticon-lawyer"></span>
		    					</div>
		    					<div class="text">
		    						<h3>Experienced Attorney</h3>
								<p>LegalCare connects clients with verified and experienced attorneys who possess strong legal knowledge and proven courtroom and consultation expertise.</p>
		    					</div>
		    					<a href="attorneys.php" class="btn-custom d-flex align-items-center justify-content-center"><span class="ion-ios-arrow-round-forward"></span></a>
		    				</div>
		    			</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>
   	
    <section class="ftco-section ftco-no-pt ftco-no-pb">
    	<div class="container">
    		<div class="row d-flex">
    			<div class="col-md-6 d-flex">
    				<div class="img img-video d-flex align-self-stretch align-items-center justify-content-center justify-content-md-end" style="background-image:url(images/about.jpg);">
    					<a href="https://vimeo.com/45830194" class="icon-video popup-vimeo d-flex justify-content-center align-items-center">
    						<span class="icon-play"></span>
    					</a>
    				</div>
    			</div>
    			<div class="col-md-6 pl-md-5">
    				<div class="row justify-content-start pt-3 pb-3">
		          <div class="col-md-12 heading-section ftco-animate">
		          	<span class="subheading">Welcome to Legalcare</span>
		            <h2 class="mb-4">We Always Fight For Your Justice to Win</h2>
		            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
		            <div class="tabulation-2 mt-4">
									<ul class="nav nav-pills nav-fill d-md-flex d-block">
									  <li class="nav-item mb-md-0 mb-2">
									    <a class="nav-link active py-2" data-toggle="tab" href="#home1">Our Mission</a>
									  </li>
									  <li class="nav-item px-lg-2 mb-md-0 mb-2">
									    <a class="nav-link py-2" data-toggle="tab" href="#home2">Our Vision</a>
									  </li>
									  <li class="nav-item">
									    <a class="nav-link py-2 mb-md-0 mb-2" data-toggle="tab" href="#home3">Our Value</a>
									  </li>
									</ul>
									<div class="tab-content bg-light rounded mt-2">
									  <div class="tab-pane container p-0 active" id="home1">
										<p>Our mission at LegalCare is to connect clients with trusted legal professionals through a secure and modern digital platform. We aim to simplify legal consultation, improve accessibility to qualified attorneys, and provide a transparent appointment booking experience for everyone.</p>									  </div>
									  <div class="tab-pane container p-0 fade" id="home2">
										<p>We envision a future where legal services are accessible, organized, and available online for every individual and business. LegalCare strives to become a reliable digital bridge between experienced lawyers and clients seeking professional legal guidance.</p>									  </div>
									  <div class="tab-pane container p-0 fade" id="home3">
                                        <p>LegalCare is built on integrity, professionalism, transparency, and client trust. We believe every client deserves honest legal support, secure communication, and access to experienced lawyers who are committed to protecting their rights.</p>
									  </div>
									</div>
								</div>
		            <div class="years d-flex mt-4 mt-md-5">
		            	<h4>
			            	<span class="number mr-2" data-number="40">0</span>
				            <span>Years of Experienced</span>
			            </h4>
		            </div>
		          </div>
		        </div>
	        </div>
        </div>
    	</div>
    </section>

  <section class="at-section">
  <div class="container">
 
    <!-- Header — uses same .subheading class as rest of template -->
    <div class="at-header">
      <span class="subheading">Our Attorney</span>
      <h2 class="at-title">Meet Our Legal Team</h2>
      <p class="at-subtitle">Experienced attorneys dedicated to justice &amp; results</p>
    </div>
 
    <div class="at-grid">
      <?php if(mysqli_num_rows($lawyer_query_result) > 0) {?>
     <?php while($row = mysqli_fetch_assoc($lawyer_query_result)) { ?>
      <div class="at-card">
    <!-- Photo -->
    <div class="at-photo">
        <img src="uploads/<?php echo $row['lawyer_profile_photo']; ?>"
             alt="<?php echo $row['user_name']; ?>"
             style="width:100%;height:100%;object-fit:cover;">
        <div class="at-badge">
            <span class="at-badge-role">
                <i class="ion-ios-pin" style="font-size:11px;margin-right:3px"></i>
                <?php echo $row['lawyer_city']; ?>
            </span>
        </div>
    </div>

    <!-- Body -->
    <div class="at-body">
        <p class="at-name"><?php echo $row['user_name']; ?></p>
        <div class="at-sep"></div>

        <p class="at-quote">
            <?php echo mb_substr($row['lawyer_bio'], 0, 90); ?>…
        </p>

        <!-- Stats row -->
        <div class="at-stats">
            <div class="at-stat">
                <span class="at-stat-num"><?php echo $row['lawyer_experience_years']; ?>+</span>
                <span class="at-stat-lbl">Yrs exp.</span>
            </div>
            <div class="at-stat">
                <span class="at-stat-num">Rs <?php echo number_format($row['lawyer_consultation_fee']); ?></span>
                <span class="at-stat-lbl">Fee</span>
            </div>
        </div>

        <!-- Practice area tags -->
        <?php
        $services_query = "SELECT pa.practice_area_name FROM lawyer_services ls JOIN practice_areas pa ON ls.practice_area_id = pa.practice_area_id WHERE ls.lawyer_profile_id = " . (int)$row['lawyer_profile_id'] . " LIMIT 2";
        $services = mysqli_query($connection, $services_query);
        ?>
        <div class="lp-profile-tags" style="margin-bottom:16px">
        <?php if(mysqli_num_rows($services) == 0){ ?>
            <span style="font-size:12px;color:#aaa">No services yet</span>
        <?php } else { while($s = mysqli_fetch_assoc($services)) { ?>
            <span class="lp-profile-tag"><?php echo $s['practice_area_name']; ?></span>
        <?php }} ?>
        </div>

        <!-- View Profile — pinned to bottom via mt-auto -->
        <a href="lawyer_profile.php?id=<?php echo (int)$row['lawyer_profile_id']; ?>"
           class="btn btn-primary mt-auto w-100 py-2"
           style="display:flex;align-items:center;justify-content:center;gap:7px;font-weight:600;letter-spacing:.03em;border-radius:10px;">
            View Profile
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="13 6 19 12 13 18"/>
            </svg>
        </a>
    </div>
  </div>
 <?php }?>
 <?php }?>
    </div>
  </div>
</section>
		


    <section class="ftco-section testimony-section">
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-md-7 text-center heading-section ftco-animate">
          	<span class="subheading">Testimonial</span>
            <h2 class="mb-4">Happy Clients</h2>
          </div>
        </div>
        <div class="row ftco-animate">
          <div class="col-md-12">
            <div class="carousel-testimony owl-carousel ftco-owl">
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">LegalCare handled my business contract dispute with incredible precision. Within weeks, they resolved an issue my previous firm had struggled with for over a year. I felt genuinely supported every step of the way.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_1.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">James Hartley</p>
		                    <span class="position">CEO, Hartley Ventures</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">Going through a divorce is never easy, but the family law team at LegalCare made the process as smooth as possible. They were empathetic, thorough, and always prioritised what was best for my children. Truly outstanding people.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_2.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Sarah Mitchell</p>
		                    <span class="position">School Principal</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">I was wrongfully terminated after 12 years of service. LegalCare's employment law specialists not only won my case but secured a settlement that changed my life. Their commitment to justice is real — not just a tagline.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_3.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">David Okafor</p>
		                    <span class="position">Senior Engineer</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">When a property dispute threatened my investment portfolio, LegalCare's real estate team stepped in and resolved it swiftly. Their deep knowledge and calm professionalism gave me confidence during a stressful period. Highly recommended.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_1.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Priya Anand</p>
		                    <span class="position">Property Developer</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap py-4">
                  <div class="text">
                    <p class="mb-4">LegalCare helped me navigate a complex insurance claim that I had nearly given up on. They communicated clearly, moved quickly, and fought hard. In the end, I received full compensation. I cannot thank this team enough.</p>
                    <div class="d-flex align-items-center">
                    	<div class="user-img" style="background-image: url(images/person_2.jpg)"></div>
                    	<div class="pl-3">
		                    <p class="name">Mohamed Al-Rashid</p>
		                    <span class="position">Retail Business Owner</span>
		                  </div>
	                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php
		include("./base/footer.php");
	?>