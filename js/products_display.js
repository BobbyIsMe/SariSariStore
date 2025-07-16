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
            tableBody.innerHTML = "";
            totalPages = data.totalPages;
            const productsData = data.products;

            products = [];
            if (!all)
                productsData.slice(0, 5).forEach(product => {
                    loadHTML(tableBody, product);
                });
            else {
                productsData.forEach(product => {
                    loadHTML(tableBody, product);
                });

                document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
                document.getElementById("prev_button").disabled = (page === 1);
                document.getElementById("next_button").disabled = (page >= totalPages);
            }
        })
        .catch(err => console.error("Failed to fetch products:", err));
}

function addToCart(product_id) {
    window.location.href = "../Webpages/itemDescription.php?product_id=" + product_id;
}


function loadHTML(tableBody, product) {
    const in_stock = product.stock_qty > 0;
    tableBody.innerHTML += `
    <div class="product_card">
                                    <a href="../../html/Webpages/itemDescription.php?product_id=${product.product_id}">
                                        <div class="image"><img src="../../img/${product.image}" alt="img"></div>
                                    </a>
                                    <div class="category" style="font-size: 10px;">${product.category} | ${product.subcategory}</div>
                                    <a>
                                        <div class="name">${product.brand} | ${product.item_name}</div>
                                        <div class="price"><strong>₱${product.price}</strong></div>
                                        <button ${in_stock ? "" : "disabled"} class="add_to_cart" style="width: 100%; margin-top: 5px;" onclick="addToCart(${product.product_id})">${in_stock ? "Add to Cart" : "Out of Stock"}</button>
                                    </a>
                                </div>

    `;
}