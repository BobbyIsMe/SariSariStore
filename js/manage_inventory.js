let totalPages = 1;
let page = 1;
let category = "";
let subcategory = "";
let brand = "";
let stock_qty = "";
let item_name = "";
let total_sales = "";
let date_restocked = "";
let recent = "";
let query = "";
let products = [];
let categories = [];

document.addEventListener('DOMContentLoaded', () => {
  loadPage(1);
  loadCategories();
});

function refreshFilter() {
  category = "";
  subcategory = "";
  brand = "";
  stock_qty = "";
  item_name = "";
  total_sales = "";
  date_restocked = "";
  recent = "";
  query = "";
}

function previewSelectedImage(event, id) {
  const input = event.target;
  const preview = document.getElementById(id);

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      preview.src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function onSubmit(e) {
  e.preventDefault();
  refreshFilter();
  const form = document.getElementById("filter_form");
  const formData = new FormData(form);
  document.getElementById("all").classList.remove("active");
  document.getElementById("recent").classList.remove("active");
  document.getElementById("filter_button").classList.add("active");
  category = formData.get("category");
  subcategory = formData.get("subcategory");
  brand = formData.get("brand");
  stock_qty = formData.get("stock_qty");
  item_name = formData.get("item_name");
  total_sales = formData.get("total_sales");
  date_restocked = formData.get("date_restocked");
  const newParams = new URLSearchParams(
    {
      category: formData.get("category"),
      subcategory: formData.get("subcategory"),
      brand: formData.get("brand"),
      stock_qty: formData.get("stock_qty"),
      item_name: formData.get("item_name"),
      total_sales: formData.get("total_sales"),
      date_restocked: formData.get("date_restocked")
    }
  );
  history.replaceState(null, "", "?" + newParams.toString());
  page = 1;
  const modal = bootstrap.Modal.getInstance(document.getElementById('filter_popup'));
  modal.hide();
  loadPage(1);
}

document.getElementById("recent").addEventListener("click", function (event) {
  event.preventDefault();
  document.getElementById("filter_form").reset();
  document.getElementById("recent").classList.add("active");
  document.getElementById("filter_button").classList.remove("active");
  document.getElementById("all").classList.remove("active");
  refreshFilter();
  const newParams = new URLSearchParams(
    {
      recent: "DESC"
    }
  );
  history.replaceState(null, "", "?" + newParams.toString());
  recent = "ASC";
  page = 1;
  loadPage(1);
});

document.getElementById("all").addEventListener("click", function (event) {
  event.preventDefault();
  document.getElementById("filter_form").reset();
  document.getElementById("recent").classList.remove("active");
  document.getElementById("filter_button").classList.remove("active");
  document.getElementById("all").classList.add("active");
  refreshFilter();
  history.replaceState(null, "", window.location.pathname);
  page = 1;
  loadPage(1);
});

function editItem(product_id) {
  const dropdown = document.getElementById("edit_product_subcategory");
  categories[products[product_id].category].forEach(category => {
    category.forEach(item => {
      const option = document.createElement("option");
      option.value = item.category_id;
      option.textContent = item.subcategory;
      dropdown.appendChild(option.cloneNode(true));
    });
  });
  document.getElementById("edit_product_id").value = products[product_id].product_id;
  document.getElementById("edit_product_image").src = products[product_id].image;
  document.getElementById("edit_product_name").value = products[product_id].item_name;
  document.getElementById("edit_product_category").value = products[product_id].category;
  document.getElementById("edit_product_subcategory").value = products[product_id].category_id;
  document.getElementById("edit_product_brand").value = products[product_id].brand;
  document.getElementById("edit_product_stock").value = products[product_id].stock_quantity;
  document.getElementById("edit_product_price").value = products[product_id].price;
  document.getElementById("edit_product_details").value = products[product_id].item_details;
}

function onSubmitAddItem(e) {
  e.preventDefault();
  const form = document.getElementById("add_item_form");
  const formData = new FormData(form);
  const fileInput = document.getElementById('add_product_image');
  formData.set('image', fileInput.files[0]);
  formData.set('edit', 'add');

  fetch('../../php/add_new_product.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('add_item_form'));
        modal.hide();
        document.getElementById("add_item_form").reset();
        loadPage(page);
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitAddCategory(e) {
  e.preventDefault();
  const form = document.getElementById("edit_categories_form");
  const formData = new FormData(form);
  formData.set('edit', 'add');
  fetch('../../php/add_categories.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_categories_form'));
        modal.hide();
        document.getElementById("edit_categories_form").reset();
        loadCategories();
        loadSidebar();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitAddVariation(e) {
  e.preventDefault();
  const form = document.getElementById("edit_variations_form");
  const formData = new FormData(form);
  formData.set('edit', 'add');
  fetch('../../php/add_variations.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_variations_form'));
        modal.hide();
        document.getElementById("edit_variations_form").reset();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitEditVariation(e) {
  e.preventDefault();
  const variationList = Array.from(document.querySelectorAll('.variation_row input')).map(input => ({
    category_id: parseInt(input.dataset.id),
    subcategory: input.value
  }));
  fetch('../../php/add_variations.php', {
    method: 'POST',
    body: JSON.stringify({ variation_list: variationList, edit: 'edit' })
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_variations_form'));
        modal.hide();
        document.getElementById("edit_variations_form").reset();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitEditCategories(e) {
  e.preventDefault();
  const categoryList = Array.from(document.querySelectorAll('.category_row input')).map(input => ({
    category_id: parseInt(input.dataset.id),
    subcategory: input.value
  }));
  fetch('../../php/add_categories.php', {
    method: 'POST',
    body: JSON.stringify({ category_list: categoryList, edit: 'edit' })
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_categories_form'));
        modal.hide();
        document.getElementById("edit_categories_form").reset();
        loadCategories();
        loadSidebar();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitEditItem(e) {
  e.preventDefault();
  const form = document.getElementById("edit_item_form");
  const formData = new FormData(form);
  const fileInput = document.getElementById('edit_product_image');
  formData.set('image', fileInput.files[0]);
  formData.set('edit', 'edit');

  fetch('../../php/add_new_product.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_item_form'));
        modal.hide();
        document.getElementById("edit_item_form").reset();
        alert(data.message);
        loadPage(page);
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}



document.getElementById("prev_button").addEventListener("click", () => {
  if (page > 1) {
    page--;
    loadPage(page);
  }
});

document.getElementById("next_button").addEventListener("click", () => {
  if (page < totalPages) {
    page++;
    loadPage(page);
  }
});

function editCategories(category) {
  const tableBody = document.getElementById("edit_subcategories");
  tableBody.innerHTML = "";
  categories[category].forEach(subcategory => {
    subcategory.forEach(item => {
      tableBody.innerHTML += `
            <div class="d-flex flex-row flex-wrap align-items-center gap-2 subcategory_row">
              <input type="text" class="form-control" style="width: 200px; font-size: 14px;"
                value="${item.subcategory} "placeholder="Subcategory Name" data-id="${item.category_id}" required>
              <button type="button" class="btn btn-danger small"
                style="width: 140px; font-size: 14px; white-space: nowrap;" onclick="removeSubcategory(${item.category_id})">Remove</button>
            </div>
            `;
    });
  })
}

function editVariations(product_id) {
  fetch(`../../php/get_variations.php?product_id=${product_id}`)
    .then(res => res.json())
    .then(data => {
      const tableBody = document.getElementById("edit_variations");
      tableBody.innerHTML = "";
      data.variations.forEach(variation => {
        tableBody.innerHTML += `
                <div class="d-flex flex-row align-items-center gap-2 variation_row">
                      <input type="text" class="form-control" style="width: 220px; font-size: 14px;"
                        value="${variation.variation_name}" name="newVariation" data-id="${variation.variation_id}" required>
                      <button type="button" class="btn btn-danger small" style="width: 140px;" onclick="removeVariation(${variation.variation_id})">Remove</button>
                    </div>
                `;
      })
    })
    .catch(err => console.error("Failed to fetch variations:", err));
}

function loadCategories() {
  const add_dropdown = document.getElementById("add_product_category");
  const edit_dropdown = document.getElementById("edit_product_category");

  dropdown.innerHTML = "";
  fetch(`../../php/get_category_id.php`)
    .then(res => res.json())
    .then(data => {
      const sidebarData = data.categories;
      Objects.keys(sidebarData).forEach((category, subcategory) => {
        categories[category].push(subcategory);
      })
      Object.keys(sidebarData).forEach(category => {
        const option = document.createElement("option");
        option.value = category;
        option.textContent = category;
        add_dropdown.appendChild(option.cloneNode(true));
        edit_dropdown.appendChild(option.cloneNode(true));
      });

    })
    .catch(err => console.error("Failed to fetch categories:", err));
}

function removeItem(product_id) {
  if (confirm("Are you sure you want to delete this product?")) {
    fetch(`../../php/remove_product.php?product_id=${product_id}`)
      .then(res => res.json())
      .then(data => {
        if (data.status === 200) {
          alert(data.message);
          loadPage(page);
        } else {
          alert(data.message);
        }
      })
      .catch(error => console.log(error));
  }
}


function removeSubcategory(category_id) {
  fetch(`../../php/remove_subcategory.php?subcategory_id=${category_id}`)
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_subcategories'));
        modal.hide();
        loadSidebar();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function removeVariation(variation_id) {
  fetch(`../../php/remove_variation.php?variation_id=${variation_id}`)
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('edit_variations'));
        modal.hide();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}


function loadPage(page) {
  query = "";
  if (category != null && category != "") query += `&category=${category}`;
  if (subcategory != null && subcategory != "") query += `&subcategory=${subcategory}`;
  if (brand != null && brand != "") query += `&brand=${brand}`;
  if (stock_qty != null && stock_qty != "") query += `&stock_qty=${stock_qty}`;
  if (item_name != null && item_name != "") query += `&item_name=${item_name}`;
  if (total_sales != null && total_sales != "") query += `&total_sales=${total_sales}`;
  if (date_restocked != null && date_restocked != "") query += `&date_restocked=${date_restocked}`;

  const tableBody = document.getElementById(id != null ? id : "products_list");
  tableBody.innerHTML = "Loading products...";

  fetch(`../../php/get_inventory_list.php?page=${page}${query}`)
    .then(res => res.json())
    .then(data => {
      totalPages = data.totalPages;
      const productsData = data.rents;

      products = [];
      productsData.forEach(product => {
        products[product.product_id] = {
          ["image"]: product.image,
          ["name"]: product.item_name,
          ["category_id"]: product.category_id,
          ["item_details"]: product.item_details,
          ["category"]: product.category,
          ["subcategory"]: product.subcategory,
          ["brand"]: product.brand,
          ["price"]: product.price,
          ["stock_qty"]: product.stock_qty
        };
        tableBody.innerHTML += `
                    <div class="cart-container"
              style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5; justify-content: center; align-items: center; ">
              <div class="item_image" style="width: 300px; height: 400px;">
                <img src="${product.image}" alt="img">
              </div>
              <!-- cart items-->
              <div class="cart_items">
                <div>
                  <h4><b>Product Details</b></h4>
                </div>

                <br>
                <div class="d-flex flex-row col-12">
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Name</b><br></h5>
                    <div>${product.brand} | ${product.item_name}</div>
                  </div>
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Item ID</b><br></h5>
                    <div>${product.product_id}</div>
                  </div>
                </div>
                <br>
                <div class="d-flex flex-row col-12">
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Category</b><br></h5>
                    <div>${product.category}</div>
                  </div>
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Subcategory</b><br></h5>
                    <div>${product.subcategory}</div>
                  </div>
                </div>


                <br>

                <div class="d-flex flex-column">
                  <h5><b>Description</b></h5>
                  <div class="description-text">
                    ${product.item_details}
                  </div>
                </div>

                <div class="col-12 d-flex flex-row" style="padding-top: 20px;">
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Price</b><br></h5>
                    <div>₱${product.price}</div>
                  </div>
                  <div class="col-6 d-flex flex-column gap-1">
                    <h5><b>Current Quantity</b><br></h5>
                    <div>${product.stock_qty}</div>
                  </div>

                </div>





              </div>

              <!--order summary-->
              <div class="order_summary" style="background-color:  #f5f5f5;">

                <div class="d-flex flex-column align-items-center p-4" style="gap: 20px;">

                  <button id="edit_item" class="btn adminBtn " type="button" style="background-color: #e6cc8c;;"
                    data-bs-toggle="modal" data-bs-target="#editItemModal" onclick="editItem(${product.product_id})">
                    Edit Item
                  </button>
                  <button id="edit_variations" type="button" class="btn adminBtn" style="background-color: #e6cc8c;"
                    data-bs-toggle="modal" data-bs-target="#editVariationsModal" onclick="editVariations(${product.product_id})">
                    Edit Variations
                  </button>
                  <button id="remove" class="btn adminBtn " type="button" style="background-color: red; color: white;" onclick="removeItem(${product.product_id})">
                    Remove
                  </button>
                </div>


              </div>
            </div>
                    `;
        document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
        document.getElementById("prev_button").disabled = (page === 1);
        document.getElementById("next_button").disabled = (page >= totalPages);
      });
    })
    .catch(err => console.error("Failed to fetch products:", err));
}

function loadHTML(tableBody, product) {
  in_stock = product.stock_qty > 0;
  tableBody.innerHTML += `
    <div class="product_card">
        <div class="image"><img src="../../img/${product.image}" alt="img"></div>
        <div class="subcategory" style="font-size: 10px;">${product.subcategory}</div>
        <div class="name">${product.brand} | ${product.item_name}</div>
        <div class="price"><strong>${product.price}</strong></div>
        <button ${in_stock ? "" : "disabled"} style="width: 100%; margin-top: 5px;" onclick="addToCart(${product.product_id})">${in_stock ? "Add to Cart" : "Out of Stock"}</button>
    </div>
    `;
}