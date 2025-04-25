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


function formatCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    
    cpf = cpf.substring(0, 11);
    
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    
    return cpf;
}

const cpfInput = document.querySelector('[name="cpf"]');
if(cpfInput){

    cpfInput.addEventListener('input', function(e) {
        e.target.value = formatCPF(e.target.value);
    });
}