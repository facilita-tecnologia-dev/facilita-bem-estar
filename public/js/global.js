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



// Password Requirement Checks

function checkPasswordSteps(event){
    const password = event.currentTarget.value; 

    const lengthRequirement = password.length >= 8;
    const uppercaseRequirement = /[A-Z]/.test(password);
    const numberRequirement = /[0-9]/.test(password);
    const specialCharRequirement = /[!@#$%^&*(),.?":{}|<>_\-+=~`[\]\\\/]/.test(password);

    updatePasswordRequirement('length-requirement', lengthRequirement)
    updatePasswordRequirement('uppercase-requirement', uppercaseRequirement)
    updatePasswordRequirement('number-requirement', numberRequirement)
    updatePasswordRequirement('special-char-requirement', specialCharRequirement)
}

function updatePasswordRequirement(requirementId, satisfied){
    const requirement = document.getElementById(requirementId);
    
    const requirementBar = requirement.querySelector('.requirement-bar');
    const iconChecked = requirement.querySelector('.checked-icon');
    const iconUnchecked = requirement.querySelector('.unchecked-icon');

    if (satisfied) {
        requirementBar.classList.replace('bg-red-500', 'bg-green-500');
        iconUnchecked.classList.replace('block', 'hidden');
        iconChecked.classList.replace('hidden', 'block');
    } else {
        requirementBar.classList.replace('bg-green-500', 'bg-red-500');
        iconChecked.classList.replace('block', 'hidden');
        iconUnchecked.classList.replace('hidden', 'block');
    }
}


// Logout modal

document.addEventListener('DOMContentLoaded', function () {
    const triggerLogoutModal = document.querySelector(
        '[data-role="logout-modal-trigger"]'
    );

    const logoutModal = document.querySelector('[data-role="logout-modal"]');
    const openModal = localStorage.getItem('open-logout-modal');

    if (openModal) {
        showLogoutModal(logoutModal);         
    }


    if(logoutModal){
        body.addEventListener("click", function (event) {
            if (event.target === logoutModal) {
                hideLogoutModal(logoutModal);
                localStorage.removeItem('open-logout-modal');   
            }
        });
    }

    if(triggerLogoutModal && logoutModal){
        triggerLogoutModal.addEventListener("click", function () {
            showLogoutModal(logoutModal);
            localStorage.setItem('open-logout-modal', true);
        });
    }
});

function showLogoutModal(logoutModal){
    logoutModal.classList.replace("hidden", "flex");
}

function hideLogoutModal(logoutModal){
    logoutModal.classList.replace("flex", "hidden");
}