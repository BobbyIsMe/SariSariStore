
document.addEventListener('DOMContentLoaded', () => {
    total_sales = 'DESC';
    loadPage(1, false, "top_selling");

    total_sales = "";
    date_restocked = 'DESC';
    loadPage(1, false, "restocked_items");
});