<?php
		include("./base/header.php");

$about_query = "SELECT * FROM homepage_content WHERE section='about'";
$about_result = mysqli_query($connection, $about_query);
$about_row = mysqli_fetch_assoc($about_result);

$mission_query = "SELECT * FROM homepage_content WHERE section='mission'";
$mission_result = mysqli_query($connection, $mission_query);
$mission_row = mysqli_fetch_assoc($mission_result);

$vision_query = "SELECT * FROM homepage_content WHERE section='vision'";
$vision_result = mysqli_query($connection, $vision_query);
$vision_row = mysqli_fetch_assoc($vision_result);

$value_query = "SELECT * FROM homepage_content WHERE section='value'";
$value_result = mysqli_query($connection, $value_query);
$value_row = mysqli_fetch_assoc($value_result);
?>
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
            <h1 class="mb-3 bread"><?php echo $about_row['title']; ?></h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span><?php echo $about_row['subtitle']; ?> <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
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

    <section class="ftco-section ftco-no-pt ftco-no-pb">
      <div class="container">
        <div class="row d-flex justify-content-end">
        	<div class="col-md-8 py-4 px-md-4 bg-primary">
        		<div class="row">
		          <div class="col-md-6 ftco-animate d-flex align-items-center">
		            <h2 class="mb-0" style="color:white; font-size: 24px;">Subcribe to our Newsletter</h2>
		          </div>
		          <div class="col-md-6 d-flex align-items-center">
		            <form action="contact.php" class="subscribe-form">
		              <div class="form-group d-flex">
		                <input type="text" class="form-control" placeholder="Enter email address">
		                <input type="submit" value="Subscribe" class="submit px-3">
		              </div>
		            </form>
		          </div>
	          </div>
          </div>
        </div>
      </div>
    </section>

  <?php
		include("./base/footer.php");
	?>