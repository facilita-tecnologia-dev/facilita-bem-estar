const offset = 20;
const windowWidth = window.innerWidth;
const checklistElement = document.getElementById('next-steps-checklist');

const checklistTrigger = document.querySelector('[data-role="next-steps-checklist-trigger"]');

document.addEventListener('mousemove', function(event) {
    const isOnRightLimit = event.clientX >= windowWidth - offset;

    if(isOnRightLimit && checklistElement) {
        showChecklistElement(checklistElement);
    }
})

body.addEventListener("click", function (event) {
    if (checklistElement && !checklistElement.contains(event.target)) {
        hideChecklistElement(checklistElement);
    }
});

document.addEventListener("DOMContentLoaded", function() {
    if(checklistTrigger){
        checklistTrigger.addEventListener('click', function(e){
            e.stopPropagation();
            showChecklistElement(checklistElement);
        }); 
    }
});


function showChecklistElement(checklistElement){
    checklistElement.classList.replace('-right-full', 'right-0');
}

function hideChecklistElement(checklistElement){
    checklistElement.classList.replace('right-0', '-right-full');
}