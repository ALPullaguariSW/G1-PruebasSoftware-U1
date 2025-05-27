document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formRegistro");
    form.addEventListener("submit", function (e) {
        const nombre = form.nombre.value.trim();
        const correo = form.correo.value.trim();
        const contraseña = form.contraseña.value;

        // Validación de contraseña: mínimo 5 caracteres, una minúscula y un caracter especial
        const regexMinuscula = /[a-z]/;
        const regexEspecial = /[^A-Za-z0-9]/;

        let mensaje = "";

        if (contraseña.length < 5) {
            mensaje = "La contraseña debe tener al menos 5 caracteres.";
        } else if (!regexMinuscula.test(contraseña)) {
            mensaje = "La contraseña debe contener al menos una letra minúscula.";
        } else if (!regexEspecial.test(contraseña)) {
            mensaje = "La contraseña debe contener al menos un carácter especial.";
        }

        if (mensaje) {
            e.preventDefault();
            mostrarMensaje(mensaje, "error");
        }
    });

    function mostrarMensaje(texto, tipo) {
        let mensajeDiv = document.querySelector(".mensaje");
        if (!mensajeDiv) {
            mensajeDiv = document.createElement("p");
            mensajeDiv.className = "mensaje";
            form.parentNode.insertBefore(mensajeDiv, form);
        }
        mensajeDiv.textContent = texto;
        mensajeDiv.className = "mensaje " + tipo;
    }
});