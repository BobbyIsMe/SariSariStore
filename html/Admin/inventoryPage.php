<?php
// session_start();
// if (!isset($_SESSION["user_id"]) && !isset($_SESSION["staff_type"]) || $_SESSION["staff_type"] != "inventory") {
//   header("Location: ../Signin/Login.php");
//   exit();
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Inventory</title>
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
  <script type="text/javascript" src="../../js/manage_inventory.js" defer></script>
  <script type="text/javascript" src="../../js/notifications_controller.js" defer></script> -->
  <style>
    * {
      font-family: Verdana, Geneva, Tahoma, sans-serif;

    }

    .description_text {
      word-wrap: break-word;
      overflow-wrap: break-word;
      word-break: break-word;
      white-space: normal;
      font-size: 12px;
    }

    #editItem,
    #editVariations,
    #remove {
      width: 200px;
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
            <div id="sidebar"></div>
          </aside>
        </div>

        <main style="flex-grow: 1; padding: 20px;">
          <div class="object_container">
            <h3>Inventory</h3>
            <div class="col-12 d-flex flex-row align-items-center">
              <div class="col-8 d-flex align-items-center gap-2">
                <button id="all" class="btn adminBtn " type="button">All</button>
                <span class="d-flex align-items-center px-2">|</span>
                <button id="recent" class="btn adminBtn " type="button">Recent</button>
                <span class="d-flex align-items-center px-2">|</span>
                <button id="filter_button" class="btn adminBtn" type="button" data-bs-toggle="modal" data-bs-target="#filterItemModal">Filter</button>
                <!-- <span class="d-flex align-items-center px-2">|</span> -->
                <!-- <button id="search_item" class="btn adminBtn" type="button" data-bs-toggle="modal" data-bs-target="#searchItemModal">Search Item</button> -->
              </div>

              <div class="col-4 d-flex gap-2">
                <button id="addItemBtn" class="btn adminBtn " type="button" data-bs-toggle="modal" data-bs-target="#addItemModal" onclick="addItem()">Add Item</button>
                <span class="d-flex align-items-center px-2">|</span>
                <button class="btn adminBtn" type="button" data-bs-toggle="modal" data-bs-target="#editCategoriesModal" onclick="loadDefault()">Edit Category</button>
              </div>

            </div>

            <div class="modal fade" id="searchItemModal" tabindex="-1" aria-labelledby="searchItemModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content p-4">
                  <div class="modal-header border-0">
                    <h5 class="modal-title" id="searchItemModalLabel">Search Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  <div class="modal-body">
                    <form id="searchItemForm" class="d-flex flex-column gap-3">
                      <label for="searchInput" class="form-label mb-0"><b>Item Name:</b></label>
                      <input type="text" id="searchInput" class="form-control" placeholder="Enter item name...">

                      <div class="d-flex justify-content-center">
                        <button type="submit" class="btn px-4 py-1" style="background-color: #e6cc8c;">Search</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>



            <div id="product_list">
              <div class="cart-container"
                style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5; justify-content: center; align-items: center; ">
                <div class="item_image" style="height: 250px; width: 250px; aspect-ratio: 1/1; ">
                  <img src="../../img/test1.png" alt="img" >
                </div>
                <!-- cart items-->
                <div class="cart_items">
                  <div>
                    <h5><b>Product Details</b></h5>
                  </div>

                  
                  <div class="row">
                    <div class="col-6 ">
                      <h5><b>Name</b></h5>
                      <div class="name">${product.brand} | ${product.item_name}</div>
                    </div>
                    <div class="col-6 ">
                      <h5><b>Item ID</b></h5>
                      <div class="item_id">${product.product_id}</div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-6">
                      <h5><b>Category</b></h5>
                      <div class="category">${product.category}</div>
                    </div>
                    <div class="col-6">
                      <h5><b>Subcategory</b></h5>
                      <div class="category">${product.subcategory}</div>
                    </div>
                    
                  </div>


                  

                  <div class="row">
                    <h5><b>Description</b></h5>
                    <div class="description_text">
                      ${product.item_details}
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <h5><b>Price</b></h5>
                      <div class="price">₱${product.price}</div>
                    </div>
                    <div class="col-6 ">
                      <h5><b>Current Quantity</b></h5>
                      <div class="qty" style="font-size: 12px;">${product.stock_qty}</div>
                    </div>

                  </div>





                </div>

                <!--order summary-->
                <div class="order_summary" style="background-color:  #f5f5f5;">

                  <div class="d-flex flex-column align-items-center p-4" style="gap: 20px;">

                    <button id="editItem" class="btn adminBtn " type="button" style="background-color: #FFC107;;;"
                      data-bs-toggle="modal" data-bs-target="#editItemModal">
                      Edit Item
                    </button>
                    <button id="editVariations" type="button" class="btn adminBtn" style="background-color: #FFC107;;"
                      data-bs-toggle="modal" data-bs-target="#editVariationsModal">
                      Edit Variations
                    </button>
                    <button id="remove" class="btn adminBtn " type="button" style="background-color: red; color: white;">
                      Remove
                    </button>
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
        </main>
      </div>
    </main>

    <div class="modal fade" id="editVariationsModal" tabindex="-1" aria-labelledby="editVariationsModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="editVariationsModalLabel">Edit Variations</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div style="width: 100px; padding-bottom: 10px;">
              <label for="editVariationId" class="form-label">Item ID:</label>
              <input type="text" class="form-control" id="edit_variation_id" name="product_id" readonly>
            </div>

            <form id="edit_variations_form" class="d-flex flex-column gap-3" method="POST" onsubmit="onSubmitEditVariations(event)">
              <div class="d-flex flex-row align-items-center gap-2">
                <input type="text" class="form-control" style="width: 220px; font-size: 14px;" placeholder="Your variation here..." id="variation_name" name="variation_name">
                <button type="button" class="btn small" style="width: 140px; font-size: 16px; background-color: #FFC107;" id="addVariationBtn" onclick="onSubmitAddVariation()">Add Variation</button>
              </div>

              <div id="edit_variations">
                <div class="d-flex flex-row align-items-center gap-2 variation_row">
                  <input type="text" class="form-control" style="width: 220px; font-size: 14px;"
                    value="${variation.variation_name}" name="newVariation" data-id="${variation.variation_id}" required>
                  <button type="button" class="btn btn-danger small" style="width: 140px; background-color: red; ">Remove</button>
                </div><br>
              </div>

              <div class="d-flex justify-content-center">
                <button type="submit" class="btn px-4 py-1" style="background-color: #FFC107;">Update Variations</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form id="edit_item_form" method="POST" onsubmit="onSubmitEditItem(event)">
              <div class="d-flex gap-4">
                <div style="width: 400px;">
                  <img id="edit_image" src="" alt="Image Placeholder" style="width: 300px; height: 300px; object-fit: contain; border: 1px solid #ccc;" />
                  <input type="file" id="edit_product_image" name="image" accept="image/*"
                    onchange="previewSelectedImage(event, 'edit_image')" style="display: none;" />
                  <button type="button" class="btn btn-warning mt-2 w-100" onclick="document.getElementById('edit_product_image').click()">Change Image</button>
                </div>

                <div class="flex-grow-1">
                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="editItemName" class="form-label">Name:</label>
                      <input type="text" class="form-control" id="edit_product_name" name="item_name" required>
                    </div>

                    <div class="flex-grow-1">
                      <label for="editItemBrand" class="form-label">Brand:</label>
                      <input type="text" class="form-control" id="edit_product_brand" name="brand" required>
                    </div>

                    <div style="width: 100px;">
                      <label for="editItemId" class="form-label">Item ID:</label>
                      <input type="text" class="form-control" id="edit_product_id" name="product_id" readonly>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="editItemDescription" class="form-label">Description:</label>
                    <textarea class="form-control" id="edit_product_details" name="item_details" rows="3" required></textarea>
                  </div>

                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="editItemPrices" class="form-label">Prices:</label>
                      <input type="text" class="form-control" id="edit_product_price" name="price" required>
                    </div>

                    <div class="flex-grow-1">
                      <label for="editItemQty" class="form-label">Qty In Stock</label>
                      <input type="text" class="form-control" id="edit_product_stock" name="stock_qty" required>
                    </div>

                    <div style="width: 150px;">
                      <label for="editItemCategory" class="form-label">Category:</label>
                      <select class="form-select" id="edit_product_category" required>
                      </select>
                    </div>

                    <div style="width: 150px;">
                      <label for="editItemSubcategory" class="form-label">Subcategory:</label>
                      <select class="form-select" id="edit_product_subcategory" name="category_id" required>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-warning">Update Item</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="editCategoriesModal" tabindex="-1" aria-labelledby="editCategoriesModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4" style="max-width: 650px;">
          <div class="modal-body">
            <form id="edit_categories_form" class="d-flex flex-column gap-3" method="POST" onsubmit="onSubmitEditCategories(event)">
              <div class="d-flex flex-row flex-wrap align-items-center gap-2">
                <input type="text" class="form-control" style="width: 200px; font-size: 14px;" placeholder="Category Name" name="category" id="add_category">
                <input type="text" class="form-control" style="width: 200px; font-size: 14px;" placeholder="Subcategory Name" name="subcategory" id="add_subcategory">
                <button type="button" class="btn small" style="width: 140px; font-size: 14px; white-space: nowrap; background-color: #e6cc8c;" onclick="onSubmitAddCategory()">Add Category</button>
              </div>

              <hr class="my-2">
              <h6><b>Category</b></h6>
              <select class="form-select" style="font-size: 14px; width: 200px;" id="edit_category" required>
              </select>

              <div id="edit_subcategories">
                <div class="d-flex flex-row flex-wrap align-items-center gap-2 subcategory_row">
                  <input type="text" class="form-control" style="width: 200px; font-size: 14px;"
                    value="${item.subcategory}" placeholder="Subcategory Name" data-id="${item.category_id}" required>
                  <button type="button" class="btn btn-danger small"
                    style="width: 140px; font-size: 14px; white-space: nowrap;">Remove</button>
                </div><br>
              </div>

              <div class="d-flex justify-content-center mt-2">
                <button type="submit" class="btn px-4 py-1" style="background-color: #e6cc8c; white-space: nowrap;">Update Categories</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="addItemModalLabel">Add Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form id="add_item_form" method="POST" onsubmit="onSubmitAddItem(event)">
              <div class="d-flex gap-4">
                <div style="width: 400px;">
                  <img id="previewImage" src="" alt="Image Placeholder" style="width: 300px; height: 300px; object-fit: contain; border: 1px solid #ccc;" />
                  <input type="file" id="image" name="image" accept="image/*"
                    onchange="previewSelectedImage(event, 'previewImage')" style="display: none;" />
                  <button type="button" class="btn btn-warning mt-2 w-100" onclick="document.getElementById('image').click()">Add Image</button>
                </div>

                <div class="flex-grow-1">
                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="itemName" class="form-label">Name:</label>
                      <input type="text" class="form-control" id="itemName" name="item_name" required>
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemBrand" class="form-label">Brand:</label>
                      <input type="text" class="form-control" id="itemBrand" name="brand" required>
                    </div>
                  </div>

                  <div class="mb-3">
                    <label for="itemDescription" class="form-label">Description:</label>
                    <textarea class="form-control" id="itemDescription" name="item_details" rows="3" required></textarea>
                  </div>

                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="itemPrices" class="form-label">Price:</label>
                      <input type="text" class="form-control" id="itemPrices" name="price" required>
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemQty" class="form-label">Qty In Stock</label>
                      <input type="text" class="form-control" id="itemQty" name="stock_qty" required>
                    </div>

                    <div style="width: 150px;">
                      <label for="itemCategory" class="form-label">Category:</label>
                      <select class="form-select" id="add_product_category" required>
                      </select>
                    </div>

                    <div style="width: 150px;">
                      <label for="itemSubcategory" class="form-label">Subcategory:</label>
                      <select class="form-select" name="category_id" id="add_product_subcategory" required>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-warning">Add Item</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="filterItemModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
          <div class="modal-header border-0">
            <h5 class="modal-title" id="filterItemModalLabel">Filter Item</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <form id="filter_form" method="GET" onsubmit="onSubmitFilter(event)">
              <div class="d-flex gap-4">
                <div class="flex-grow-1">
                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="itemName" class="form-label">Item:</label>
                      <input type="text" class="form-control" id="itemName" name="item_name" value="">
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemBrand" class="form-label">Brand:</label>
                      <input type="text" class="form-control" id="itemBrand" name="brand" value="">
                    </div>
                  </div>

                  <div class="mb-3 d-flex gap-3">
                    <div class="flex-grow-1">
                      <label for="itemCategory" class="form-label">Category:</label>
                      <input type="text" class="form-control" id="itemName" name="category" value="">
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemSubcategory" class="form-label">Subcategory:</label>
                      <input type="text" class="form-control" id="itemBrand" name="subcategory" value="">
                    </div>
                  </div>
                  <div class="mb-3 d-flex gap-3">

                    <div class="flex-grow-1">
                      <label for="itemStockQuantity" class="form-label">Stock Quantity:</label>
                      <select class="form-select" id="itemCategory" name="stock_qty">
                        <option value="">Select Option</option>
                        <option value="DESC">Highest Stock</option>
                        <option value="ASC">Lowest Stock</option>
                      </select>
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemTotalSales" class="form-label">Total Sales:</label>
                      <select class="form-select" id="itemSubcategory" name="total_sales">
                        <option value="">Select Option</option>
                        <option value="DESC">Highest Sales</option>
                        <option value="ASC">Lowest Sales</option>
                      </select>
                    </div>

                    <div class="flex-grow-1">
                      <label for="itemDateRestock" class="form-label">Date Restocked</label>
                      <select class="form-select" id="itemSubcategory" name="date_restocked">
                        <option value="">Select Option</option>
                        <option value="DESC">Recent</option>
                        <option value="ASC">Oldest</option>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-warning">Filter</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php include '../Navbars/footer.php'; ?>
</body>

</html>