document.addEventListener('DOMContentLoaded', () => {
    loadCart();
});

function removeProductFromCart(product_id) {
    fetch('../../php/remove_product_cart.php?product_id=' + product_id)
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                loadCart();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
}

function changeAmount(product_id, variation_id, quantity) {
    fetch('../../php/add_to_cart.php', {
        method: "POST",
        body: JSON.stringify({
            "product_id": product_id,
            "variation_id": variation_id,
            "quantity": quantity
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                loadCart();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
}

document.getElementById("reserve").addEventListener("click", () => {
    fetch('../../php/reserve_order.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                window.location.replace("../html/Orders/order.php");
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
});

function loadCart() {
    fetch('../../php/get_cart.php')
        .then(res => res.json())
        .then(data => {
            const cartData = data.cart_items;
            const cartBody = document.getElementById('cart_items');
            const detailsBody = document.getElementById('cart_details');
            if (data.status !== 200) {
                cartBody.innerHTML = data.message;
                return;
            }

            document.getElementById("cart_number").textContent = data.cart_id;
            cartBody.innerHTML = "";
            cartData.forEach(item => {
                cartBody.innerHTML += `
           <div class="cart_item">

                                    <div class="item_image">
                                        <img src="../../img/${item.image}" alt="img">
                                    </div>

                                    <!-- details-->
                                    <div style="flex: 1; ">
                                        <div class="d-flex flex-row g-2"
                                            style="gap: 20px; font-size: 12px; color: gray;">
                                            <div class="category">${item.category}</div>
                                            <div>|</div>
                                            <div class="category">${item.subcategory}</div>
                                        </div>

                                        <div class="name" style="font-weight: bold;">${item.brand} | ${item.item_name}</div>
                                        <div style="font-size: 15px;">
                                            ${item.variation_name}
                                        </div>
                                    </div>

                                    <div class="d-flex flex-row align-items-center" style="gap:20px">
                                        <div style="width: auto; text-align: right;">₱24.00</div>
                                        <div class="d-flex flex-column align-items-center text-center"
                                            style="gap: 10px; ">
                                            <div readonly
                                                style="height: 12px; width: 200px; font-size: 12PX; color: red; ">
                                                ${item.item_qty > item.stock_qty ? "QUANTITY EXCEEDS STOCK" : ""}
                                                </div>
                                            <div>

                                               
                                                <div class="quantity_control  ">
                                                    <button onclick="changeAmount(${item.product_id}, ${item.variation_id}, ${item.item_qty}-1)" ${item.item_qty <= 1 ? "disabled" : ""}>-</button>
                                                    <input type="text" value="${item.item_qty}" readonly />
                                                    <button onclick="changeAmount(${item.product_id}, ${item.variation_id}, ${item.item_qty}+1)" ${item.item_qty >= item.stock_qty ? "disabled" : ""}>+</button>
                                                </div>

                                            </div>

                                            <div style="font-size: 10px; color: gray;">${item.stock_qty} in Stock</div>
                                        </div>
                                        <div>
                                            <button style="background: none; border: none; cursor: pointer;" onclick="removeProductFromCart(${item.product_id})">🗑️
                                            </button>
                                        </div>
                                    </div>
                                </div>
            `;
                detailsBody.innerHTML += `
            <div class="summary_item">
                                    <div>${item.brand} | ${item.item_name}</div>
                                    <div>${item.subtotal}</div>
                                </div>
            `;
            })
            document.getElementById("estimated_total").textContent = data.total;
        })
        .catch(error => console.log(error));
}