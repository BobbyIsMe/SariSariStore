<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
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
        <h3 class="mb-4 text-center"><b>Contact Us</b></h3>

        <div class="about-section-wrapper d-flex flex-column flex-lg-row justify-content-center gap-4">

          <div class="creator-box flex-grow-1">

        <div class="creator-profile d-flex align-items-start mb-4">
          <div class="avatar-img">
            <svg width="300px" height="300px" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16.5562 12.9062L16.1007 13.359C16.1007 13.359 15.0181 14.4355 12.0631 11.4972C9.10812 8.55901 10.1907 7.48257 10.1907 7.48257L10.4775 7.19738C11.1841 6.49484 11.2507 5.36691 10.6342 4.54348L9.37326 2.85908C8.61028 1.83992 7.13596 1.70529 6.26145 2.57483L4.69185 4.13552C4.25823 4.56668 3.96765 5.12559 4.00289 5.74561C4.09304 7.33182 4.81071 10.7447 8.81536 14.7266C13.0621 18.9492 17.0468 19.117 18.6763 18.9651C19.1917 18.9171 19.6399 18.6546 20.0011 18.2954L21.4217 16.883C22.3806 15.9295 22.1102 14.2949 20.8833 13.628L18.9728 12.5894C18.1672 12.1515 17.1858 12.2801 16.5562 12.9062Z" fill="#1C274C"/>
            </svg>
          </div>

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

            <div class="creator-profile bembi-profile d-flex align-items-start mb-4">
              <div class="avatar-img bg-light border">
                <img src="../../img/bembi.jpg" alt="Contact Avatar" class="bembi-logo"
                  style="height: 80px; width: 80px; object-fit: cover; border-radius: 6px;">
              </div>
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
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
              dolore magna aliqua.
              Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
              consequat.
              Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
              Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
              laborum.
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