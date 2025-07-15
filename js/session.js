document.addEventListener("DOMContentLoaded", () => {
    fetch("../../php/session_status.php")
        .then(res => res.json())
        .then(data => {
            const authLink = document.getElementById("authLink");
            const personalDetails = document.getElementById("personalDetailsLink");
            const rentedRoom = document.getElementById("rentedRoomLink");
            if (!data.loggedIn) {
                authLink.textContent = "Sign In";
                authLink.onclick = null;
                authLink.setAttribute("href", "../../html/Signin/login.php");

                personalDetails.style.display = "none";
                rentedRoom.style.display = "none";
            }
        })
        .catch(err => console.error("Session check failed:", err));
});

const profileDropdown = document.getElementById("profileDropdown");

profileDropdown.addEventListener("click", function () {
    profileDropdown.classList.toggle("active");
});

document.addEventListener('click', function (event) {
    if (!profileDropdown.contains(event.target)) {
        profileDropdown.classList.remove('active');
    }
});