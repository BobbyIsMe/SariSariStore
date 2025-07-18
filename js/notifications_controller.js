let totalNotifPages = 0;
let notif_page = 1;

const tableBody = document.getElementById("notifications_popup");
tableBody.innerHTML = "";

document.getElementById("notif_prev_button").addEventListener("click", () => {
    if (notif_page > 1) {
        notif_page--;
        loadNotification(notif_page);
    }
});

document.getElementById("notif_next_button").addEventListener("click", () => {
    if (notif_page < totalNotifPages) {
        notif_page++;
        loadNotification(notif_page);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadNotificationBadge();
    loadNotification(notif_page);
});

function loadNotification(notif_page) {
    tableBody.innerHTML = "";
    fetch(`../../php/get_notifications.php?page=${notif_page}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                color = "";
                const notifications = data.notifications;
                totalNotifPages = data.totalPages;
                notifications.forEach(notif => {
                    if (notif.status === "approved")
                        color = "rgb(217, 255, 0)";
                    else if (notif.status === "rejected")
                        color = "red";
                    else if (notif.status === "closed")
                        color = "rgb(185, 185, 185)";
                    tableBody.innerHTML +=
                        `
                    <div class="notification_card d-flex mb-3">
                                            <div class="col-1" style="background-color: ${color};"></div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <div class="fw-bold">Order ID# ${notif.cart_id}</div>
                                                <div class="notification_message">${notif.message}
                                                </div>
                                                <small class="text-muted">${notif.date_time_created}</small>
                                            </div>
                                        </div>
                    `;
                });
            }
            document.getElementById("notif_page_number").innerHTML = data.totalPages != 0 ? `Page <strong>${notif_page}</strong> of <strong>${data.totalPages}</strong>` : data.message;
            document.getElementById("notif_prev_button").disabled = (notif_page === 1);
            document.getElementById("notif_next_button").disabled = (notif_page >= totalNotifPages);
        })
        .catch(error => console.log(error));
}

document.getElementById("notifications").addEventListener("click", () => {
    fetch('../../php/read_notifications.php')
        .then(res => res.json())
        .then(() => {
            loadNotificationBadge();
        })
        .catch(error => console.log(error));
});

function loadNotificationBadge() {
    fetch('../../php/get_notification_count.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                document.getElementById("notification_count").innerHTML =
                    `
                <div class="notification-badge">${data.notification_count}</div>
                `;
            } else {
                document.getElementById("notification_count").innerHTML = "";
            }
        })
        .catch(error => console.log(error));
}