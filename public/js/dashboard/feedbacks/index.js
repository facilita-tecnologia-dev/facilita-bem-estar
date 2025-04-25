const triggerFilterModal = document.querySelector(
    '[data-role="filter-modal-trigger"]'
);

const filterModal = document.querySelector('[data-role="filter-modal"]');

body.addEventListener("click", function (event) {
    if (event.target === filterModal) {
        filterModal.classList.replace("flex", "hidden");
    }
});

triggerFilterModal.addEventListener("click", function () {
    filterModal.classList.replace("hidden", "flex");
});