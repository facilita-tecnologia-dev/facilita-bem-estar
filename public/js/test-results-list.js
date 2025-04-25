const tableHeaders = Array.from(document.querySelectorAll('[data-role="th"]'));
let direcao = [true, true, true, true, true];

function reorderTable(event, column) {
    let tabela = document.querySelector('[data-role="table"]');
    let body = tabela.querySelector('[data-role="tbody"]');
    let rows = Array.from(body.querySelectorAll('[data-role="tr"]'));

    let ordemAscendente = direcao[column];
    direcao[column] = !ordemAscendente;

    rows.sort((a, b) => {
        let cellsA = a.querySelectorAll('[data-role="td"]');
        let cellsB = b.querySelectorAll('[data-role="td"]');

        let cellA, cellB;

        if (column === 3) {
            cellA = parseFloat(cellsA[column].dataset.value) || 0;
            cellB = parseFloat(cellsB[column].dataset.value) || 0;
        } else {
            cellA = cellsA[column].innerText;
            cellB = cellsB[column].innerText;
        }

        if (typeof cellA === "number" && typeof cellB === "number") {
            return ordemAscendente ? cellA - cellB : cellB - cellA;
        } else {
            return ordemAscendente
                ? String(cellA).localeCompare(String(cellB))
                : String(cellB).localeCompare(String(cellA));
        }
    });

    rows.forEach((row) => body.appendChild(row));

    tableHeaders.forEach((item) => {
        const icon = item.querySelector("i");
        if (icon) {
            icon.remove();
        }
    });

    event.target.innerHTML += `<i class="fa-solid fa-arrows-up-down ml-2"></i>`;
}

const triggerFilterModal = document.querySelector(
    '[data-role="filter-modal-trigger"]'
);

const filterModal = document.querySelector('[data-role="filter-modal"]');

body.addEventListener("click", function (event) {
    if (event.target === filterModal) {
        filterModal.classList.replace("flex", "hidden");
    }
});

triggerFilterModal.addEventListener("click", function () {
    filterModal.classList.replace("hidden", "flex");
});
