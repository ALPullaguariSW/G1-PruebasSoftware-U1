// js/auth_validations.js
document.addEventListener("DOMContentLoaded", function () {
    const formRegistro = document.getElementById("formRegistro");

    if (formRegistro) {
        const passwordInput = formRegistro.contraseña;
        const confirmPasswordInput = formRegistro.confirm_contraseña; // Asumiendo que añades este campo
        const nombreInput = formRegistro.nombre;
        const correoInput = formRegistro.correo;
        
        // Password strength indicator (opcional)
        // const strengthIndicator = document.createElement('div');
        // strengthIndicator.className = 'password-strength';
        // passwordInput.parentNode.insertBefore(strengthIndicator, passwordInput.nextSibling);

        // function updateStrengthIndicator() {
        //     const password = passwordInput.value;
        //     let strength = 0;
        //     if (password.length >= 5) strength++;
        //     if (password.match(/[a-z]/)) strength++;
        //     if (password.match(/[A-Z]/)) strength++; // Mayúscula
        //     if (password.match(/[0-9]/)) strength++; // Número
        //     if (password.match(/[^A-Za-z0-9]/)) strength++; // Especial
            
        //     strengthIndicator.textContent = '';
        //     if (password.length > 0) {
        //         if (strength < 2) strengthIndicator.className = 'password-strength weak';
        //         else if (strength < 4) strengthIndicator.className = 'password-strength medium';
        //         else strengthIndicator.className = 'password-strength strong';
        //         strengthIndicator.textContent = strength < 2 ? 'Débil' : (strength < 4 ? 'Media' : 'Fuerte');
        //     }
        // }
        // if (passwordInput) passwordInput.addEventListener('input', updateStrengthIndicator);


        formRegistro.addEventListener("submit", function (e) {
            let errors = [];
            
            // Nombre: no vacío
            if (nombreInput.value.trim() === "") {
                errors.push("El nombre es obligatorio.");
            }

            // Correo: formato válido
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(correoInput.value.trim())) {
                errors.push("El correo electrónico no es válido.");
            }

            // Contraseña
            const password = passwordInput.value;
            const regexMinuscula = /[a-z]/;
            const regexEspecial = /[^A-Za-z0-9]/; // Al menos un no alfanumérico

            if (password.length < 5) {
                errors.push("La contraseña debe tener al menos 5 caracteres.");
            } else if (!regexMinuscula.test(password)) {
                errors.push("La contraseña debe contener al menos una letra minúscula.");
            } else if (!regexEspecial.test(password)) {
                errors.push("La contraseña debe contener al menos un carácter especial.");
            }
            // (Opcional: añadir validación de mayúscula, número si se desea)

            // Confirmación de contraseña
            if (confirmPasswordInput && password !== confirmPasswordInput.value) {
                errors.push("Las contraseñas no coinciden.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                displayValidationMessage(errors.join("<br>"), "error", formRegistro);
            } else {
                // Limpiar mensajes si todo está bien (aunque el form se enviará)
                clearValidationMessage(formRegistro);
            }
        });
    }

    function displayValidationMessage(text, type, form) {
        let messageDiv = form.querySelector(".validation-message-client");
        if (!messageDiv) {
            messageDiv = document.createElement("div");
            messageDiv.className = "validation-message-client";
            // Insertar antes del primer botón o al final del form-group
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                 form.insertBefore(messageDiv, submitButton.closest('.form-group') || submitButton);
            } else {
                form.appendChild(messageDiv);
            }
        }
        messageDiv.innerHTML = text; // Usar innerHTML para <br>
        messageDiv.className = "validation-message-client message message-" + type; // Reutiliza estilos de .message
        messageDiv.style.marginBottom = "1rem"; // Espacio antes del botón
    }
    
    function clearValidationMessage(form) {
        const messageDiv = form.querySelector(".validation-message-client");
        if (messageDiv) {
            messageDiv.remove();
        }
    }
});