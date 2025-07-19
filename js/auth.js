function signupSubmit(e) {
    e.preventDefault();

    const formData = new FormData(document.getElementById("signupForm"));
    const data = new URLSearchParams(formData);

    fetch('../../php/signup.php', {
        method: 'POST',
        body: data
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(res => {
            alert(res["message"]);
            if (res["status"] === 200) {
                document.getElementById("signupForm").reset();
                window.location.replace('../../html/Signin/login.php');
            }
        })
        .catch(error => {
            console.error("Error during sign-up:", error);
            alert("An error occurred. Please try again.");
        });
}

function signinSubmit(e) {
    e.preventDefault();
    var url = "../../php/signin.php";

    const formData = new FormData(document.getElementById("signinForm"));
    const data = new URLSearchParams(formData);
    fetch(`${url}?${data.toString()}`, {
        method: "POST",
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(res => {
            if (res["status"] == 200) {
                document.getElementById("signinForm").reset();
                window.location.replace('../../html/Webpages/homepage.php');
            } else {
                alert(res["message"]);
            }
        })
        .catch(error => {
            console.error("Error during sign-in:", error);
            alert("An error occurred. Please try again.");
        });
}

function signoutClick(e) {
    e.preventDefault();

    fetch("../../php/signout.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(res => {
            if (res["status"] === 200) {
                window.location.replace('../../html/Signin/login.php'); // replaces the current location
                // or: window.location = '../public/index.php'; // navigates to another location
            }
        })
        .catch(error => {
            console.error("Error during sign-out:", error);
        });
}