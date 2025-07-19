<?php
// include_once("../../php/db_connect.php");
// $product_id = $_GET['product_id'] ?? null;

// $stmt = $con->prepare("
// SELECT item_name,brand FROM Products WHERE product_id = ? LIMIT 1");
// $stmt->bind_param('i', $product_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $stmt->close();
// if (!$result || $result->num_rows === 0) {
//     header("Location: ../../html/Webpages/homepage.php");
//     exit();
// }

// $row = $result->fetch_assoc();
// $item_name = $row['item_name'];
// $brand = $row['brand'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $brand ?> | <?= $item_name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/navbarFooter.css">
    <link rel="stylesheet" href="../../css/webpageBody.css">
    <link rel="stylesheet" href="../../css/itemDescription.css">
    <link rel="stylesheet" href="../../css/loadingscreen.css">
    <link rel="stylesheet" href="../../css/failedtoload.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script type="text/javascript" src="../../js/auth.js"></script>
    <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
    <script type="text/javascript" src="../../js/products_display.js" defer></script>
    <script type="text/javascript" src="../../js/item_page.js" defer></script>
    <script type="text/javascript" src="../../js/notifications_controller.js" defer></script> -->
    <style>
        * {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .desc {
            font-size: 12px;
            max-width: 300px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
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
                                    <a
                                        href="../Webpages/category.php?category=${category}&subcategory=${item.subcategory}">
                                        ${item.subcategory}</a>
                                </ul>
                            </details>
                        </div>
                    </aside>
                </div>

                <main style="flex-grow: 1; padding: 20px;">
                    <div class="object_container">
                        <div style="border-bottom: 1px solid black; padding: 40px; ;">
                            <div class="d-flex align-items-center justify-content-center"
                                style="background-color: rgb(255, 255, 255); gap: 50px; ">
                                <div class="item_desc">
                                    <img src="../../img/test1.png" alt="img" id="image"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>

                                <div>
                                    <div class="product_card" style="width: 300px; height: 100%;">
                                        <div class="category" id="category">
                                            category_placeholder | subcategory_placeholder</div>
                                        <div class="name" id="item_name">name_placeholder</div>
                                        <div class="price" id="price"><strong>price_placeholder</strong></div>
                                        <br>
                                        <div class="desc" id="item_details">desc_placeholder</div>
                                        <br>

                                        <div class="product_controls">
                                            <div class="quantity_control w-100">
                                                <button id="decrease_button">-</button>
                                                <input type="text" id="item_qty" value="1" readonly />
                                                <button id="increase_button">+</button>
                                            </div>

                                            <div class="flex-grow-1">
                                                <label for="itemVariation" class="form-label">Item Variation:</label>
                                                <select class="form-select" id="variations_dropdown"
                                                    name="itemVariation" required title="Select Option"
                                                    style="width: 100%;">
                                                    <option value="">Select Option</option>
                                                </select>
                                            </div>

                                            <div class="price" id="subtotal">subtotal_placeholder</div>
                                            <button class="add_to_cart" id="add_product">Add To Cart</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section style=" margin-top: 30px;">
                            <div class="d-flex flex-row justify-content-between align-items-center me-3">
                                <h4><b id="category_title">More Items</b></h4>
                                <a href="" id="see_more">See More</a>
                            </div>

                            <div class="d-flex flex-row justify-content-between" id="products_list">
                                <div class="product_card">
                                    <a href="../../html/Webpages/itemDescription.php?product_id=${product.product_id}">
                                        <div class="image"><img src="" alt="img"></div>
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
                                        <div class="image" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category" style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dgsauydgsayudgsdsay</div>
                                    <a><br>
                                        <div class="name" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dasdasd</div>
                                        <div class="price" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"><strong>dsai</strong></div><br>
                                        <div class="add_to_cart" style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                        <div class="image" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category" style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dgsauydgsayudgsdsay</div>
                                    <a><br>
                                        <div class="name" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dasdasd</div>
                                        <div class="price" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"><strong>dsai</strong></div><br>
                                        <div class="add_to_cart" style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                        <div class="image" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category" style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dgsauydgsayudgsdsay</div>
                                    <a><br>
                                        <div class="name" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dasdasd</div>
                                        <div class="price" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"><strong>dsai</strong></div><br>
                                        <div class="add_to_cart" style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">Add to Cart</div>
                                    </a>
                                </div>

                                <div class="product_card">
                                    <!----FILLER CARD---->
                                        <div class="image" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"></div>
                                    <br>
                                    <div class="category" style="font-size: 10px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dgsauydgsayudgsdsay</div>
                                    <a><br>
                                        <div class="name" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">dasdasd</div>
                                        <div class="price" style="background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)"><strong>dsai</strong></div><br>
                                        <div class="add_to_cart" style="width: 100%; margin-top: 5px; background-color: rgb(211, 211, 211); color: rgb(211, 211, 211)">Add to Cart</div>
                                    </a>
                                </div>
                            </div>
                            

                            
                        </section>
                </main>
            </div>
        </main>
        <?php include '../Navbars/footer.php'; ?>
        <script type="text/javascript" src="../../js/session.js"></script>


    </div>
</body>

</html>