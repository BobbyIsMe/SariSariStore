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

function filterProducts(type) {
  document.getElementById('allButton').classList.remove('active');
  document.getElementById('newButton').classList.remove('active');
  document.getElementById('filteredButton').classList.remove('active');

  document.getElementById('allButton').classList.add('inactive');
  document.getElementById('newButton').classList.add('inactive');
  document.getElementById('filteredButton').classList.add('inactive');

  if (type === 'all') {
    document.getElementById('allButton').classList.add('active');
  } else if (type === 'new') {
    document.getElementById('newButton').classList.add('active');
  } else if (type === 'filtered') {
    document.getElementById('filteredButton').classList.add('active');
  }
}

function updateCategory(mainCategory, subCategory) {
  document.getElementById('categoryHeading').innerHTML = `<b>${mainCategory} > ${subCategory}</b>`;
}