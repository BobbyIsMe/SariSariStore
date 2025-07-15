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
                const subListHTML = `
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

document.addEventListener('DOMContentLoaded', () => {
    loadSidebar();
});