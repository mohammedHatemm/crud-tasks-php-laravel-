document.addEventListener("DOMContentLoaded", function () {
    const categoryLinks = document.querySelectorAll(".category-list a");
    const dateLinks = document.querySelectorAll(".date-filter a");
    const searchForm = document.querySelector(".search-form");
    const newsContainer = document.querySelector("#news-container");
    const clearFiltersBtn = document.querySelector("#clear-filters");

    categoryLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const url = new URL(this.href, window.location.origin);
            const categoryId = url.searchParams.get("category");

            if (this.textContent.trim() === "جميع الأخبار") {
                updateNewsWithFilters({ category: null });
            } else if (categoryId) {
                updateNewsWithFilters({ category: categoryId });
            }
        });
    });

    dateLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            const url = new URL(this.href, window.location.origin);
            const date = url.searchParams.get("date");

            updateNewsWithFilters({ date: date });
        });
    });


    if (searchForm) {
        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const searchInput = this.querySelector('input[name="search"]');
            updateNewsWithFilters({ search: searchInput.value });
        });
    }


    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function (e) {
            e.preventDefault();
            updateNewsWithFilters({ category: null, date: null, search: null });
        });
    }


    function updateNewsWithFilters(newFilter) {

        const urlParams = new URLSearchParams(window.location.search);


        for (const [key, value] of Object.entries(newFilter)) {
            if (value && value !== "null" && value !== null) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
        }


        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;


        window.history.pushState({}, "", newUrl);


        if (newsContainer) {
            newsContainer.innerHTML =
                '<div class="text-center py-8"><div class="animate-pulse">جاري التحميل...</div></div>';
        }


        fetch(`${window.location.pathname}?${urlParams.toString()}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then((data) => {

                if (newsContainer && data.html) {
                    newsContainer.innerHTML = data.html;
                }


                updateActiveFilters(urlParams);


                updateActiveFiltersSection(urlParams);
            })
            .catch((error) => {
                console.error("Error fetching filtered news:", error);


                window.location.href = newUrl;
            });
    }


    function updateActiveFilters(urlParams) {

        categoryLinks.forEach((link) => {
            const url = new URL(link.href, window.location.origin);
            const categoryId = url.searchParams.get("category");

            if (
                urlParams.get("category") === categoryId ||
                (!urlParams.get("category") &&
                    !categoryId &&
                    link.textContent.trim() === "جميع الأخبار")
            ) {
                link.classList.add("active-filter", "font-bold");
            } else {
                link.classList.remove("active-filter", "font-bold");
            }
        });


        dateLinks.forEach((link) => {
            const url = new URL(link.href, window.location.origin);
            const date = url.searchParams.get("date");

            if (
                urlParams.get("date") === date ||
                (!urlParams.get("date") &&
                    !date &&
                    link.textContent.trim() === "الكل")
            ) {
                link.classList.add("active-filter", "font-bold");
            } else {
                link.classList.remove("active-filter", "font-bold");
            }
        });
    }


    function updateActiveFiltersSection(urlParams) {

    }
});
