let product_id = null;
let quantity = 1;
let stock_quantity = 0;

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    fetch("../../php/get_product.php" + "?product_id=" + urlParams.get("product_id"))
        .then(res => res.json())
        .then(data => {
            if (data.product_id == null) {
                window.location.replace('../../html/Webpages/homepage.php');
                return;
            }
            product_id = data.product_id;
            category = data.category;
            stock_quantity = data.stock_qty;
            document.getElementById("image").src = `../../img/${data.image}`;
            document.getElementById("item_name").textContent = data.brand + " | " + data.item_name;
            document.getElementById("category").textContent = data.category + " | " + data.subcategory;
            document.getElementById("category_title").textContent = `More ${data.category}`;
            // document.getElementById("stock_qty").textContent = (data.stock_quantity > 0) ? 1 : 0;
            document.getElementById("price").textContent = `₱${data.price}`;
             document.getElementById("price").value = data.price;
            document.getElementById("item_details").textContent = data.item_details;

            document.getElementById("add_product").textContent = (stock_quantity > 0) ? "Add to Cart" : "Out of Stock";
            document.getElementById("add_product").disabled = (stock_quantity === 0);

            document.getElementById("subtotal").textContent = `₱${Number(quantity * data.price).toFixed(2)}`;

            document.getElementById("see_more").href = "../Webpages/category.php?category=" + data.category + "&subcategory=" + data.subcategory;

            const dropdown = document.getElementById("variations_dropdown");
            fetch("../../php/get_variations.php" + "?product_id=" + urlParams.get("product_id"))
                .then(res => res.json())
                .then(data => {
                    const variations = data.variations;

                    if (variations.length === 0) {
                        const option = document.createElement("option");
                        option.textContent = `No variations available`;
                        dropdown.appendChild(option.cloneNode(true));
                        return;
                    }
                    Object.keys(variations).forEach(variationId => {
                        const variation = variations[variationId];
                        const option = document.createElement("option");
                        option.value = variation.variation_id;
                        option.textContent = `${variation.variation_name}`;
                        dropdown.appendChild(option.cloneNode(true));
                    });
                })
                .catch(error => console.log(error));
            loadPage(1, false);

            document.getElementById("decrease_button").disabled = (quantity === 1);
            document.getElementById("increase_button").disabled = (quantity >= stock_quantity);
        })
        .catch(error => console.log(error));
});

document.getElementById("decrease_button").addEventListener("click", () => {
    if (quantity > 1) {
        quantity--;
        document.getElementById("item_qty").value = quantity;
    }
    document.getElementById("decrease_button").disabled = (quantity === 1);
    document.getElementById("increase_button").disabled = (quantity >= stock_quantity);
    document.getElementById("subtotal").textContent = `₱${Number(quantity * document.getElementById("price").value).toFixed(2)}`;
});

document.getElementById("increase_button").addEventListener("click", () => {
    if (quantity < stock_quantity) {
        quantity++;
        document.getElementById("item_qty").value = quantity;
    }
    document.getElementById("decrease_button").disabled = (quantity === 1);
    document.getElementById("increase_button").disabled = (quantity >= stock_quantity);
    document.getElementById("subtotal").textContent = `₱${Number(quantity * document.getElementById("price").value).toFixed(2)}`;
});

document.getElementById("add_product").addEventListener("click", () => {
    const formData = new FormData();
    formData.append('product_id', product_id);
    formData.append('variation_id', document.getElementById("variations_dropdown").value);
    formData.append('quantity', quantity);

    fetch("../../php/add_to_cart.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                alert(data.message);
                window.location.replace("../../html/Cart/cart.php");
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
});