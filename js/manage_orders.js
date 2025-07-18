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

document.getElementById("recent").addEventListener("click", function (event) {
    refreshFilter();
    document.getElementById("recent").classList.add("active");
    document.getElementById("status_dropdown").value = "status";
    page = 1;
    loadPage(1);
});

document.getElementById("status_dropdown").addEventListener("change", function () {
    refreshFilter();
    document.getElementById("recent").classList.remove("active");
    const selectedValue = this.value;
    stat = selectedValue;
    page = 1;
    loadPage(1);
});

function changeStatus(order_id, old_value, status) {
    const formData = new FormData();
    formData.append('cart_id', order_id);
    formData.append('status', status.value);
    formData.append('date_time_deadline', document.getElementById(`date_${order_id}`).value);
    fetch('../../php/manage_order.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json()).then(
        data => {
            if (data.status === 200) {
                loadNotificationBadge();
                loadNotification(1);
                loadPage(page);
            } else {
                status.value = old_value;
                alert(data.message);
            }
        }
    ).catch(error => console.log(error));
}

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
                                                <div>₱${item.subtotal}</div>
                                                <div>${item.item_qty} pcs</div>
                                            </div>
                                        </div>
        `;
    });
}

function loadPage(page) {
    query = "";
    order_items = [];
    if (stat != null && stat != "") query += `&status=${stat}`;

    const tableBody = document.getElementById("orders_list");
    tableBody.innerHTML = "Loading orders...";

    fetch(`../../php/get_order_list.php?page=${page}${query}`)
        .then(res => res.json())
        .then(data => {
            totalPages = data.totalPages;
            const ordersData = data.orders;
            const orderItemsData = data.order_items;
            tableBody.innerHTML = "";
            if (data.status != 200) {
                document.getElementById("page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
                document.getElementById("prev_button").disabled = (page === 1);
                document.getElementById("next_button").disabled = (page >= totalPages);
                return;
            }

            Object.entries(orderItemsData).forEach(([order_id, items]) => {
                order_items[order_id] = items;
            });

            const date = new Date();
            date.setDate(date.getDate() + 1);

            const manilaDate = new Date(date.toLocaleString("en-US", { timeZone: "Asia/Manila" }));

            const year = manilaDate.getFullYear();
            const month = String(manilaDate.getMonth() + 1).padStart(2, '0');
            const day = String(manilaDate.getDate()).padStart(2, '0');
            const hours = String(manilaDate.getHours()).padStart(2, '0');
            const minutes = String(manilaDate.getMinutes()).padStart(2, '0');

            const date_format = `${year}-${month}-${day}T${hours}:${minutes}`;


            ordersData.forEach(order => {
                subListHTML = "";
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
                                    <div style="width: 80px; text-align: right;">₱${item.subtotal}</div>

                                    <!-- qty of items-->
                                    <div>
                                        ${item.item_qty} pcs
                                    </div>
                                </div>
                `;
                });

                const cur_status = order.status;

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
                                    <select class="form-select border-0" id="status_${order.cart_id}" onchange="changeStatus(${order.cart_id}, '${order.status}', this)" name="rent_status"
                                        required>
                                        <option value="status">Status</option>
                                        <option value="pending" ${cur_status == 'pending' ? 'selected' : ''}>Pending</option>
                                        <option value="approved" ${cur_status == 'approved' ? 'selected' : ''}>Approved</option>
                                        <option value="rejected" ${cur_status == 'rejected' ? 'selected' : ''}>Rejected</option>
                                        <option value="closed" ${cur_status == 'closed' ? 'selected' : ''}>Closed</option>
                                    </select>
                                </div>

                                <br><br><br>

                                <div class="d-flex flex-column align-items-center ">

                                    <div class="" style="border-top: solid black 1px;">
                                        <label for="check_in_date" class="form-label">Check-in Date</label>
                                        <input type="datetime-local" class="paragraphs form-control" id="date_${order.cart_id}"
                                            name="check_in_date" value="${date_format}" required>
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

