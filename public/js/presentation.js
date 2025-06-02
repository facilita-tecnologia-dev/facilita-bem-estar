const loginSubmenuButton = document.querySelector(
    '[data-role="login-submenu-button"]'
);
const body = document.querySelector("body");
const loginSubmenu = document.querySelector("#login-submenu");

body.addEventListener("click", function (event) {
    if (loginSubmenu && !loginSubmenu.contains(event.target)) {
        loginSubmenu.classList.replace("right-0", "-right-full");
    }
});

if(loginSubmenuButton){
    loginSubmenuButton.addEventListener("click", function (e) {
        e.stopPropagation();
        loginSubmenu.classList.replace("-right-full", "right-0");
    });
}
