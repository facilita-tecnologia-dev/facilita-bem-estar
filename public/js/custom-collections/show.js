const addTestModal = document.querySelector('[data-role="add-test-modal"]');
const addQuestionModal = document.querySelector('[data-role="add-question-modal"]');

document.addEventListener('DOMContentLoaded', function () {
    if(addTestModal){
        body.addEventListener("click", function (event) {
            if (event.target === addTestModal) {
                hideAddTestModal(addTestModal);
            }
        });
    }

    if(addQuestionModal){
        body.addEventListener("click", function (event) {
            if (event.target === addQuestionModal) {
                hideAddQuestionModal(addQuestionModal);
            }
        });
    }
});

function showAddTestModal(){
    addTestModal.classList.replace("hidden", "flex");
}

function hideAddTestModal(){
    addTestModal.classList.replace("flex", "hidden");
}

function showAddQuestionModal(event){
    const test = event.target.dataset.test;

    addQuestionModal.querySelector(['input[name="custom_test_id"]']).value = test;
    addQuestionModal.classList.replace("hidden", "flex");
}

function hideAddQuestionModal(){
    addQuestionModal.classList.replace("flex", "hidden");
}