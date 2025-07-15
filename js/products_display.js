let totalPage = 1;
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

document.getElementById("prev_button").addEventListener("click", () => {
    if (page > 1) {
        page--;
        loadPage(page, true);
    }
});

document.getElementById("next_button").addEventListener("click", () => {
    if (page < totalPages) {
        page++;
        loadPage(page, true);
    }
});

function loadPage(page, all, id = null) {
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

    fetch(`../../php/get_product_list.php?page=${page}${query}`)
        .then(res => res.json())
        .then(data => {
            totalPages = data.totalPages;
            const productsData = data.rents;

            products = [];
            if (!all)
                productsData.slice(0, 5).forEach(product => {
                    loadHTML(tableBody, product);
                });
            else {
                productsData.forEach(product => {
                    loadHTML(tableBody, product);
                });

                if (all) {
                    document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
                    document.getElementById("prev_button").disabled = (page === 1);
                    document.getElementById("next_button").disabled = (page >= totalPages);
                } else {
                    if (data.totalPages == 0)
                        tableBody.innerHTML += data.message;
                }
            }
        })
        .catch(err => console.error("Failed to fetch products:", err));
}

function addToCart(product_id) {
    window.location.href = "../itemDescription.php?product_id=" + product_id;
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