const initiallyCheckedPermissions = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'));
const permissions = Array.from(document.querySelectorAll('input[type="checkbox"]'));

function resetPermissions(){
    permissions.forEach(permission => {
        permission.checked = false;
    });

    initiallyCheckedPermissions.forEach(permission => {
        permission.checked = true;
    });
}

function checkAllPermissions(){
    permissions.forEach(permission => {
        permission.checked = true;
    });
}

function uncheckAllPermissions(){
    permissions.forEach(permission => {
        permission.checked = false;
    });
}
