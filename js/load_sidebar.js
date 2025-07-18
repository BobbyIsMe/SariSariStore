function loadSidebar() {
    fetch('../../php/get_sidebar.php')
        .then(res => res.json())
        .then(data => {
            const sidebarBody = document.getElementById('sidebar');
            sidebarBody.innerHTML = "";
            const sidebarData = data.categories;
            if (data.status != 200) {
                sidebarBody.innerHTML = data.message;
                return;
            }

            Object.entries(sidebarData).forEach(([category, subcategories]) => {
                subListHTML = `
            <details class="category">
            <summary>${category}</summary>
            `;

                subcategories.forEach(item => {
                    subListHTML += `
                <ul class="subcategory">
                <a href="../Webpages/category.php?category=${category}&subcategory=${item.subcategory}">• ${item.subcategory}</a>
                </ul>
                `;
                });

                subListHTML += `</details>`;

                sidebarBody.innerHTML += subListHTML;
            });
        })
        .catch(err => console.error("Failed to fetch sidebar categories:", err));
}

const searchInput = document.getElementById('search_input');
const searchButton = document.getElementById('search_button');

document.addEventListener('DOMContentLoaded', () => {
    loadSidebar();
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has("item_name") && [...urlParams].length === 1) {
        searchInput.value = urlParams.get("item_name");
    }
});

function handleSearch() {
    const query = searchInput.value.trim();
    if (query !== '') {
        window.location.href = `../../html/Webpages/category.php?item_name=${query}`;
    }
}

searchInput.addEventListener('keydown', function (event) {
    if (event.key === 'Enter') {
        handleSearch();
    }
});

searchButton.addEventListener('click', handleSearch);