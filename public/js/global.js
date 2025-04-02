const sidebarMobileButton = document.querySelector(
    '[data-role="sidebar-mobile-button"]'
);

const body = document.querySelector("body");
const sidebar = document.querySelector("#sidebar");

body.addEventListener("click", function (event) {
    if (!sidebar.contains(event.target)) {
        sidebar.classList.replace("left-0", "-left-full");
    }
});

sidebarMobileButton.addEventListener("click", function (e) {
    e.stopPropagation();
    sidebar.classList.replace("-left-full", "left-0");
});
