// Sidebar

const sidebarMobileButton = document.querySelector(
    '[data-role="sidebar-mobile-button"]'
);

const body = document.querySelector("body");
const sidebar = document.querySelector("#sidebar");

body.addEventListener("click", function (event) {
    if (sidebar && !sidebar.contains(event.target)) {
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

// LGPD Bar

const LGPDBar = document.querySelector('[data-role="lgpd-bar"]');
const LGPDAcceptButton = LGPDBar?.querySelector('button');


function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}

function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
}

document.addEventListener("DOMContentLoaded", function() {
    if (!getCookie('lgpd_consent')) {
        LGPDBar.style.display = 'flex';
    }

    if(LGPDAcceptButton){    
        LGPDAcceptButton.addEventListener('click', function() {
            setCookie('lgpd_consent', '1', 30);
            LGPDBar.style.display = 'none';
        });
    }
});