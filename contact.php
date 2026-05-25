<?php
include("./base/header.php");

$hc_result = mysqli_query($connection, "SELECT * FROM homepage_content WHERE section='contact'");
$hc_row = mysqli_fetch_assoc($hc_result);

// save contact message (POST)
if (isset($_POST["contact_submit"])) {

    $contact_name = $_POST["contact_name"];
    $contact_email = $_POST["contact_email"];
    $contact_subject = $_POST["contact_subject"];
    $contact_message = $_POST["contact_message"];

    if ($contact_name === "" || $contact_email === "" || $contact_subject === "" || $contact_message === "") {
        $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Contact Not Saved!', 'text' => 'Please fill in all fields.'];
        header("Location: contact.php");
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $contact_name) || strlen($contact_name) < 3 || strlen($contact_name) > 50) {
        $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Contact Not Saved!', 'text' => 'Name must be between 3 and 50 characters and contain only letters and spaces.'];
        header("Location: contact.php");
        exit();
    }
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Contact Not Saved!', 'text' => 'Please enter a valid email address.'];
        header("Location: contact.php");
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $contact_subject) || strlen($contact_subject) < 3 || strlen($contact_subject) > 50) {
        $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Contact Not Saved!', 'text' => 'Subject must be between 3 and 50 characters and contain only letters and spaces.'];
        header("Location: contact.php");
        exit();
    }
    if (!preg_match("/^[a-zA-Z\s]+$/", $contact_message) || strlen($contact_message) < 10 || strlen($contact_message) > 500) {
        $_SESSION['_swal'] = ['icon' => 'warning', 'title' => 'Contact Not Saved!', 'text' => 'Message must be between 10 and 500 characters and contain only letters and spaces.'];
        header("Location: contact.php");
        exit();
    }

    $check_email_query = "SELECT * FROM contact WHERE contact_email = '$contact_email'";
    $check_email_result = mysqli_query($connection, $check_email_query);

    if ($check_email_result && mysqli_num_rows($check_email_result) > 0) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Contact Not Saved!', 'text' => 'This email has already sent a message.'];
        header("Location: contact.php");
        exit();
    }

    $contact_query = "INSERT INTO contact (contact_name, contact_email, contact_subject, contact_message) VALUES ('$contact_name', '$contact_email', '$contact_subject', '$contact_message')";
    if (!mysqli_query($connection, $contact_query)) {
        $_SESSION['_swal'] = ['icon' => 'error', 'title' => 'Could Not Save', 'text' => 'Something went wrong. Please try again.'];
        header("Location: contact.php");
        exit();
    }

    $_SESSION['_swal'] = ['icon' => 'success', 'title' => 'Contact Saved!', 'text' => 'Your message has been saved.'];
    header("Location: contact.php");
    exit();
}
?>
    
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
            <h1 class="mb-3 bread"><?php echo $hc_row['title']; ?></h1>
            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span> <span><?php echo $hc_row['subtitle']; ?> <i class="ion-ios-arrow-forward"></i></span></p>
          </div>
        </div>
      </div>
    </section>
   	
   	<!-- Material Dashboard–style contact section -->
    <style>
      /* ── Google Inter font (matches dashboard) ── */
      @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

      .contact-section-dash {
        padding: 80px 0;
        background: #f0f2f5;
        font-family: 'Inter', sans-serif;
      }

      /* Section heading */
      .contact-section-dash .section-title h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #344767;
        margin-bottom: 6px;
      }
      .contact-section-dash .section-title p {
        color: #7b809a;
        font-size: .95rem;
        margin-bottom: 0;
      }

      /* ── Card shell ── */
      .dash-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        overflow: hidden;
      }

      /* Gradient header bar */
      .dash-card-header {
        background: linear-gradient(195deg, #005fa3, #007acc);
        padding: 24px 28px 18px;
        border-radius: 12px 12px 0 0;
      }
      .dash-card-header h4 {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 4px;
      }
      .dash-card-header p {
        color: rgba(255,255,255,.72);
        font-size: .82rem;
        margin: 0;
      }

      .dash-card-body {
        padding: 28px 28px 24px;
      }

      /* ── Floating-label inputs (Material Dashboard outline style) ── */
      .input-group-outline {
        position: relative;
        margin-bottom: 20px;
      }
      .input-group-outline label {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        font-size: .875rem;
        color: #7b809a;
        pointer-events: none;
        transition: all .2s ease;
        background: transparent;
        padding: 0 4px;
        line-height: 1;
      }
      .input-group-outline.textarea-group label {
        top: 14px;
        transform: none;
      }
      .input-group-outline.is-filled label,
      .input-group-outline.is-focused label {
        top: 0;
        font-size: .75rem;
        color: #007acc;
        background: #fff;
      }
      .input-group-outline .form-control {
        border: 1.5px solid #d2d6da;
        border-radius: 8px;
        height: 44px;
        padding: 0 14px;
        font-size: .875rem;
        color: #344767;
        background: transparent;
        transition: border-color .2s;
        font-family: 'Inter', sans-serif;
        width: 100%;
        box-shadow: none;
        outline: none;
      }
      .input-group-outline textarea.form-control {
        height: auto;
        padding: 12px 14px;
        resize: none;
      }
      .input-group-outline .form-control:focus {
        border-color: #007acc;
        box-shadow: none;
      }

      /* ── Gradient send button ── */
      .btn-dash-send {
        display: inline-block;
        width: 100%;
        padding: 12px 0;
        background: linear-gradient(195deg, #1a2238, #007acc);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .9rem;
        font-weight: 600;
        letter-spacing: .5px;
        cursor: pointer;
        transition: box-shadow .25s, transform .15s;
        font-family: 'Inter', sans-serif;
        margin-top: 4px;
      }
      .btn-dash-send:hover {
        box-shadow: 0 6px 20px rgba(83, 83, 83, 0.38);
        transform: translateY(-1px);
        color: #fff;
      }
      .btn-dash-send:active { transform: translateY(0); }

      /* ── Map card ── */
      .dash-map-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08);
        overflow: hidden;
        height: 100%;
        min-height: 420px;
      }
      .dash-map-card iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
        min-height: 420px;
      }

      /* ── Contact info cards ── */
      .contact-info-row {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 36px;
      }
      .contact-info-card {
        flex: 1 1 200px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        padding: 20px 22px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        transition: box-shadow .2s, transform .2s;
      }
      .contact-info-card:hover {
        transform: translateY(-3px);
      }
      .contact-info-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(195deg, #005fa3, #007acc);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
        color: #fff;
      }
      .contact-info-text strong {
        display: block;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: #7b809a;
        margin-bottom: 3px;
        font-weight: 600;
      }
      .contact-info-text span,
      .contact-info-text a {
        font-size: .9rem;
        color: #344767;
        font-weight: 500;
        text-decoration: none;
      }
      .contact-info-text a:hover { color: #007acc; }
    </style>

    <section class="contact-section-dash">
      <div class="container">

        <!-- Heading -->
        <div class="row justify-content-center mb-5">
          <div class="col-md-8 text-center section-title">
            <h2>Get in Touch</h2>
            <p>Use the form, call us, or stop by our office — our trusted attorneys are ready to help with your case.</p>
          </div>
        </div>

        <!-- Form + Map -->
        <div class="row g-4 align-items-stretch">
          <!-- Map Card -->
          <div class="col-lg-6 d-flex flex-column">
            <div class="dash-map-card flex-grow-1">
              <iframe
                title="Legalcare location map"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
                aria-hidden="false"
                tabindex="0"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.91467953653!2d-74.00594138459452!3d40.71277597933144!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259af18af1549%3A0x3d5d0b639ddc5f5b!2s198%20West%2021st%20St%2C%20New%20York%2C%20NY%2010016!5e0!3m2!1sen!2sus!4v1700000000000">
              </iframe>
            </div>
          </div>
          <!-- Contact Form Card -->
          <div class="col-lg-6 d-flex flex-column">
            <div class="dash-card flex-grow-1">
              <div class="dash-card-header">
                <h4>Send Us a Message</h4>
                <p>We typically respond within 1 business day.</p>
              </div>
              <div class="dash-card-body">
                <form id="contactForm" method="POST" novalidate>

                  <div class="input-group-outline" id="grp-name">
                    <label for="contact-name">Your Name</label>
                    <input type="text" id="contact-name" name="contact_name" class="form-control" autocomplete="name" maxlength="50" minlength="3">
                  </div>

                  <div class="input-group-outline" id="grp-email">
                    <label for="contact-email">Your Email</label>
                    <input type="email" id="contact-email" name="contact_email" class="form-control" autocomplete="email">
                  </div>

                  <div class="input-group-outline" id="grp-subject">
                    <label for="contact-subject">Subject</label>
                    <input type="text" id="contact-subject" name="contact_subject" class="form-control" maxlength="50" minlength="3">
                  </div>

                  <div class="input-group-outline textarea-group" id="grp-message">
                    <label for="contact-message">Message</label>
                    <textarea id="contact-message" name="contact_message" rows="5" class="form-control" maxlength="500" minlength="10"></textarea>
                  </div>

                  <button type="submit" name="contact_submit" class="btn-dash-send">Send Message</button>

                </form>
              </div>
            </div>
          </div>

          

        </div><!-- /row -->

        <!-- Contact Info Cards -->
        <div class="contact-info-row">

          <div class="contact-info-card">
            <div class="contact-info-icon">&#128205;</div>
            <div class="contact-info-text">
              <strong>Address</strong>
              <span>Karachi, Pakistan</span>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">&#128222;</div>
            <div class="contact-info-text">
              <strong>Phone</strong>
              <a href="tel:+11234567890">+92 300 1234567</a>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">&#9993;</div>
            <div class="contact-info-text">
              <strong>Email</strong>
              <a href="mailto:info@yoursite.com">Legalcare@gmail.com</a>
            </div>
          </div>

          <div class="contact-info-card">
            <div class="contact-info-icon">&#127758;</div>
            <div class="contact-info-text">
              <strong>Website</strong>
              <a href="#">LegalCare.com</a>
            </div>
          </div>

        </div><!-- /contact-info-row -->

      </div><!-- /container -->
    </section>

    <!-- Floating-label focus + SweetAlert2 validation -->
    <script>
      (function () {
        // Floating label behaviour
        var groups = document.querySelectorAll('.input-group-outline');
        groups.forEach(function (grp) {
          var field = grp.querySelector('.form-control');
          if (!field) return;
          function refresh() { grp.classList.toggle('is-filled', field.value.trim() !== ''); }
          field.addEventListener('focus', function () { grp.classList.add('is-focused'); });
          field.addEventListener('blur',  function () { grp.classList.remove('is-focused'); refresh(); });
          field.addEventListener('input', refresh);
          refresh();
        });

        // SweetAlert2 — same rules as PHP (trimmed lengths)
        var form = document.getElementById('contactForm');
        if (form) {
          form.addEventListener('submit', function (e) {
            var name    = document.getElementById('contact-name').value.trim();
            var email   = document.getElementById('contact-email').value.trim();
            var subject = document.getElementById('contact-subject').value.trim();
            var message = document.getElementById('contact-message').value.trim();
            var emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!name || !email || !subject || !message) {
              e.preventDefault();
              Swal.fire({ icon: 'warning', title: 'Missing Fields', text: 'Please fill in all fields before sending.' });
              return;
            }
            if (name.length < 3 || name.length > 50) {
              e.preventDefault();
              Swal.fire({ icon: 'warning', title: 'Invalid Name', text: 'Name must be between 3 and 50 characters.' });
              return;
            }
            if (!emailRx.test(email)) {
              e.preventDefault();
              Swal.fire({ icon: 'error', title: 'Invalid Email', text: 'Please enter a valid email address.' });
              return;
            }
            if (subject.length < 3 || subject.length > 50) {
              e.preventDefault();
              Swal.fire({ icon: 'warning', title: 'Invalid Subject', text: 'Subject must be between 3 and 50 characters.' });
              return;
            }
            if (message.length < 10 || message.length > 500) {
              e.preventDefault();
              Swal.fire({ icon: 'warning', title: 'Invalid Message', text: 'Message must be between 10 and 500 characters.' });
              return;
            }
            // valid: allow POST to PHP
          });
        }
      })();
    </script>

    <?php include("./base/footer.php"); ?>