<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
     <link rel="icon" type="image/x-icon" href="../../icons/tab-icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <link rel="stylesheet" href="../../css/loadingscreen.css">
    <link rel="stylesheet" href="../../css/failedtoload.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script>
    <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
    <script type="text/javascript" src="../../js/products_display.js" defer></script>
    <script type="text/javascript" src="../../js/products_homepage.js" defer></script>
    <script type="text/javascript" src="../../js/notifications_controller.js" defer></script> -->
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .add_to_cart {
            width: 100%;
            margin-top: 5px;
            padding: 10px;
        }

        .loader-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;

            width: 100%;

        }
    </style>
</head>

<body>

    <div class="d-flex flex-column min-vh-100">

        <?php include '../Navbars/navbar.php'; ?>

        <main class="flex-fill">
            <div class="d-flex align-items-stretch justify-content-center" style="min-height: 100vh;">
                <div class="col-2 p-2" style="background-color: white; flex-shrink: 0;">
                    <aside class="item_nav flex-column align-items-start text-start">
                        <h6>Categories</h6>
                        <hr>
                        <div id="sidebar">
                            <details class="category">
                                <summary>${category}</summary>
                                <ul class="subcategory">
                                    <a
                                        href="../Webpages/category.php?category=${category}&subcategory=${item.subcategory}">
                                        ${item.subcategory}</a>
                                </ul>
                            </details>
                        </div>
                    </aside>
                </div>

                <section class="body_container" style="flex-grow: 1; gap: 20px; margin-top: -10px; margin-left: -10px">
                    <div class="object_container">
                        <section class="top_selling">

                            <div class="d-flex flex-row justify-content-between  align-items-center me-3">
                                <h4><b>Top Selling Products</b></h4>
                                <a href="../Webpages/category.php?total_sales=DESC">See More</a>
                            </div>

                            <div id="top_selling" class="product_list">

                                <div class="product_list col-12">
                                    <div class="loader-container">
                                        <div class="loader"></div>
                                        <div class="warning">
                                            <div class="warning__icon">
                                                <svg fill="none" height="24" viewBox="0 0 24 24" width="24"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="m13 14h-2v-5h2zm0 4h-2v-2h2zm-12 3h22l-11-19z"
                                                        fill="#393a37"></path>
                                                </svg>
                                            </div>
                                            <div class="warning__title">lorem ipsum dolor sit amet</div>
                                        </div>
                                    </div>
                                </div>
                        </section>
                    </div>
                    <br>

                    <div class="object_container" style="flex-grow: 1;">
                        <section class="restocked">
                            <div class="d-flex flex-row justify-content-between align-items-center me-3">
                                <h4><b>Restocked Items</b></h4>
                                <a href="../Webpages/category.php?date_restocked=DESC">See More</a>
                            </div>

                            <div id="restocked_items" class="product_list justify-content-between">
                                <div class="product_card">
                                    <a href="../../html/Webpages/itemDescription.php?product_id=${product.product_id}">
                                        <div class="image"><img src="../../img/bembi.jpg" alt="img"></div>
                                    </a>
                                    <div class="category">${product.category} |
                                        ${product.subcategory}</div>
                                    <a>
                                        <div class="name">${product.brand} | ${product.item_name}</div>
                                        <div class="price"><strong>₱${product.price}</strong></div>
                                        <button class="add_to_cart" style="width: 100%; margin-top: 5px;">${in_stock ?
                                            "Add to Cart" : "Out of Stock"}</button>
                                    </a>

                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                    <div class="image"
                                        style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category"
                                        style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                        dgsauydgsayudgsdsay</div>

                                    <a><br>
                                        <div class="name"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            dasdasd</div>
                                        <div class="price"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            <strong>dsai</strong>
                                        </div><br>
                                        <div class="add_to_cart"
                                            style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                    <div class="image"
                                        style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category"
                                        style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                        dgsauydgsayudgsdsay</div>

                                    <a><br>
                                        <div class="name"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            dasdasd</div>
                                        <div class="price"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            <strong>dsai</strong>
                                        </div><br>
                                        <div class="add_to_cart"
                                            style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                    <div class="image"
                                        style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category"
                                        style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                        dgsauydgsayudgsdsay</div>

                                    <a><br>
                                        <div class="name"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            dasdasd</div>
                                        <div class="price"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            <strong>dsai</strong>
                                        </div><br>
                                        <div class="add_to_cart"
                                            style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                    <div class="image"
                                        style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category"
                                        style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                        dgsauydgsayudgsdsay</div>

                                    <a><br>
                                        <div class="name"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            dasdasd</div>
                                        <div class="price"
                                            style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            <strong>dsai</strong>
                                        </div><br>
                                        <div class="add_to_cart"
                                            style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">
                                            Add to Cart</div>
                                    </a>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </main>

    <?php include '../Navbars/footer.php'; ?>
    <script type="text/javascript" src="../../js/session.js"></script>

</body>

</html>