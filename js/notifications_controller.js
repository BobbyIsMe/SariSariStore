notif_page = 0;

const tableBody = document.getElementById("notifications_popup");
tableBody.innerHTML = "";

document.addEventListener('DOMContentLoaded', () => {
    color = "";
    loadNotificationBadge();
    fetch(`../../php/get_notifications.php?page=${notif_page}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                const notifications = data.notifications;
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
            } else {
                tableBody.innerHTML = data.message;
            }
        })
        .catch(error => console.log(error));
});

document.getElementById("notifications").addEventListener("click", () => {
    fetch('../../php/read_notifications.php')
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                window.location.replace("../html/Cart/notifications.php");
            } else {
                tableBody.innerHTML = data.message;
            }
        })
        .catch(error => console.log(error));
    loadNotificationBadge();
});

function loadNotificationBadge()
{
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