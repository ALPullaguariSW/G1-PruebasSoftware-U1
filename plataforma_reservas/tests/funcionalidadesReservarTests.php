<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../funcionalidadesReservar.php';

class funcionalidadesReservarTests extends TestCase {
    private $conn;
    private $funcionalidadesReservar;

    protected function setUp(): void {
        $this->conn = new mysqli("localhost", "root", "", "reservas"); 
        if ($this->conn->connect_error) {
            $this->markTestSkipped("No se puede conectar a la base de datos para pruebas");
        }
        $this->funcionalidadesReservar = new funcionalidadesReservar($this->conn);

        $this->conn->query("CREATE TEMPORARY TABLE habitaciones (
            id INT PRIMARY KEY AUTO_INCREMENT,
            tipo VARCHAR(50),
            numero VARCHAR(10),
            descripcion TEXT,
            servicios TEXT,
            imagen VARCHAR(255),
            precio DECIMAL(10,2)
        )");

        $this->conn->query("CREATE TEMPORARY TABLE reservas (
            id INT PRIMARY KEY AUTO_INCREMENT,
            usuario_id INT,
            habitacion_id INT,
            fecha_inicio DATE,
            fecha_fin DATE
        )");

        $this->conn->query("INSERT INTO habitaciones (tipo, numero, descripcion, servicios, imagen, precio) VALUES 
            ('Simple', '101', 'Habitación simple', 'WiFi', '', 50.00),
            ('Doble', '102', 'Habitación doble', 'WiFi, TV', '', 80.00)");
    }

    protected function tearDown(): void {
        $this->conn->close();
    }

    public function testConsultarDisponibilidadConFechasValidas() {
        $resultado = $this->funcionalidadesReservar->consultarDisponibilidad('2025-06-10', '2025-06-12', 'Simple');
        $this->assertIsArray($resultado);
        $this->assertCount(1, $resultado);
        $this->assertEquals('Simple', $resultado[0]['tipo']);
    }

    public function testConsultarDisponibilidadFechasInvalidas() {
        $this->expectException(InvalidArgumentException::class);
        $this->funcionalidadesReservar->consultarDisponibilidad('2025-06-12', '2025-06-10');
    }

    public function testRealizarReservaDisponible() {
        $usuario_id = 1;
        $habitacion_id = 1;
        $fecha_inicio = '2025-06-15';
        $fecha_fin = '2025-06-17';

        $resultado = $this->funcionalidadesReservar->realizarReserva($usuario_id, $habitacion_id, $fecha_inicio, $fecha_fin);
        $this->assertTrue($resultado);

        $res = $this->conn->query("SELECT * FROM reservas WHERE usuario_id = $usuario_id AND habitacion_id = $habitacion_id");
        $this->assertEquals(1, $res->num_rows);
    }

    public function testRealizarReservaNoDisponible() {
        $this->conn->query("INSERT INTO reservas (usuario_id, habitacion_id, fecha_inicio, fecha_fin) VALUES (2, 1, '2025-06-20', '2025-06-22')");

        $resultado = $this->funcionalidadesReservar->realizarReserva(3, 1, '2025-06-21', '2025-06-23');
        $this->assertFalse($resultado);
    }
}
