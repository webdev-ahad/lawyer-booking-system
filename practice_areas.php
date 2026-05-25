<?php
    include("./base/header.php");

    // fetch all practice areas from DB
    $areas_query = "SELECT * FROM practice_areas ORDER BY practice_area_name ASC";
    $areas_result = mysqli_query($connection, $areas_query);
    $total_areas  = mysqli_num_rows($areas_result);

    // map practice area names to icons
    $icon_map = [
        'family'      => 'flaticon-family',
        'business'    => 'flaticon-auction',
        'insurance'   => 'flaticon-shield',
        'criminal'    => 'flaticon-handcuffs',
        'property'    => 'flaticon-house',
        'employment'  => 'flaticon-employee',
        'fire'        => 'flaticon-fire',
        'financial'   => 'flaticon-money',
        'drug'        => 'flaticon-medicine',
        'sexual'      => 'flaticon-handcuffs',
        'civil'       => 'flaticon-auction',
        'corporate'   => 'flaticon-auction',
        'immigration' => 'flaticon-employee',
        'tax'         => 'flaticon-money',
        'real estate' => 'flaticon-house',
        'labor'       => 'flaticon-employee',
        'medical'     => 'flaticon-medicine',
        'traffic'     => 'flaticon-shield',
        'divorce'     => 'flaticon-family',
        'bankruptcy'  => 'flaticon-money',
    ];

    // get icon for practice area by name
    function getIcon($name, $icon_map) {
        $lower = strtolower($name);
        foreach ($icon_map as $key => $icon) {
            if (strpos($lower, $key) !== false) {
                return $icon;
            }
        }
        return 'flaticon-auction';
    }
?>

    <!-- Hero Banner -->
    <section class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-center">
          <div class="col-md-9 ftco-animate pb-5 text-center">
            <h1 class="mb-3 bread">Practice Areas</h1>
            <p class="breadcrumbs">
              <span class="mr-2"><a href="index.php">Home <i class="ion-ios-arrow-forward"></i></a></span>
              <span>Practice Areas <i class="ion-ios-arrow-forward"></i></span>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Practice Areas Grid -->
    <section class="ftco-section">
      <div class="container practice-container">

        <?php if ($total_areas > 0) { ?>
        <div class="row d-flex justify-content-center">

          <?php while ($area = mysqli_fetch_assoc($areas_result)) {

              $icon = getIcon($area['practice_area_name'], $icon_map);
          ?>
          <div class="col-md-3 text-center">
            <div class="practice-area ftco-animate h-100">
              <div class="icon d-flex justify-content-center align-items-center">
                <span class="<?php echo $icon; ?>"></span>
              </div>
              <h3><a href="attorneys.php?service=<?php echo $area['practice_area_id']; ?>"><?php echo $area['practice_area_name']; ?></a></h3>
              <p><?php echo $area['practice_area_description']; ?></p>
              <a href="attorneys.php?service=<?php echo $area['practice_area_id']; ?>"
                 class="btn-custom d-flex align-items-center justify-content-center"
                 title="Find <?php echo $area['practice_area_name']; ?> lawyers">
                <span class="ion-ios-arrow-round-forward"></span>
              </a>
            </div>
          </div>
          <?php } ?>

        </div>
        <?php } else { ?>
        <div class="row justify-content-center">
          <div class="col-md-6 text-center py-5">
            <i class="flaticon-auction" style="font-size:60px;color:#d2d6da;display:block;margin-bottom:1rem;"></i>
            <h4 class="text-secondary">No Practice Areas Found</h4>
            <p class="text-secondary">Our legal team is currently setting up service categories. Please check back shortly.</p>
            <a href="index.php" class="btn btn-primary mt-2">Back to Home</a>
          </div>
        </div>
        <?php } ?>

      </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="ftco-section ftco-no-pt ftco-no-pb">
      <div class="container">
        <div class="row d-flex justify-content-end">
          <div class="col-md-8 py-4 px-md-4 bg-primary">
            <div class="row">
              <div class="col-md-6 ftco-animate d-flex align-items-center">
                <h2 class="mb-0" style="color:white; font-size: 24px;">Subscribe to our Newsletter</h2>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                <form action="attorneys.php" class="subscribe-form">
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

<?php include("./base/footer.php"); ?>