const testsContainer = document.querySelector('[data-role="tests-container"]');
const testSections = document.querySelectorAll('[data-role="test-section"]');
const addTestButton = document.querySelector('[data-role="add-test"]');

document.addEventListener("DOMContentLoaded", function () {
    const questions = document.querySelectorAll('[data-role="question"]');

    testSections.forEach(test => {
        setTestActions(test);
    });

    addTestButton.addEventListener('click', () => {
        const newTestSection = testSections[0].cloneNode(true);
        const question = testSections[0].querySelector('[data-role="question"]');
        const testHeader = newTestSection.querySelector('[data-role="test-header"]');
        const questionsContainer = newTestSection.querySelector('[data-role="questions-container"]');
        const inputTestName = document.createElement('div');

        inputTestName.style.flex = "1";

        inputTestName.innerHTML = `
            <input 
                type="text" 
                placeholder="Digite o nome do teste..."
                class="bg-gray-100 rounded-md p-2 flex-1 text-sm sm:text-base w-full" 
                onChange="updateQuestionsName(event)"
            >
        `;

        testHeader.replaceChild(inputTestName, testHeader.querySelector('h2'));

        const questionInput = question.querySelector('input');

        questionInput.removeAttribute('name');
        questionInput.removeAttribute('id');
        questionInput.removeAttribute('value');

        questionInput.removeAttribute('readonly');

        questionsContainer.innerHTML = '';
        questionsContainer.appendChild(question);

        testsContainer.appendChild(newTestSection);

        setTestActions(newTestSection);
    });
});

function setQuestionActions(question){
    const deleteButton = question.querySelector('[data-role="delete"]');
    const restoreButton = question.querySelector('[data-role="restore"]');

    // restoreButton.setAttribute('disabled', true);
    // restoreButton.style.opacity = '50%';

    deleteButton.addEventListener('click', function() {
        toggleInputState(question, false);
    })
    
    restoreButton.addEventListener('click', function() {
        toggleInputState(question, true);
    })
}

function setTestActions(test){
    const questionsContainer = test.querySelector('[data-role="questions-container"]');
    const testQuestions = Array.from(test.querySelectorAll('[data-role="question"]'));
    const addQuestionButton = test.querySelector('[data-role="add-question"]');
    const questionNode = test.querySelector('[data-role="question"]');

    const restoreTestButton = test.querySelector('[data-role="restore-test"]');
    const deleteTestButton = test.querySelector('[data-role="delete-test"]');

    // restoreTestButton.setAttribute('disabled', true);
    // restoreTestButton.style.opacity = '50%';

    testQuestions.forEach(question => {
        setQuestionActions(question);
    });

    addQuestionButton.addEventListener('click', function(){
        const newQuestion = questionNode.cloneNode(true);
        const input = newQuestion.querySelector('input');
        input.value = '';
        input.removeAttribute('readonly');
        input.setAttribute('onChange', 'trimInput(event)');

        questionsContainer.appendChild(newQuestion);

        setQuestionActions(newQuestion);
        testQuestions.push(newQuestion);
    });

    restoreTestButton.addEventListener('click', function(){
        deleteTestButton.removeAttribute('disabled');
        deleteTestButton.style.opacity = '100%';    
        restoreTestButton.setAttribute('disabled', true);
        restoreTestButton.style.opacity = '50%';

        testQuestions.forEach(question => {
            toggleInputState(question, true);
        });
    }); 

    deleteTestButton.addEventListener('click', function(){        
        restoreTestButton.removeAttribute('disabled');
        restoreTestButton.style.opacity = '100%';    
        deleteTestButton.setAttribute('disabled', true);
        deleteTestButton.style.opacity = '50%';        

        testQuestions.forEach(question => {
            toggleInputState(question, false);
        });
    }); 
}

function toggleInputState(question, isActive){
    const deleteButton = question.querySelector('[data-role="delete"]');
    const restoreButton = question.querySelector('[data-role="restore"]');
    const input = question.querySelector('input');
    
    if(isActive){
        input.removeAttribute('disabled');
        input.style.opacity = '100%';
        deleteButton.removeAttribute('disabled');
        deleteButton.style.opacity = '100%';
        restoreButton.setAttribute('disabled', true);
        restoreButton.style.opacity = '50%';
    } else {
        input.setAttribute('disabled', true);
        input.style.opacity = '50%';
        deleteButton.setAttribute('disabled', true);
        deleteButton.style.opacity = '50%';
        restoreButton.removeAttribute('disabled');
        restoreButton.style.opacity = '100%';
    }
}

function updateQuestionsName(event){
    const parentTestSection = event.currentTarget.closest('[data-role="test-section"]');
    const questions = parentTestSection.querySelectorAll('[data-role="question"]');

    questions.forEach(question => {
        const input = question.querySelector('input');

        input.name = `${event.currentTarget.value}_questions[]`;
    })
}

function trimInput(event){
    event.currentTarget.value = event.currentTarget.value.trim();
}
