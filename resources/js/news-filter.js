document.addEventListener("DOMContentLoaded", function () {
    // تحديد عناصر التصفية - تم إصلاح selectors
    const categoryLinks = document.querySelectorAll(".category-list a"); // تم تغيير selector
    const dateLinks = document.querySelectorAll(".date-filter a");
    const searchForm = document.querySelector(".search-form");
    const newsContainer = document.querySelector("#news-container");
    const clearFiltersBtn = document.querySelector("#clear-filters");

    // إضافة مستمع أحداث لروابط التصنيفات
    categoryLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // استخراج category ID من URL بدلاً من data attribute
            const url = new URL(this.href, window.location.origin);
            const categoryId = url.searchParams.get("category");

            // إذا كان الرابط "جميع الأخبار" فلا نمرر category
            if (this.textContent.trim() === "جميع الأخبار") {
                updateNewsWithFilters({ category: null });
            } else if (categoryId) {
                updateNewsWithFilters({ category: categoryId });
            }
        });
    });

    // إضافة مستمع أحداث لروابط التاريخ
    dateLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault();

            // استخراج التاريخ من URL
            const url = new URL(this.href, window.location.origin);
            const date = url.searchParams.get("date");

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
            // بدلاً من window.location.href، استخدم updateNewsWithFilters
            updateNewsWithFilters({ category: null, date: null, search: null });
        });
    }

    // وظيفة لتحديث الأخبار باستخدام AJAX
    function updateNewsWithFilters(newFilter) {
        // الحصول على المعلمات الحالية من URL
        const urlParams = new URLSearchParams(window.location.search);

        // تحديث المعلمات بناءً على التصفية الجديدة
        for (const [key, value] of Object.entries(newFilter)) {
            if (value && value !== "null" && value !== null) {
                urlParams.set(key, value);
            } else {
                urlParams.delete(key);
            }
        }

        // إنشاء URL جديد مع المعلمات المحدثة
        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;

        // تحديث عنوان URL بدون إعادة تحميل الصفحة
        window.history.pushState({}, "", newUrl);

        // إظهار loading indicator
        if (newsContainer) {
            newsContainer.innerHTML =
                '<div class="text-center py-8"><div class="animate-pulse">جاري التحميل...</div></div>';
        }

        // إرسال طلب AJAX للحصول على الأخبار المصفاة
        // تأكد من أن route 'news.home' يقبل AJAX requests
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
                // تحديث محتوى الأخبار
                if (newsContainer && data.html) {
                    newsContainer.innerHTML = data.html;
                }

                // تحديث عناصر التصفية النشطة
                updateActiveFilters(urlParams);

                // تحديث active filters section إذا كان موجوداً
                updateActiveFiltersSection(urlParams);
            })
            .catch((error) => {
                console.error("Error fetching filtered news:", error);

                // في حالة فشل AJAX، قم بإعادة تحميل الصفحة
                window.location.href = newUrl;
            });
    }

    // وظيفة لتحديث عناصر التصفية النشطة
    function updateActiveFilters(urlParams) {
        // تحديث حالة روابط التصنيفات
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

        // تحديث حالة روابط التاريخ
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

    // وظيفة لتحديث قسم الفلاتر النشطة
    function updateActiveFiltersSection(urlParams) {
        // يمكنك إضافة منطق هنا لتحديث active filters section
        // أو ترك هذا للصفحة التالية التي سيتم تحميلها
    }
});
