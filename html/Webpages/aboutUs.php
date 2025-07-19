<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>About Us</title>
  <link rel="icon" type="image/x-icon" href="../../icons/tab-icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/navbarFooter.css">
  <link rel="stylesheet" href="../../css/webpageBody.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    * {
      font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
  </style>
</head>

<body>
  <div class="d-flex flex-column min-vh-100">

    <?php include '../Navbars/navbar.php'; ?>

    <main class="flex-fill">
      <section class="body_container p-5">
        <h3 class="mb-4 text-center"><b>About Us</b></h3>
        <div class="about-section-wrapper d-flex flex-column flex-lg-row justify-content-center gap-4">
          <div class="creator-box flex-grow-1">
            <h4 class="mb-3"><b>Meet the Creators</b></h4>

            <div class="creator-profile d-flex align-items-start mb-4">
              <div class="avatar-img bg-light border">
                <img src="../../img/bembi.jpg" alt="Contact Avatar" class="bembi-logo"
                  style="height: 80px; width: 80px; object-fit: cover; border-radius: 6px;">
              </div>
              <div class="creator-details">
                <h5>James Cameron Abello</h5>
                <p>Lead Developer. Focused on usability, dynamic features, and frontend polish.</p>
              </div>
            </div>

            <div class="creator-profile d-flex align-items-start mb-4">
              <div class="avatar-img bg-light border"></div>
              <div class="creator-details">
                <h5>Cedrick Digory</h5>
                <p>UI Designer. Responsible for layout structure, colors, and responsive feel.</p>
              </div>
            </div>

            <div class="creator-profile d-flex align-items-start mb-4">
              <div class="avatar-img bg-light border"></div>
              <div class="creator-details">
                <h5>Roy THE Man</h5>
                <p>Backend Engineer. Handles reservation logic and database efficiency.</p>
              </div>
            </div>

            <div class="creator-profile d-flex align-items-start mb-4">
              <div class="avatar-img bg-light border"></div>
              <div class="creator-details">
                <h5>Mizzi You 2</h5>
                <p>Project Manager. Oversees development flow, timelines, and testing.</p>
              </div>
            </div>
          </div>

      <div class="faq-box flex-grow-1">
        <h5 class="mb-3"><b>FAQ's</b></h5>
        <ol>
          <li>How do I reserve items?
            <ul><li>Simply select your products and click “Reserve” in your cart.</li></ul>
          </li>
          <li>Can I cancel a reservation?
            <ul><li>Yes, as long as the status is still pending for approval.</li></ul>
          </li>
          <li>How often are items restocked?
            <ul><li>We restock key products weekly; exact dates may vary per supplier.</li></ul>
          </li>
          <li>Who should I contact for help?
            <ul><li>See our <a href="contactUs.html">Contact Us</a> page for full details.</li></ul>
          </li>
        </ol>
      </div>
    </div>
  </section>
</main>

    <?php include '../Navbars/footer.php'; ?>
  </div>

  <script src="../../js/script.js"></script>
</body>

</html>