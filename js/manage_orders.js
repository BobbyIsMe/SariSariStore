let totalPages = 1;
let page = 1;
let stat = "";
let query = "";
let order_items = [];
let date_time_deadline = null;

function refreshFilter() {
    stat = "";
    query = "";
}

document.addEventListener('DOMContentLoaded', () => {
    loadPage(page);
});

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

document.getElementById("status_dropdown").addEventListener("change", function () {
    refreshFilter();
    document.getElementById("filter_form").reset();
    document.getElementById("recent").classList.remove("active");
    document.getElementById("filter_button").classList.remove("active");
    const selectedValue = this.value;
    stat = selectedValue;
    page = 1;
    loadPage(1);
});

document.getElementById("set_status").addEventListener("change", function () {
    fetch('../../php/manage_order.php', {
        method: 'POST',
        body: JSON.stringify({
            order_id: this.value,
            status: document.getElementById("set_status").value,
            date_time_deadline: date_time_deadline
        })
    }).then(res => res.json()).then(
        data => {
            if (data.status === 200) {
                loadPage(page);
            } else {
                document.getElementById("set_status").value = "";
                alert(data.message);
            }
        }
    )
});

function loadOrderItems(cart_id) {
    const tableBody = document.getElementById("order_items_list");
    tableBody.innerHTML = "";

    order_items[cart_id].forEach(item => {
        tableBody.innerHTML += `
            <div
                                            class="order-item d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <div class="category" style="font-size: 12px; color: gray;">
                                                    ${item.category} | ${item.subcategory}</div>
                                                <div class="name fw-bold">${item.brand} | ${item.item_name}</div>
                                            </div>
                                            <div class="text-end">
                                                <div>₱${item.price}</div>
                                                <div>${item.quantity} pcs</div>
                                            </div>
                                        </div>
        `;
    });
}

function loadPage(page) {
    query = "";
    order_items = [];
    if (stat != null && stat != "") query += `&stat=${stat}`;

    const tableBody = document.getElementById("orders_list");
    tableBody.innerHTML = "Loading orders...";

    fetch(`../../php/get_order_list.php?page=${page}${query}`)
        .then(res => res.json())
        .then(data => {
            totalPages = data.totalPages;
            const ordersData = data.orders;
            const orderItemsData = data.order_items;
            if (data.status != 200) {
                tableBody.innerHTML = data.message;
                return;
            }

            tableBody.innerHTML = "";
            const subListHTML = "";

            Object.entries(orderItemsData).forEach(([order_id, items]) => {
                order_items[order_id] = items;
            });

            ordersData.forEach(order => {
                subListHTML += `
                <div class="cart-container"
                            style="display: flex; gap: 20px; padding: 20px; background: #f5f5f5;">

                            <!-- cart items-->
                            <div class="cart_items">
                                <div>
                                    <h6>Cart ID # ${order.cart_id}</h6>
                                </div>

                                <br>

                                <div>
                                    Name: ${order.name}
                                </div>

                                <br>

                                <div class="d-flex flex-row col-12">
                                    <div class="col-4">
                                        Reserved: ${order.date_time_created}
                                    </div>
                                    <div class="col-4">
                                        Received: ${order.date_time_received}
                                    </div>
                                    <div class="col-4">
                                        Deadline: ${order.date_time_deadline}
                                    </div>
                                </div>
                `;

                order_items[order.cart_id].slice(0, 2).forEach(item => {
                    subListHTML += `
                                <!-- item-->
                                <div class="cart_item">

                                    <!-- details-->
                                    <div style="flex: 1;">
                                        <div class="category" style="font-size: 12px; color: gray;">${item.category} | ${item.subcategory}</div>
                                        <div class="name" style="font-weight: bold;">${item.brand} | ${item.item_name}</div>
                                    </div>

                                    <!-- price -->
                                    <div style="width: 80px; text-align: right;">₱${item.price}</div>

                                    <!-- qty of items-->
                                    <div>
                                        ${item.quantity} pcs
                                    </div>
                                </div>
                `;
                });

                subListHTML += `
                <div class="d-flex justify-content-center align-items-center mt-3">
                                    <button type="button" class="btn seeMoreBtn" data-bs-toggle="modal"
                                        data-bs-target="#seeMoreModal" onclick="loadOrderItems(${order.cart_id})">
                                        See More
                                    </button>
                                </div>


                            </div>

                            <!--order summary-->
                            <div class="order_summary">
                                <div class="dropdown">
                                    <select class="form-select border-0" id="status_dropdown" name="rent_status"
                                        required>
                                        <option value="status">Status</option>
                                        <option value="closed">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>

                                <br><br><br>

                                <div class="d-flex flex-column align-items-center ">

                                    <div class="" style="border-top: solid black 1px;">
                                        <label for="check_in_date" class="form-label">Check-in Date</label>
                                        <input type="date" class="paragraphs form-control" id="rent_check_in_date"
                                            name="check_in_date" required>
                                    </div>
                                </div>


                            </div>
                        </div>
                `;

                tableBody.innerHTML += subListHTML;

                document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
                document.getElementById("prev_button").disabled = (page === 1);
                document.getElementById("next_button").disabled = (page >= totalPages);
            });

        })
        .catch(error => console.log(error));
}

