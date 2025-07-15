product_id = null;
quantity = 1;
stock_quantity = 0;

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    fetch("../../php/get_product.php" + "?product_id=" + urlParams.get("product_id"))
        .then(res => res.json())
        .then(data => {
            if (data.product_id == null) {
                window.location.replace('../html/Webpages/homepage.php');
                return;
            }
            product_id = data.product_id;
            document.getElementById("image").src = data.image;
            document.getElementById("item_name").textContent = data.brand + " | " + data.item_name;
            document.getElementById("category").textContent = data.category;
            document.getElementById("subcategory").textContent = data.subcategory;
            document.getElementById("stock_qty").textContent = (data.stock_quantity > 0) ? 1 : 0;
            document.getElementById("price").textContent = data.price;
            document.getElementById("item_details").textContent = data.item_details;

            document.getElementById("add_product").textContent = (data.stock_quantity > 0) ? "Add to Cart" : "Out of Stock";
            document.getElementById("add_product").disabled = (data.stock_quantity === 0);

            document.getElementById("see_more").href = "../Webpages/category.php?category=" + data.category + "&subcategory=" + data.subcategory;

            const dropdown = document.getElementById("variations_dropdown");
            const dropdownSort = document.getElementById("variations");
            fetch("../../php/get_variations.php" + "?product_id=" + urlParams.get("product_id"))
                .then(res => res.json())
                .then(data => {
                    variations = data.variations;
                    Object.keys(variations).forEach(variationId => {
                        const variation = variations[variationId];
                        const option = document.createElement("option");
                        option.value = variation.variation_id;
                        option.textContent = `${variation.variation_name}`;
                        dropdown.appendChild(option.cloneNode(true));
                        dropdownSort.appendChild(option.cloneNode(true));
                    });
                })
                .catch(error => console.log(error));
            loadPage(1, false);

            document.getElementById("decrease_button").disabled = (quantity === 0);
            document.getElementById("increase_button").disabled = (quantity >= stock_quantity);
        })
        .catch(error => console.log(error));
});

document.getElementById("decrease_button").addEventListener("click", () => {
    if (quantity > 0) {
        quantity--;
    }
});

document.getElementById("increase_button").addEventListener("click", () => {
    if (quantity < stock_quantity) {
        quantity++;
    }
});

document.getElementById("add_product").addEventListener("click", () => {
    fetch("../../php/add_to_cart.php", {
        method: "POST",
        body: JSON.stringify({
            "product_id": product_id,
            "variation_id": document.getElementById("variations_dropdown").value,
            "quantity": quantity
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                window.location.replace("../html/Cart/cart.php");
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
});