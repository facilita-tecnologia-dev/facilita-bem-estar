document.addEventListener("DOMContentLoaded", function () {
    // Seleciona todos os checkboxes que não podem ser desmarcados
    const checkboxes = document.querySelectorAll('[data-role="no-uncheck"]');

    checkboxes.forEach((checkbox) => {
        // Adiciona um evento de change para prevenir a desmarcação
        checkbox.addEventListener("change", function (e) {
            if (!this.checked) {
                e.preventDefault();
                this.checked = true;
            }
        });
    });
});
