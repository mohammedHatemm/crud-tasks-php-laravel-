import "./bootstrap";

import Alpine from "alpinejs";
import "./news-filter";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-children").forEach((button) => {
        button.addEventListener("click", function () {
            const categoryId = this.dataset.categoryId;
            const childrenContainer = document.getElementById(
                `children-${categoryId}`
            );
            const folderIcon = this.querySelector(`.folder-icon-${categoryId}`);
            const parentItem = this.closest(".category-item");

            if (
                childrenContainer.style.maxHeight === "0px" ||
                childrenContainer.style.maxHeight === ""
            ) {
                childrenContainer.style.maxHeight =
                    childrenContainer.scrollHeight + "px";
                folderIcon.classList.add("folder-icon-rotated");
                parentItem.classList.add("expanded");
            } else {
                childrenContainer.style.maxHeight = "0px";
                folderIcon.classList.remove("folder-icon-rotated");
                parentItem.classList.remove("expanded");
            }
        });
    });

    document
        .querySelectorAll('input[name="categories[]"]')
        .forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                const categoryItem = this.closest(".category-item");
                if (this.checked) {
                    categoryItem.classList.add("selected");
                } else {
                    categoryItem.classList.remove("selected");
                }
            });
        });

    const categoriesSection =
        document.querySelector(".category-list").parentElement;
    const controlsDiv = document.createElement("div");
    controlsDiv.className = "flex justify-end mb-2";
    controlsDiv.innerHTML = `
        <button type="button" id="expand-all" class="text-xs bg-white-200 hover:bg-white-300 text-gray-800 font-semibold py-1 px-2 rounded-l">
            توسيع الكل
        </button>
        <button type="button" id="collapse-all" class="text-xs bg-white-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-2 rounded-r border-l border-gray-300">
            طي الكل
        </button>
    `;
    categoriesSection.prepend(controlsDiv);

    document
        .getElementById("expand-all")
        .addEventListener("click", function () {
            document
                .querySelectorAll(".children-container")
                .forEach((container) => {
                    container.style.maxHeight = container.scrollHeight + "px";
                });
            document
                .querySelectorAll(
                    '.folder-icon-rotated, [class*="folder-icon-"]'
                )
                .forEach((icon) => {
                    icon.classList.add("folder-icon-rotated");
                });
        });

    document
        .getElementById("collapse-all")
        .addEventListener("click", function () {
            document
                .querySelectorAll(".children-container")
                .forEach((container) => {
                    container.style.maxHeight = "0px";
                });
            document
                .querySelectorAll(".folder-icon-rotated")
                .forEach((icon) => {
                    icon.classList.remove("folder-icon-rotated");
                });
        });
});
