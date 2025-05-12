// Sidebar

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

if(sidebarMobileButton){
    sidebarMobileButton.addEventListener("click", function (e) {
        e.stopPropagation();
        sidebar.classList.replace("-left-full", "left-0");
    });
}


// Format CPF

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


// Format CNPJ

function formatCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, '');

    cnpj = cnpj.substring(0, 14);

    cnpj = cnpj.replace(/^(\d{2})(\d)/, '$1.$2');
    cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
    cnpj = cnpj.replace(/\.(\d{3})(\d)/, '.$1/$2');
    cnpj = cnpj.replace(/(\d{4})(\d)/, '$1-$2');

    return cnpj;
}

const cnpjInput = document.querySelector('[name="cnpj"]');
if (cnpjInput) {
    cnpjInput.addEventListener('input', function(e) {
        e.target.value = formatCNPJ(e.target.value);
    });
}


// Filter Modal

const triggerFilterModal = document.querySelector(
    '[data-role="filter-modal-trigger"]'
);

const filterModal = document.querySelector('[data-role="filter-modal"]');

if(filterModal){
    body.addEventListener("click", function (event) {
        if (event.target === filterModal) {
            filterModal.classList.replace("flex", "hidden");
        }
    });
}

if(triggerFilterModal && filterModal){
    triggerFilterModal.addEventListener("click", function () {
        filterModal.classList.replace("hidden", "flex");
    });
}

