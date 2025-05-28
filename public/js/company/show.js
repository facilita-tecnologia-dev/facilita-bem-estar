document.addEventListener('DOMContentLoaded', function () {
    const triggerDeleteCompanyModal = document.querySelector(
        '[data-role="delete-company-modal-trigger"]'
    );
    
    const deleteCompanyModal = document.querySelector('[data-role="delete-company-modal"]');
    const openModal = localStorage.getItem('open-delete-company-modal');

    if (openModal) {
        showDeleteCompanyModal(deleteCompanyModal);         
    }
    
    if(deleteCompanyModal){
        body.addEventListener("click", function (event) {
            if (event.target === deleteCompanyModal) {
                hideDeleteCompanyModal(deleteCompanyModal);
                localStorage.removeItem('open-delete-company-modal');
            }
        });
    }
    
    if(triggerDeleteCompanyModal && deleteCompanyModal){
        triggerDeleteCompanyModal.addEventListener("click", function () {
            showDeleteCompanyModal(deleteCompanyModal);
            localStorage.setItem('open-delete-company-modal', true);
        });
    }
});

function showDeleteCompanyModal(deleteCompanyModal){
    deleteCompanyModal.classList.replace("hidden", "flex");
}

function hideDeleteCompanyModal(deleteCompanyModal){
    deleteCompanyModal.classList.replace("flex", "hidden");
}


