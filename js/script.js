function signoutClick(event) {
  event.preventDefault();
  sessionStorage.clear();
  localStorage.clear();
  window.location.href = '../Signin/login.html';
}