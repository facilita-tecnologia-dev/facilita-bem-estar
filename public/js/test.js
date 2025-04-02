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
