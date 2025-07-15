function signoutClick(event) {
  event.preventDefault();
  sessionStorage.clear();
  localStorage.clear();
  window.location.href = '../Signin/login.html';
}

    const params = new URLSearchParams(window.location.search);
    const sub = params.get('subcategory');
    if (sub) {
      document.getElementById("categoryHeading").innerHTML = `<b>Snacks &gt; ${sub}</b>`;
    }