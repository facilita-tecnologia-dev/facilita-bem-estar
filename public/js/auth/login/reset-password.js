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
