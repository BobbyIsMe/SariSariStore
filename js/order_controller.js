document.addEventListener('DOMContentLoaded', () => {
    loadOrder();
});

document.getElementById("cancel").addEventListener("click", () => {
    fetch('../../php/cancel_order.php')
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
                                        <img src="" alt="img">
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
                                        <div style="width: auto; text-align: right;">₱12.00</div>
                                        <div class="d-flex flex-column align-items-center text-center"
                                            style="gap: 10px; ">
                                            <div readonly
                                                style="height: 15px; width: 100px; font-size: 12PX; color: red; ">
                                                </div>
                                            <div>
                                                <div>
                                                    ${item.quantity} pcs
                                                </div>
                                            </div>
                                            <div style="font-size: 10px; color: gray;">${item.stock_qty} in Stock</div>
                                        </div>
                                        <div>
                                            
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