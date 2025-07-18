<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Category</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/navbarFooter.css">
  <link rel="stylesheet" href="../../css/webpageBody.css">
  <link rel="stylesheet" href="../../css/category.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script type="text/javascript" src="../../js/auth.js"></script>
  <script type="text/javascript" src="../../js/load_sidebar.js" defer></script>
  <script type="text/javascript" src="../../js/products_display.js" defer></script>
  <script type="text/javascript" src="../../js/products_category.js" defer></script>
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

    .active {
      background-color: orange !important;
      color: white;
    }

    .inactive {
      background-color: white !important;
      color: black;
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
                  <a href="../Webpages/category.php?category=${category}&subcategory=${item.subcategory}">• ${item.subcategory}</a>
                </ul>
              </details>
            </div>
          </aside>
        </div>

        <section class="body_container flex-grow-1 p-4">
          <h4><b id="category_title">Selected Category</b></h4>

          <div class="d-flex gap-2 mb-3">
            <button class="btn btn-light filter-btn" id="all">All</button>
            <button class="btn btn-light filter-btn" id="recent">New</button>
            <button class="btn btn-light filter-btn" id="filter_button"
              data-bs-toggle="modal" data-bs-target="#filterItemModal">Filtered</button>
          </div>

          <div class="product_list d-flex flex-wrap gap-4" id="products_list">
            <div class="product_card">
              <a href="../../html/Webpages/itemDescription.php?product_id=${product.product_id}">
                <div class="image"><img src="../../img/bembi.jpg" alt="img"></div>
              </a>
              <div class="category" style="font-size: 10px;">${product.category} | ${product.subcategory}</div>
              <a>
                <div class="name">${product.brand} | ${product.item_name}</div>
                <div class="price"><strong>₱${product.price}</strong></div>
                <button class="add_to_cart" style="width: 100%; margin-top: 5px;">${in_stock ? "Add to Cart" : "Out of Stock"}</button>
              </a>

            </div>
          </div>
          <div class="p-4 d-flex justify-content-center align-items-center gap-4">
            <button class="navButton" type="button" id="prev_button">
              Previous
            </button>
            <span>|</span>
            <div id="page_number" class="paragraphs">
              Page # out of #
            </div>
            <span>|</span>
            <button class="navButton" type="button" id="next_button">
              Next
            </button>
          </div>
        </section>
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
    </main>
    <script type="text/javascript" src="../../js/session.js"></script>
    <?php include '../Navbars/footer.php'; ?>

  </div>
</body>

</html>