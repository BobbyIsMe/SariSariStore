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
    document.getElementById("all").classList.add("active");
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

function onSubmitFilter(e) {
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
  page = 1;
  const modal = bootstrap.Modal.getInstance(document.getElementById('filterItemModal'));
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

  recent = "DESC";
  page = 1;
  loadPage(1);
});

document.getElementById("all").addEventListener("click", function () {
  event.preventDefault();
  document.getElementById("filter_form").reset();
  document.getElementById("recent").classList.remove("active");
  document.getElementById("filter_button").classList.remove("active");
  document.getElementById("all").classList.add("active");
  refreshFilter();
  page = 1;
  loadPage(1);
});

document.getElementById("edit_category").addEventListener("change", function () {
  editCategories(document.getElementById("edit_category").value);
});

document.getElementById("add_product_category").addEventListener("change", function () {
  updateSubcategories(document.getElementById("add_product_category").value, "add_product_subcategory");
});

document.getElementById("edit_product_category").addEventListener("change", function () {
  updateSubcategories(document.getElementById("edit_product_category").value, "edit_product_subcategory");
});

function addItem() {
  updateSubcategories(Object.keys(categories)[0], "add_product_subcategory");
}

function updateSubcategories(category, id) {
  const dropdown = document.getElementById(id);
  dropdown.innerHTML = "";
  if (categories == null) return;
  categories[category].forEach(subcategory => {
    const option = document.createElement("option");
    option.value = subcategory.category_id;
    option.textContent = subcategory.subcategory;
    dropdown.appendChild(option);
  });
}

function editItem(product_id) {
  updateSubcategories(products[product_id].category, "edit_product_subcategory");
  document.getElementById("edit_product_id").value = product_id;
  document.getElementById("edit_image").src = "../../img/" + products[product_id].image;
  document.getElementById("edit_product_name").value = products[product_id].item_name;
  document.getElementById("edit_product_category").value = products[product_id].category;
  document.getElementById("edit_product_subcategory").value = products[product_id].category_id;
  document.getElementById("edit_product_brand").value = products[product_id].brand;
  document.getElementById("edit_product_stock").value = products[product_id].stock_qty;
  document.getElementById("edit_product_price").value = products[product_id].price;
  document.getElementById("edit_product_details").value = products[product_id].item_details;
}


function onSubmitAddItem(e) {
  e.preventDefault();
  const form = document.getElementById("add_item_form");
  const formData = new FormData(form);
  const fileInput = document.getElementById('image');
  formData.set('image', fileInput.files[0]);
  formData.set('edit', 'add');

  fetch('../../php/add_new_product.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      console.log(data.message);
      if (data.status === 200) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
        modal.hide();
        document.getElementById("add_item_form").reset();
        alert(data.message);
        loadPage(page);
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitAddCategory() {
  const formData = new FormData();
  formData.append('category', document.getElementById("add_category").value);
  formData.append('subcategory', document.getElementById("add_subcategory").value);
  formData.append('edit', 'add');
  fetch('../../php/add_categories.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoriesModal'));
        modal.hide();
        loadCategories();
        document.getElementById("edit_categories_form").reset();
        loadSidebar();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitAddVariation() {
  const formData = new FormData();
  formData.set('product_id', document.getElementById("edit_variation_id").value);
  formData.set('variation_name', document.getElementById("variation_name").value);
  formData.set('edit', 'add');
  fetch('../../php/add_variations.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editVariationsModal'));
        modal.hide();
        document.getElementById("edit_variations_form").reset();
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.log(error));
}

function onSubmitEditVariations(e) {
  e.preventDefault();
  const container = document.getElementById('edit_variations_form');
  const variationList = Array.from(container.querySelectorAll('.variation_row input')).map(input => ({
    variation_id: parseInt(input.dataset.id),
    variation_name: input.value
  }));

  const formData = new FormData();
  formData.append('variation_list', JSON.stringify(variationList));
  formData.append('edit', 'edit');
  fetch('../../php/add_variations.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editVariationsModal'));
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
  const container = document.getElementById('edit_categories_form');
  const categoryList = Array.from(container.querySelectorAll('.subcategory_row input')).map(input => ({
    category_id: input.dataset.id,
    subcategory: input.value
  }));

  const formData = new FormData();
  formData.append('category_list', JSON.stringify(categoryList));
  formData.append('edit', 'edit');
  fetch('../../php/add_categories.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoriesModal'));
        modal.hide();
        document.getElementById("edit_categories_form").reset();
        loadCategories();
        loadSidebar();
        loadPage(page);
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
        const modal = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
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

function loadDefault() {
  document.getElementById("edit_category").value = Object.keys(categories)[0];
  editCategories(Object.keys(categories)[0]);
}

function editCategories(category) {
  const tableBody = document.getElementById("edit_subcategories");
  tableBody.innerHTML = "";
  if (categories[category] == null) {
    tableBody.innerHTML = "No Subcategories";
    return;
  }


  categories[category].forEach(item => {
    tableBody.innerHTML += `
            <div class="d-flex flex-row flex-wrap align-items-center gap-2 subcategory_row">
              <input type="text" class="form-control" style="width: 200px; font-size: 14px;"
                value="${item.subcategory}" placeholder="Subcategory Name" data-id="${item.category_id}" required>
              <button type="button" class="btn btn-danger small"
                style="width: 140px; font-size: 14px; white-space: nowrap;" onclick="removeSubcategory(${item.category_id})">Remove</button>
            </div><br>
            `;
  });
}

function editVariations(product_id) {
  fetch(`../../php/get_variations.php?product_id=${product_id}`)
    .then(res => res.json())
    .then(data => {
      const tableBody = document.getElementById("edit_variations");
      tableBody.innerHTML = "";
      document.getElementById("edit_variation_id").value = product_id;
      data.variations.forEach(variation => {
        tableBody.innerHTML += `
                <div class="d-flex flex-row align-items-center gap-2 variation_row">
                      <input type="text" class="form-control" style="width: 220px; font-size: 14px;"
                        value="${variation.variation_name}" name="newVariation" data-id="${variation.variation_id}" required>
                      <button type="button" class="btn btn-danger small" style="width: 140px;" onclick="removeVariation(${variation.variation_id})">Remove</button>
                    </div><br>
                `;
      })
    })
    .catch(err => console.error("Failed to fetch variations:", err));
}

function loadCategories() {
  const category_dropdown = document.getElementById("edit_category");
  const add_dropdown = document.getElementById("add_product_category");
  const edit_dropdown = document.getElementById("edit_product_category");

  categories = [];

  category_dropdown.innerHTML = "";
  add_dropdown.innerHTML = "";
  edit_dropdown.innerHTML = "";
  fetch(`../../php/get_category_id.php`)
    .then(res => res.json())
    .then(data => {
      const sidebarData = data.categories;
      if (sidebarData != null) {
        Object.keys(sidebarData).forEach(category => {
          if (!categories[category]) {
            categories[category] = [];
          }

          sidebarData[category].forEach(subcategory => {
            categories[category].push(subcategory);
          });
          // console.log(categories);
        });
        Object.keys(sidebarData).forEach(category => {
          const option = document.createElement("option");
          option.value = category;
          option.textContent = category;
          add_dropdown.appendChild(option.cloneNode(true));
          edit_dropdown.appendChild(option.cloneNode(true));
          category_dropdown.appendChild(option.cloneNode(true));
        });
      }
    })
    .catch(err => console.error("Failed to fetch categories:", err));
}

function removeItem(product_id) {
  const formData = new FormData();
  formData.append('product_id', product_id);
  if (confirm("Are you sure you want to delete this product?")) {
    fetch(`../../php/remove_product.php`, {
      method: 'POST',
      body: formData
    })
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
  const formData = new FormData();
  formData.append('category_id', category_id);
  fetch(`../../php/remove_subcategory.php`,
    {
      method: 'POST',
      body: formData
    }
  )
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editCategoriesModal'));
        modal.hide();
        loadSidebar();
        loadCategories();
      } else {
        alert(data.message);
        console.log('hi');
      }
    })
    .catch(error => console.log(error));
}

function removeVariation(variation_id) {
  const formData = new FormData();
  formData.append('variation_id', variation_id);
  fetch(`../../php/remove_variation.php?variation_id`, {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 200) {
        alert(data.message);
        const modal = bootstrap.Modal.getInstance(document.getElementById('editVariationsModal'));
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
  if (recent != null && recent != "") query += `&recent=${recent}`;
  if (date_restocked != null && date_restocked != "") query += `&date_restocked=${date_restocked}`;
  
  const tableBody = document.getElementById("product_list");
  tableBody.innerHTML = "Loading products...";

  fetch(`../../php/get_inventory_list.php?page=${page}${query}`)
    .then(res => res.json())
    .then(data => {
      totalPages = data.totalPages;
      const productsData = data.products;
      tableBody.innerHTML = "";

      document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
      document.getElementById("prev_button").disabled = (page === 1);
      document.getElementById("next_button").disabled = (page >= totalPages);

      products = [];
      productsData.forEach(product => {
        products[product.product_id] = {
          ["image"]: product.image,
          ["item_name"]: product.item_name,
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
                <img src="../../img/${product.image}" alt="img" style="width: 100%; height: 100%; object-fit: contain;">
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

                  <button id="editItem" class="btn adminBtn " type="button" style="background-color: #e6cc8c;;"
                    data-bs-toggle="modal" data-bs-target="#editItemModal" onclick="editItem(${product.product_id})">
                    Edit Item
                  </button>
                  <button id="editVariations" type="button" class="btn adminBtn" style="background-color: #e6cc8c;"
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
      });

    })
    .catch(err => console.error("Failed to fetch products:", err));
}