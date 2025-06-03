<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../funcionalidadesRegistro.php';

class funcionalidadesRegistroTest extends TestCase {
    private $conn;
    private $registro;

    protected function setUp(): void {
        $this->conn = new mysqli("localhost", "root", "", "reservas");
        if ($this->conn->connect_error) {
            $this->markTestSkipped("No se puede conectar a la base de datos para pruebas");
        }
        $this->conn->query("CREATE TEMPORARY TABLE usuarios (
            id INT PRIMARY KEY AUTO_INCREMENT,
            nombre VARCHAR(100),
            correo VARCHAR(255),
            contraseña VARCHAR(255)
        )");

        $this->registro = new funcionalidadesRegistro($this->conn);
    }

    protected function tearDown(): void {
        $this->conn->close();
    }

    public function testRegistroExitoso() {
        $res = $this->registro->registrar("Juan Pérez", "juan@example.com", "abcde!", "abcde!");
        $this->assertTrue($res);
        $this->assertEquals("¡Registro exitoso!", $this->registro->mensaje);
        $this->assertEquals("success", $this->registro->claseMensaje);
    }

    public function testRegistroCorreoDuplicado() {
        // Insertar correo existente
        $this->conn->query("INSERT INTO usuarios (nombre, correo, contraseña) VALUES ('Maria', 'maria@example.com', '1234')");
        $res = $this->registro->registrar("Otro", "maria@example.com", "abcde!", "abcde!");
        $this->assertFalse($res);
        $this->assertStringContainsString("ya está registrado", $this->registro->mensaje);
    }

    public function testRegistroContrasenasNoCoinciden() {
        $res = $this->registro->registrar("Ana", "ana@example.com", "abcde!", "abcd!");
        $this->assertFalse($res);
        $this->assertEquals("Las contraseñas no coinciden.", $this->registro->mensaje);
    }

    public function testRegistroContrasenaInvalida() {
        $res = $this->registro->registrar("Pedro", "pedro@example.com", "ABCDE", "ABCDE");
        $this->assertFalse($res);
        $this->assertEquals("La contraseña debe contener al menos una letra minúscula.", $this->registro->mensaje);
    }

    public function testRegistroCorreoInvalido() {
        $res = $this->registro->registrar("Luis", "no-es-correo", "abcde!", "abcde!");
        $this->assertFalse($res);
        $this->assertEquals("El formato del correo electrónico no es válido.", $this->registro->mensaje);
    }
}
