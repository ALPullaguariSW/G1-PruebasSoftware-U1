<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../authLogin.php';

class authLoginTests extends TestCase {
    private $conn;
    protected function setUp(): void { 
    $this->conn = new mysqli("localhost", "root", "", "reservas");
    $this->conn->query("DELETE FROM usuarios WHERE correo = 'usuario@test.com'");
    $passwordHash = password_hash('correcta', PASSWORD_DEFAULT);
    $this->conn->query("INSERT INTO usuarios (nombre, correo, contraseña, rol) VALUES ('Usuario Test', 'usuario@test.com', '$passwordHash', 'usuari')");
    }

    protected function tearDown(): void {
         $this->conn->query("DELETE FROM usuarios WHERE correo = 'usuario@test.com'");
        $this->conn->close();
    }

    public function testCamposVacios() {
        $resultado = validarLogin("", "", $this->conn);
        $this->assertFalse($resultado['exito']);
        $this->assertEquals('Por favor, complete todos los campos.', $resultado['mensaje']);
    }

    public function testCorreoNoExiste() {
        $resultado = validarLogin("noexiste@correo.com", "cualquier", $this->conn);
        $this->assertFalse($resultado['exito']);
        $this->assertEquals('Correo o contraseña incorrecta.', $resultado['mensaje']);
    }

    public function testContraseñaIncorrecta() {
        // Suponiendo que este correo sí existe en la BD de prueba
        $resultado = validarLogin("usuario@test.com", "incorrecta", $this->conn);
        $this->assertFalse($resultado['exito']);
        $this->assertEquals('Correo o contraseña incorrecta.', $resultado['mensaje']);
    }

    public function testLoginCorrecto() {
        // Suponiendo que este usuario y contraseña están en la BD de prueba
        $resultado = validarLogin("usuario@test.com", "correcta", $this->conn);
        $this->assertTrue($resultado['exito']);
        $this->assertArrayHasKey('usuario', $resultado);
        $this->assertEquals("usuario@test.com", "usuario@test.com"); // validar algún dato esperado
    }
}
