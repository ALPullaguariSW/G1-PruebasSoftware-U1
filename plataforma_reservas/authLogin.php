<?php
require_once "includes/db.php";

function validarLogin($correo, $contraseña, $conn) {
    if (empty($correo) || empty($contraseña)) {
        return ['exito' => false, 'mensaje' => 'Por favor, complete todos los campos.'];
    }

    $sql = "SELECT id, nombre, contraseña, rol FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ['exito' => false, 'mensaje' => 'Error del sistema. Intente más tarde.'];
    }
    
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows !== 1) {
        $stmt->close();
        return ['exito' => false, 'mensaje' => 'Correo o contraseña incorrecta.'];
    }

    $usuario = $resultado->fetch_assoc();
    $stmt->close();

    if (password_verify($contraseña, $usuario["contraseña"])) {
        return [
            'exito' => true,
            'usuario' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'rol' => $usuario['rol']
            ]
        ];
    } else {
        return ['exito' => false, 'mensaje' => 'Correo o contraseña incorrecta.'];
    }
}
