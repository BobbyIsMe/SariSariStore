document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    category = urlParams.get("category");
    subcategory = urlParams.get("subcategory");
    brand = urlParams.get("brand");
    stock_qty = urlParams.get("stock_qty");
    item_name = urlParams.get("item_name");
    total_sales = urlParams.get("total_sales");
    date_restocked = urlParams.get("date_restocked");
    recent = urlParams.get("recent");

    if (urlParams.has("recent")) {
        document.getElementById("all").classList.remove("active");
        document.getElementById("recent").classList.add("active");
        document.getElementById("filter_button").classList.remove("active");
        document.getElementById("category_title").textContent = 'New';
    } else if ([...urlParams].length >= 1) {
        document.getElementById("all").classList.remove("active");
        document.getElementById("recent").classList.remove("active");
        document.getElementById("filter_button").classList.add("active");
        const form = document.getElementById("filter_form");
        const formData = new FormData(form);
        formData.set("category", urlParams.get("category"));
        formData.set("subcategory", urlParams.get("subcategory"));
        formData.set("brand", urlParams.get("brand"));
        formData.set("stock_qty", urlParams.get("stock_qty"));
        formData.set("item_name", urlParams.get("item_name"));
        formData.set("total_sales", urlParams.get("total_sales"));
        formData.set("date_restocked", urlParams.get("date_restocked"));

        if (urlParams.has("category")) {
            document.getElementById("category_title").textContent = urlParams.get("category");
        } else if (urlParams.has("category") && urlParams.has("subcategory")) {
            document.getElementById("category_title").textContent = `${urlParams.get("category")} > ${urlParams.get("subcategory")}`;
        } else if (urlParams.has("subcategory")) {
            document.getElementById("category_title").textContent = urlParams.get("subcategory");
        } else {
            document.getElementById("category_title").textContent = 'All';
        }
    } else if (urlParams.toString() === "") {
        document.getElementById("all").classList.add("active");
        document.getElementById("recent").classList.remove("active");
        document.getElementById("filter_button").classList.remove("active");

        document.getElementById("category_title").textContent = 'All';
    }

    loadPage(1, true);
});

