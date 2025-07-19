<?php
// session_start();
// if (isset($_SESSION["user_id"])) {
//     header("Location: ../../html/Webpages/homepage.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script> -->
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .header {
            padding: 12.5px 22.5px
        }
        
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">

        <div class="header w-100">
            <div class="container-fluid d-flex flex-row align-items-center justify-content-start py-2 px-3" 
                style="gap: 34.5px; margin-left: -2.5px">
                
                <div class="d-flex align-items-center">
                <img src="../../icons/logo-icon.png" alt="Cerina's Sari2Store Logo" style="height: 40px; width: auto;">
                </div>

                <div class="d-flex flex-grow-1 ms-3" style="max-width: 700px;">
                <div class="d-flex flex-row flex-grow-1">
                    <input type="text" class="form-control" placeholder="Search" style="padding-top: 5px">
                    <button class="btn btn-light search-button" type="button" aria-label="Search">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="-81.92 -81.92 1187.84 1187.84">
                        <path d="m795.904 750.72 124.992 124.928a32 32 0 0 1-45.248 
                                45.248L750.656 795.904a416 416 0 1 1 45.248-45.248z
                                M480 832a352 352 0 1 0 0-704 352 352 0 0 0 0 704z"/>
                    </svg>
                    </button>
                </div>
                </div>

            </div>
        </div>


        <main class="flex-fill">
            <div class="d-flex align-items-center justify-content-center p-4" style="min-height: 70vh; margin-top: 12px;">
                <div class="login-card text-center">
                    <form id="signupForm" method="POST" onsubmit="signupSubmit(event)">
                        <h4 class="mb-3"><b>REGISTRATION</b></h4>

                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="" name="phone_number">
                            <label>Mobile Number</label>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="" name="first_name">
                            <label>First Name</label>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="" name="last_name">
                            <label>Last Name</label>
                        </div>

                        <div class="mb-3">
                            <input type="password" class="form-control" placeholder="" name="password">
                            <label>Password</label>
                        </div>

                        <div class="mb-3">
                            <input type="password" class="form-control" placeholder="" name="confirm_password">
                            <label>Re-enter Password</label>
                        </div>
                        <button class="add_to_cart btn login-btn" type="submit" on><b>REGISTER</b></button>
                    </form>
                    <p class="mt-2 mb-0">Already have an account? Login <a href="login.php">Here</a></p>
                </div>
            </div>
        </main>

        <?php include '../Navbars/footer.php'; ?>
    </div>
</body>

</html>