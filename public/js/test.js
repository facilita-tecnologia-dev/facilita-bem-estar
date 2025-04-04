const testQuestions = document.querySelectorAll('[data-role="test-question"]');

testQuestions.forEach((question) => {
    question.addEventListener("change", (event) => {
        if (!event.target.matches('[data-role="option-checkbox"]')) {
            return;
        }

        question
            .querySelectorAll('[data-role="option-icon"]')
            .forEach((icon) => {
                icon.classList.replace("opacity-100", "opacity-0");
            });

        const optionIcon = event.target.parentElement.querySelector(
            '[data-role="option-icon"]'
        );

        optionIcon.classList.replace("opacity-0", "opacity-100");
    });
});

// document.addEventListener("DOMContentLoaded", () => {
//     testQuestions.forEach(function (question, index) {
//         const options = document.querySelectorAll(
//             '[data-role="option-checkbox"]'
//         );

//         const proximaPergunta = question.nextElementSibling;

//         options.forEach((option) => {
//             option.addEventListener("change", () => {
//                 if (option.checked) {
//                     if (index < testQuestions.length - 1) {
//                         if (proximaPergunta) {
//                             // Faz o scroll suave para a próxima pergunta
//                             proximaPergunta.scrollIntoView({
//                                 behavior: "smooth",
//                                 block: "start",
//                             });
//                         }
//                     }
//                 }
//             });
//         });
//     });
// });

function scrollToNextQuestion(event) {
    const questionElement = event.target.closest('[data-role="test-question"]');
    const nextQuestion = questionElement.nextElementSibling;

    if (nextQuestion && nextQuestion.hasAttribute("data-role")) {
        nextQuestion.scrollIntoView({ behavior: "smooth" });
    }
}
