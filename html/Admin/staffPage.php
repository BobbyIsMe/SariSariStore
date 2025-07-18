<?php
// session_start();
// if (!isset($_SESSION["user_id"]) && !isset($_SESSION["staff_type"]) || $_SESSION["staff_type"] != "staff") {
//     header("Location: ../Signin/Login.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <link rel="stylesheet" href="../../css/cart.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script>
    <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
    <script type="text/javascript" src="../../js/notifications_controller.js" defer></script>
    <script type="text/javascript" src="../../js/manage_orders.js" defer></script> -->
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .seeMoreBtn {
            border: none;
            padding: 10px;
            border-radius: 10px;
            letter-spacing: 4px;
        }

        .seeMoreBtn:hover {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">

    <?php include '../Navbars/navbar.php'; ?>

        <main class="flex-fill">
            <div class="d-flex align-items-stretch justify-content-center" style="height: 100%; min-height: 100vh;">
                <div class="col-2 p-2" style="background-color: white; flex-shrink: 0;">
                    <aside class="item_nav flex-column align-items-start text-start">
                        <h6>Categories</h6>
                        <hr>
                        <div id="sidebar"></div>
                    </aside>
                </div>

                <main style="flex-grow: 1;">
                    <div class="object_container">
                        <h3>Reservation Details</h3>
                        <div class="d-flex align-items-center">
                            <button id="recent" class="btn adminBtn active" type="button">Recent</button>
                            <div class="dropdown">
                                <select class="form-select border-0" id="status_dropdown" name="rent_status" required>
                                    <option value="status">Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="pending">Approved</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>

                        <div id="orders_list">
                            <div class="cart-container"
                                style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5;">

                                <!-- cart items-->
                                <div class="cart_items">
                                    <div>
                                        <h6>Cart ID # ${order.cart_id}</h6>
                                    </div>

                                    <br>

                                    <div>
                                        Name: ${order.name}
                                    </div>

                                    <br>

                                    <div class="d-flex flex-row col-12">
                                        <div class="col-4">
                                            Reserved: ${order.date_time_created}
                                        </div>
                                        <div class="col-4">
                                            Received: ${order.date_time_received}
                                        </div>
                                        <div class="col-4">
                                            Deadline: ${order.date_time_deadline}
                                        </div>
                                    </div>
                                    <!-- item-->
                                    <div class="cart_item">

                                        <!-- details-->
                                        <div style="flex: 1;">
                                            <div class="category" style="font-size: 12px; color: gray;">${item.category} | ${item.subcategory}</div>
                                            <div class="name" style="font-weight: bold;">${item.brand} | ${item.item_name}</div>
                                        </div>

                                        <!-- price -->
                                        <div style="width: 80px; text-align: right;">₱${item.subtotal}</div>

                                        <!-- qty of items-->
                                        <div>
                                            ${item.item_qty} pcs
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center align-items-center mt-3">
                                        <button type="button" class="btn seeMoreBtn" data-bs-toggle="modal"
                                            data-bs-target="#seeMoreModal">
                                            See More
                                        </button>
                                    </div>


                                </div>

                                <!--order summary-->
                                <div class="order_summary">
                                    <div class="dropdown">
                                        <select class="form-select border-0" id="status_${order.cart_id}" name="rent_status"
                                            required>
                                            <option value="status">Status</option>
                                        </select>
                                    </div>

                                    <br><br><br>

                                    <div class="d-flex flex-column align-items-center ">

                                        <div class="" style="border-top: solid black 1px;">
                                            <label for="check_in_date" class="form-label">Check-in Date</label>
                                            <input type="datetime-local" class="paragraphs form-control" id="date_${order.cart_id}"
                                                name="check_in_date" value="${date_format}" required>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="seeMoreModal" tabindex="-1" aria-labelledby="seeMoreModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="seeMoreModalLabel">Order Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body" id="order_items_list">
                                         <div
                                            class="order-item d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <div class="category" style="font-size: 12px; color: gray;">
                                                    ${item.category} | ${item.subcategory}</div>
                                                <div class="name fw-bold">${item.brand} | ${item.item_name}</div>
                                            </div>
                                            <div class="text-end">
                                                <div>₱${item.subtotal}</div>
                                                <div>${item.item_qty} pcs</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 d-flex justify-content-center align-items-center gap-4">
                            <button class="navButton" type="button" id="prev_button">Previous</button>
                            <span>|</span>
                            <div id="page_number" class="paragraphs">Page # out of #</div>
                            <span>|</span>
                            <button class="navButton" type="button" id="next_button">Next</button>
                        </div>
                    </div>
                </main>
            </div>
        </main>


    </div>


    <?php include '../Navbars/footer.php'; ?>
    <script src="../../js/script.js"></script>
</body>

</html>