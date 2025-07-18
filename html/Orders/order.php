<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <link rel="stylesheet" href="../../css/cart.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script>
    <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
    <script type="text/javascript" src="../../js/order_controller.js" defer></script>
    <script type="text/javascript" src="../../js/notifications_controller.js" defer></script> -->
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;

        }

        .subcategory {
            margin-left: 0 !important;
            text-align: left;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column min-vh-100">

    <?php include '../Navbars/navbar.php'; ?>


        <main class="flex-fill" style="height: 100%;">
            <div class="d-flex align-items-stretch justify-content-center" style="min-height: 100vh;">
                <div class="col-2 p-2" style="background-color: white; flex-shrink: 0;">
                    <aside class="item_nav flex-column align-items-start text-start">
                        <h6>Categories</h6>
                        <hr>
                        <div id="sidebar">
                            <details class="category">
                                <summary>${category}</summary>
                                <ul class="subcategory">
                                    <a href="../Webpages/category.php?category=${category}&subcategory=${item.subcategory}">• ${item.subcategory}</a>
                                </ul>
                            </details>
                        </div>
                    </aside>
                </div>

                <main style="flex-grow: 1;">
                    <div class="object_container">
                        <div class="cart-container"
                            style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5;">

                            <!-- cart items-->
                            <div class="cart_items">
                                <h4><b id="order_number">Order</b></h4>
                                <div id="order_items">
                                    <div class="cart_item">

                                        <div class="item_image">
                                            <img src="../../img/bembi.jpg" alt="img" style="width: 100%; height: 100%;">
                                        </div>

                                        <!-- details-->
                                        <div style="flex: 1; ">
                                            <div class="d-flex flex-row g-2"
                                                style="gap: 20px; font-size: 12px; color: gray;">
                                                <div class="category">${item.category}</div>
                                                <div>|</div>
                                                <div class="category">${item.subcategory}</div>
                                            </div>

                                            <div class="name" style="font-weight: bold;">${item.brand} | ${item.item_name}</div>
                                            <div style="font-size: 15px;">
                                                ${item.variation_name}
                                            </div>
                                        </div>

                                        <div class="d-flex flex-row align-items-center" style="gap:20px">
                                            <div style="width: auto; text-align: right;">₱${item.subtotal}</div>
                                            <div class="d-flex flex-column align-items-center text-center"
                                                style="gap: 10px; ">
                                                <div readonly
                                                    style="height: 15px; width: 100px; font-size: 12PX; color: red; ">
                                                </div>
                                                <div>
                                                    <div>
                                                        ${item.item_qty} pcs
                                                    </div>
                                                </div>
                                                <div style="font-size: 10px; color: gray;">${item.stock_qty} in Stock</div>
                                            </div>
                                            <div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--order summary-->
                            <div class="order_summary">
                                <h3>Order Details</h3>
                                <!-- item -->
                                <div id="order_details">
                                    <div class="summary_item">
                                        <div><strong>${item.item_qty}x</strong> ${item.brand} | ${item.item_name} (${item.variation_name})</div>
                                        <div>₱${item.subtotal}</div>
                                    </div>
                                </div>
                                <br>
                                <div
                                    style="margin-top: 20px; font-weight: bold; display: flex; justify-content: space-between;">
                                    <div>Estimated Total</div>
                                    <div id="estimated_total">N/A</div>
                                </div>
                                <br>
                                <button
                                    class="add_to_cart" id="cancel">
                                    Cancel
                                </button>
                            </div>
                        </div>

                    </div>



                </main>
            </div>
        </main>

        <?php include '../Navbars/footer.php'; ?>
    </div>
    <!-- <script type="text/javascript" src="../../js/session.js"></script> -->
</body>

</html>