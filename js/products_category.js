function onSubmitFilter(e) {
    e.preventDefault();
    refreshFilter();
    const form = document.getElementById("filter_form");
    const formData = new FormData(form);
    document.getElementById("all").classList.remove("active");
    document.getElementById("recent").classList.remove("active");
    document.getElementById("filter_button").classList.add("active");
    category = formData.get("category");
    subcategory = formData.get("subcategory");
    brand = formData.get("brand");
    stock_qty = formData.get("stock_qty");
    item_name = formData.get("item_name");
    total_sales = formData.get("total_sales");
    date_restocked = formData.get("date_restocked");
    const newParams = new URLSearchParams();

    ["category", "subcategory", "brand", "stock_qty", "item_name", "total_sales", "date_restocked"].forEach(key => {
        const value = formData.get(key);
        if (value) {
            newParams.set(key, value);
        }
    });
    history.replaceState(null, "", "?" + newParams.toString());
    page = 1;
    const modal = bootstrap.Modal.getInstance(document.getElementById('filterItemModal'));
    modal.hide();
    updateTitle();
    loadPage(1, true);
}


document.getElementById("recent").addEventListener("click", function (event) {
    event.preventDefault();
    document.getElementById("filter_form").reset();
    document.getElementById("recent").classList.add("active");
    document.getElementById("filter_button").classList.remove("active");
    document.getElementById("all").classList.remove("active");
    refreshFilter();
    const newParams = new URLSearchParams(
        {
            recent: "DESC"
        }
    );
    history.replaceState(null, "", "?" + newParams.toString());
    recent = "ASC";
    page = 1;
    updateTitle();
    loadPage(1, true);
});

document.getElementById("all").addEventListener("click", function (event) {
    event.preventDefault();
    document.getElementById("filter_form").reset();
    document.getElementById("recent").classList.remove("active");
    document.getElementById("filter_button").classList.remove("active");
    document.getElementById("all").classList.add("active");
    refreshFilter();
    history.replaceState(null, "", window.location.pathname);
    page = 1;
    updateTitle();
    loadPage(1, true);
});

document.getElementById("prev_button").addEventListener("click", () => {
    if (page > 1) {
        page--;
        loadPage(page, true);
    }
});

document.getElementById("next_button").addEventListener("click", () => {
    if (page < totalPages) {
        page++;
        loadPage(page, true);
    }
});

function updateTitle() {
    const urlParams = new URLSearchParams(window.location.search);
    console.log(urlParams);
    if (urlParams.has("recent")) {
        document.getElementById("category_title").textContent = 'New';
    } else if ([...urlParams].length >= 1) {
        if (urlParams.has("category") && urlParams.has("subcategory")) {
            document.getElementById("category_title").textContent = `${urlParams.get("category")} > ${urlParams.get("subcategory")}`;
        } else
            if (urlParams.has("category")) {
                document.getElementById("category_title").textContent = urlParams.get("category");
            } else if (urlParams.has("subcategory")) {
                document.getElementById("category_title").textContent = `All > ${urlParams.get("subcategory")}`;
            } else {
                document.getElementById("category_title").textContent = 'All';
            }
    } else if ([...urlParams].length === 0) {
        document.getElementById("category_title").textContent = 'All';
    }
}

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
        const fields = ["category", "subcategory", "brand", "stock_qty", "item_name", "total_sales", "date_restocked"];
        fields.forEach(key => {
            const value = urlParams.get(key);
            const input = form.querySelector(`[name="${key}"]`);
            if (input && value !== null) {
                input.value = value;
            }
        });

        if (urlParams.has("category") && urlParams.has("subcategory")) {
            document.getElementById("category_title").textContent = `${urlParams.get("category")} > ${urlParams.get("subcategory")}`;
        } else
            if (urlParams.has("category")) {
                document.getElementById("category_title").textContent = urlParams.get("category");
            } else if (urlParams.has("subcategory")) {
                document.getElementById("category_title").textContent = urlParams.get("subcategory");
            } else {
                document.getElementById("category_title").textContent = 'All';
            }
    } else if ([...urlParams].length === 0) {
        document.getElementById("all").classList.add("active");
        document.getElementById("recent").classList.remove("active");
        document.getElementById("filter_button").classList.remove("active");

        document.getElementById("category_title").textContent = 'All';
    }

    loadPage(1, true);
});

