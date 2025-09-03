// Activation/Désactivation du champ "Code" selon rôle
document.getElementById('role').addEventListener('change', function() {
    let codeInput = document.getElementById('code');
    if (this.value === 'admin' || this.value === 'superadmin') {
        codeInput.disabled = false; 
    } else {
        codeInput.disabled = true;  
        codeInput.value = '';       
    }
});



// Vérification des mots de passe
$(document).ready(function () {
    function validatePasswordCriteria(password) {
        let hasNumber = /\d/.test(password);
        return password.length >= 8 && hasNumber;
    }

    function checkPasswords() {
        let pwd = $("#password").val();
        let confirmPwd = $("#confirm").val();
        let iconPwd = $("#password-icon");
        let iconConfirm = $("#confirm-icon");
        let errorPwd = $("#password-error");
        let errorConfirm = $("#confirm-error");

        iconPwd.removeClass();
        iconConfirm.removeClass();
        errorPwd.text("");
        errorConfirm.text("");

        if (pwd.length > 0) {
            if (validatePasswordCriteria(pwd)) {
                iconPwd.addClass("fas fa-check text-success");
            } else {
                iconPwd.addClass("fas fa-times text-danger");
                errorPwd.text("Le mot de passe doit contenir au moins 8 caractères et un chiffre.");
            }
        }

        if (confirmPwd.length > 0) {
            if (validatePasswordCriteria(pwd) && pwd === confirmPwd) {
                iconConfirm.addClass("fas fa-check text-success");
            } else {
                iconConfirm.addClass("fas fa-times text-danger");
                errorConfirm.text("Les mots de passe ne correspondent pas.");
            }
        }
    }

    $("#password, #confirm").on("keyup blur", checkPasswords);
});

