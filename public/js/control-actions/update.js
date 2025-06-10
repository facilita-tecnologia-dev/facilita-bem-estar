const addControlActionModal = document.querySelector('[data-role="add-control-action-modal"]');

document.addEventListener('DOMContentLoaded', function () {
    if(addControlActionModal){
        body.addEventListener("click", function (event) {
            if (event.target === addControlActionModal) {
                hideAddControlActionModal(addControlActionModal);
            }
        });
    }
});

function showAddControlActionModal(){
    addControlActionModal.classList.replace("hidden", "flex");
}

function hideAddControlActionModal(){
    addControlActionModal.classList.replace("flex", "hidden");
}

