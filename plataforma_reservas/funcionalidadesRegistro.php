<?php
class funcionalidadesRegistro {
    private $conn;
    public $mensaje = "";
    public $claseMensaje = "";

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registrar($nombre, $correo, $contraseña, $confirm_contraseña) {
        if (empty($nombre) || empty($correo) || empty($contraseña) || empty($confirm_contraseña)) {
            $this->mensaje = "Todos los campos son obligatorios.";
            $this->claseMensaje = "error";
            return false;
        }
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->mensaje = "El formato del correo electrónico no es válido.";
            $this->claseMensaje = "error";
            return false;
        }
        if (strlen($contraseña) < 5) {
            $this->mensaje = "La contraseña debe tener al menos 5 caracteres.";
            $this->claseMensaje = "error";
            return false;
        }
        if (!preg_match('/[a-z]/', $contraseña)) {
            $this->mensaje = "La contraseña debe contener al menos una letra minúscula.";
            $this->claseMensaje = "error";
            return false;
        }
        if (!preg_match('/[^A-Za-z0-9]/', $contraseña)) {
            $this->mensaje = "La contraseña debe contener al menos un carácter especial.";
            $this->claseMensaje = "error";
            return false;
        }
        if ($contraseña !== $confirm_contraseña) {
            $this->mensaje = "Las contraseñas no coinciden.";
            $this->claseMensaje = "error";
            return false;
        }

        // Verificar si correo existe
        $sql_check = "SELECT id FROM usuarios WHERE correo = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        if (!$stmt_check) {
            $this->mensaje = "Error del sistema. Intente más tarde.";
            $this->claseMensaje = "error";
            return false;
        }
        $stmt_check->bind_param("s", $correo);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $this->mensaje = "El correo electrónico ya está registrado.";
            $this->claseMensaje = "error";
            return false;
        }
        $stmt_check->close();

        $contraseña_hashed = password_hash($contraseña, PASSWORD_DEFAULT);
        $sql_insert = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
        $stmt_insert = $this->conn->prepare($sql_insert);
        if (!$stmt_insert) {
            $this->mensaje = "Error del sistema. Intente más tarde.";
            $this->claseMensaje = "error";
            return false;
        }
        $stmt_insert->bind_param("sss", $nombre, $correo, $contraseña_hashed);
        if (!$stmt_insert->execute()) {
            $this->mensaje = "Error al registrar el usuario.";
            $this->claseMensaje = "error";
            return false;
        }
        $stmt_insert->close();

        $this->mensaje = "¡Registro exitoso!";
        $this->claseMensaje = "success";
        return true;
    }
}
