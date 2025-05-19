const initiallyCheckedDepartmentScopes = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'));
const departmentscopes = Array.from(document.querySelectorAll('input[type="checkbox"]'));

function resetDepartmentScopes(){
    departmentscopes.forEach(item => {
        item.checked = false;
    });

    initiallyCheckedDepartmentScopes.forEach(item => {
        item.checked = true;
    });
}

function checkAllDepartmentScopes(){
    departmentscopes.forEach(item => {
        item.checked = true;
    });
}

function uncheckAllDepartmentScopes(){
    departmentscopes.forEach(item => {
        item.checked = false;
    });
}
