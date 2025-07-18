<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
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
    <h3 class="mb-4 text-center"><b>Contact Us</b></h3>

    <div class="about-section-wrapper d-flex flex-column flex-lg-row justify-content-center gap-4">

      <div class="creator-box flex-grow-1">

        <div class="creator-profile d-flex align-items-start mb-4">
          <div class="avatar-img bg-light border"></div>
          <div class="creator-details">
            <h5>+63900-456-7853</h5>
          </div>
        </div>

        <div class="creator-profile d-flex align-items-start mb-4">
          <div class="avatar-img bg-light border"></div>
          <div class="creator-details">
            <h5>@jemuzucameron</h5>
          </div>
        </div>

        <div class="creator-profile d-flex align-items-start mb-4">
          <div class="avatar-img bg-light border"></div>
          <div class="creator-details">
            <h5>James Cameron Abello</h5>
          </div>
        </div>

        <div class="creator-profile d-flex align-items-start mb-4">
          <div class="avatar-img bg-light border"></div>
          <div class="creator-details">
            <h5>jemuzucameron@gmail.com</h5>
          </div>
        </div>
      </div>

      <div class="faq-box flex-grow-1">
        <h4 class="mb-3"><b>Our Location</b></h4>

        <div class="image bg-light border mb-3" style="width: 100%; height: 150px;"></div>

        <p><b>Eskinal Gobols St.</b></p>
        <p style="text-align: justify;">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
          Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
          Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </p>
      </div>
    </div>
  </section>
</main>

<?php include '../Navbars/footer.php'; ?>
  </div>

  <script src="../../js/script.js"></script>
</body>

</html>
