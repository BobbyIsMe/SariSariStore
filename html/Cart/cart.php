<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <link rel="stylesheet" href="../../css/cart.css">
    <link rel="stylesheet" href="../../css/loadingscreen.css">
    <link rel="stylesheet" href="../../css/failedtoload.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script>
    <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
    <script type="text/javascript" src="../../js/cart_controller.js" defer></script>
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
                        <div class="cart-container" style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5;">

                            <div class="cart_items">
                                <h4><b id="cart_number">Cart</b></h4>

                                <!-- item-->
                                <div id="cart_items">
                                    <div class="cart_item">

                                        <div class="item_image">
                                            <img src="../../img/${item.image}=" alt="img" style="width: 100px; height: 100px;">
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
                                                    style="height: 12px; width: 200px; font-size: 12PX; color: red; ">
                                                    "QUANTITY EXCEEDS STOCK"
                                                </div>
                                                <div>


                                                    <div class="quantity_control  ">
                                                        <button>-</button>
                                                        <input type="text" value="1" readonly />
                                                        <button>+</button>
                                                    </div>

                                                </div>

                                                <div style="font-size: 10px; color: gray;">${item.stock_qty} in Stock</div>
                                            </div>
                                            <div>
                                                <button style="background: none; border: none; cursor: pointer;"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 2.75C11.0215 2.75 10.1871 3.37503 9.87787 4.24993C9.73983 4.64047 9.31134 4.84517 8.9208 4.70713C8.53026 4.56909 8.32557 4.1406 8.46361 3.75007C8.97804 2.29459 10.3661 1.25 12 1.25C13.634 1.25 15.022 2.29459 15.5365 3.75007C15.6745 4.1406 15.4698 4.56909 15.0793 4.70713C14.6887 4.84517 14.2602 4.64047 14.1222 4.24993C13.813 3.37503 12.9785 2.75 12 2.75Z" fill="#1C274C"></path>
                                                        <path d="M2.75 6C2.75 5.58579 3.08579 5.25 3.5 5.25H20.5001C20.9143 5.25 21.2501 5.58579 21.2501 6C21.2501 6.41421 20.9143 6.75 20.5001 6.75H3.5C3.08579 6.75 2.75 6.41421 2.75 6Z" fill="#1C274C"></path>
                                                        <path d="M5.91508 8.45011C5.88753 8.03681 5.53015 7.72411 5.11686 7.75166C4.70356 7.77921 4.39085 8.13659 4.41841 8.54989L4.88186 15.5016C4.96735 16.7844 5.03641 17.8205 5.19838 18.6336C5.36678 19.4789 5.6532 20.185 6.2448 20.7384C6.83639 21.2919 7.55994 21.5307 8.41459 21.6425C9.23663 21.75 10.2751 21.75 11.5607 21.75H12.4395C13.7251 21.75 14.7635 21.75 15.5856 21.6425C16.4402 21.5307 17.1638 21.2919 17.7554 20.7384C18.347 20.185 18.6334 19.4789 18.8018 18.6336C18.9637 17.8205 19.0328 16.7844 19.1183 15.5016L19.5818 8.54989C19.6093 8.13659 19.2966 7.77921 18.8833 7.75166C18.47 7.72411 18.1126 8.03681 18.0851 8.45011L17.6251 15.3492C17.5353 16.6971 17.4712 17.6349 17.3307 18.3405C17.1943 19.025 17.004 19.3873 16.7306 19.6431C16.4572 19.8988 16.083 20.0647 15.391 20.1552C14.6776 20.2485 13.7376 20.25 12.3868 20.25H11.6134C10.2626 20.25 9.32255 20.2485 8.60915 20.1552C7.91715 20.0647 7.54299 19.8988 7.26957 19.6431C6.99616 19.3873 6.80583 19.025 6.66948 18.3405C6.52891 17.6349 6.46488 16.6971 6.37503 15.3492L5.91508 8.45011Z" fill="#1C274C"></path>
                                                        <path d="M9.42546 10.2537C9.83762 10.2125 10.2051 10.5132 10.2464 10.9254L10.7464 15.9254C10.7876 16.3375 10.4869 16.7051 10.0747 16.7463C9.66256 16.7875 9.29502 16.4868 9.25381 16.0746L8.75381 11.0746C8.71259 10.6625 9.0133 10.2949 9.42546 10.2537Z" fill="#1C274C"></path>
                                                        <path d="M15.2464 11.0746C15.2876 10.6625 14.9869 10.2949 14.5747 10.2537C14.1626 10.2125 13.795 10.5132 13.7538 10.9254L13.2538 15.9254C13.2126 16.3375 13.5133 16.7051 13.9255 16.7463C14.3376 16.7875 14.7051 16.4868 14.7464 16.0746L15.2464 11.0746Z" fill="#1C274C"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="order_summary">
                                <h3>Order Details</h3>
                                <!-- item -->
                                <div id="cart_details">
                                    <div class="summary_item">
                                        <div><strong>${item.item_qty}x</strong> ${item.brand} | ${item.item_name} (${item.variation_name})</div>
                                        <div>₱${item.subtotal}</div>
                                    </div>
                                </div>
                                
                                <br>
                                <div class="d-flex flex-row justify-content-between">
                                <div><b>Cart Status</b></div>
                                    <div id="status"><b>hi</b></div>
                                </div>

                                <div class="d-flex flex-row justify-content-between">
                                <div><b>Deadline date</b></div>
                                    <div id="status"><b>9/26/25 12:19 PM</b></div>
                                </div>
                                
                                <div class="d-flex flex-row justify-content-between"
                                    style="margin-top: 20px; font-weight: bold;">
                                    <div>Estimated Total</div>
                                    <div id="estimated_total">N/A</div>
                                    
                                </div>
                                <br>
                                <button class="add_to_cart" id="reserve">Reserve</button>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </main>
        <script type="text/javascript" src="../../js/session.js"></script>
        <?php include '../Navbars/footer.php'; ?>
    </div>

    <script src="../../js/script.js"></script>
</body>

</html>