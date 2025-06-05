console.log('oidpasid')

const passwordWarningModal =  document.querySelector(
    '[data-role="password-warning-modal"]'
);

document.addEventListener('DOMContentLoaded', function () {
    if(passwordWarningModal){
        body.addEventListener("click", function (event) {
            if (event.target === passwordWarningModal) {
                hidePasswordWarningModal();
            }
        });
    }
});

function hidePasswordWarningModal(){
    passwordWarningModal.classList.replace('!flex', 'hidden');    

    // console.log(modal)
}


//     // const triggerResetPasswordModal = document.querySelector(
//     //     '[data-role="reset-password-modal-trigger"]'
//     // );

//     const passwordWarningModal = document.querySelector('[data-role="password-warning-modal"]');


//     if(passwordWarningModal){
//         body.addEventListener("click", function (event) {
//             if (event.target === passwordWarningModal) {
//                 passwordWarningModal.classList.replace('flex', 'hidden');
//             }
//         });
//     }


