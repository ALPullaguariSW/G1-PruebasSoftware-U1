const {
    validarNombre,
    validarCorreo,
    validarContraseña,
    validarConfirmacion
} = require("../../js/jstest/funcionesValidaciones");

describe("Validaciones", () => {
    test("Nombre no debe estar vacío", () => {
        expect(validarNombre("")).toBe(false);
        expect(validarNombre("Juan")).toBe(true);
    });

    test("Correo debe tener formato válido", () => {
        expect(validarCorreo("correo@dominio.com")).toBe(true);
        expect(validarCorreo("malcorreo")).toBe(false);
    });

    test("Contraseña debe tener mínimo 5 caracteres", () => {
        expect(validarContraseña("a$1")).toMatch(/al menos 5/);
    });

    test("Contraseña debe tener letra minúscula", () => {
        expect(validarContraseña("12345$")).toMatch(/minúscula/);
    });

    test("Contraseña debe tener carácter especial", () => {
        expect(validarContraseña("abcde")).toMatch(/carácter especial/);
    });

    test("Contraseña válida pasa sin errores", () => {
        expect(validarContraseña("abc$12")).toBe(null);
    });

    test("Confirmación debe coincidir", () => {
        expect(validarConfirmacion("abc", "abc")).toBe(true);
        expect(validarConfirmacion("abc", "def")).toBe(false);
    });
});
