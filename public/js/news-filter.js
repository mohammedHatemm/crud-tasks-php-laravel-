document.addEventListener("DOMContentLoaded", function () {
    // تحديد عناصر التصفية
    const categoryLinks = document.querySelectorAll(".category-filter a");
    const dateLinks = document.querySelectorAll(".date-filter a");
    const searchForm = document.querySelector(".search-form");
    const newsContainer = document.querySelector("#news-container");
    const clearFiltersBtn = document.querySelector("#clear-filters");

    // إضافة مستمع أحداث لروابط التصنيفات
    categoryLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const categoryId = this.dataset.categoryId;
            updateNewsWithFilters({ category: categoryId });
        });
    });

    // إضافة مستمع أحداث لروابط التاريخ
    dateLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const date = this.dataset.date;
            updateNewsWithFilters({ date: date });
        });
    });

    // إضافة مستمع أحداث لنموذج البحث
    if (searchForm) {
        searchForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const searchInput = this.querySelector('input[name="search"]');
            updateNewsWithFilters({ search: searchInput.value });
        });
    }

    // إضافة مستمع أحداث لزر مسح التصفية
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function (e) {
            e.preventDefault();
            window.location.href = "/"; // إعادة توجيه إلى الصفحة الرئيسية بدون تصفية
        });
    }

    // وظيفة لتحديث الأخبار باستخدام AJAX
    function updateNewsWithFilters(newFilter) {
        // الحصول على المعلمات الحالية من URL
        const urlParams = new URLSearchParams(window.location.search);

        // تحديث المعلمات بناءً على التصفية الجديدة
        for (const [key, value] of Object.entries(newFilter)) {
            if (value) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
        }

        // إنشاء URL جديد مع المعلمات المحدثة
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;

        // تحديث عنوان URL بدون إعادة تحميل الصفحة
        window.history.pushState({}, "", newUrl);

        // إرسال طلب AJAX للحصول على الأخبار المصفاة
        fetch(`/news/filter?${urlParams.toString()}`)
            .then((response) => response.json())
            .then((data) => {
                // تحديث محتوى الأخبار
                newsContainer.innerHTML = data.html;

                // تحديث عناصر التصفية النشطة
                updateActiveFilters(urlParams);
            })
            .catch((error) => {
                console.error("Error fetching filtered news:", error);
            });
    }

    // وظيفة لتحديث عناصر التصفية النشطة
    function updateActiveFilters(urlParams) {
        // تحديث حالة روابط التصنيفات
        categoryLinks.forEach((link) => {
            const categoryId = link.dataset.categoryId;
            if (urlParams.get("category") === categoryId) {
                link.classList.add("active-filter");
            } else {
                link.classList.remove("active-filter");
            }
        });

        // تحديث حالة روابط التاريخ
        dateLinks.forEach((link) => {
            const date = link.dataset.date;
            if (urlParams.get("date") === date) {
                link.classList.add("active-filter");
            } else {
                link.classList.remove("active-filter");
            }
        });

        // تحديث قسم التصفية النشطة
        const activeFiltersContainer =
            document.querySelector("#active-filters");
        if (activeFiltersContainer) {
            // إعادة تحميل الصفحة لتحديث قسم التصفية النشطة
            // يمكن تحسين هذا لاحقًا لتحديث القسم بشكل ديناميكي بدون إعادة تحميل
            window.location.reload();
        }
    }
});
