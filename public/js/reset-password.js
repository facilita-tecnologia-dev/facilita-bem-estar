document.addEventListener('DOMContentLoaded', function () {
    const triggerResetPasswordModal = document.querySelector(
        '[data-role="reset-password-modal-trigger"]'
    );

    const resetPasswordModal = document.querySelector('[data-role="reset-password-modal"]');
    const openModal = localStorage.getItem('open-reset-password-modal');

    console.log(resetPasswordModal, triggerResetPasswordModal);

    if (openModal) {
        showResetPasswordModal(resetPasswordModal);         
    }


    if(resetPasswordModal){
        body.addEventListener("click", function (event) {
            if (event.target === resetPasswordModal) {
                hideResetPasswordModal(resetPasswordModal);
                localStorage.removeItem('open-reset-password-modal');   
            }
        });
    }

    if(triggerResetPasswordModal && resetPasswordModal){
        triggerResetPasswordModal.addEventListener("click", function () {
            showResetPasswordModal(resetPasswordModal);
            localStorage.setItem('open-reset-password-modal', true);
        });
    }
});

function showResetPasswordModal(resetPasswordModal){
    resetPasswordModal.classList.replace("hidden", "flex");
}

function hideResetPasswordModal(resetPasswordModal){
    resetPasswordModal.classList.replace("flex", "hidden");
}
