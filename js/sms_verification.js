document.addEventListener('DOMContentLoaded', () => {
    sendCode(true);
});

function sendCode(load) {
    fetch('../../php/generate_sms_code.php')
        .then(res => res.json())
        .then(
            data => {
                if (!load)
                    alert(data.message);
            }
        ).catch(error => console.log(error));
}


function verifyCode(e) {
    e.preventDefault();
    const form = document.getElementById("verify_form");
    const formData = new FormData(form);
    fetch('../../php/verify_account.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 200) {
                alert(data.message);
                window.location.replace("../../html/Webpages/homepage.php");
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.log(error));
}