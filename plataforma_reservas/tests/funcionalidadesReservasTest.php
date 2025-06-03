<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../funcionalidadesReservas.php';

class funcionalidadesReservasTest extends TestCase {

    private $conn;
    private $usuario_id_test = 1;

    protected function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "", "reservas");

        // Limpieza de datos previos por si ya existen
        $this->conn->query("DELETE FROM reservas WHERE id IN (99, 1000)");
        $this->conn->query("DELETE FROM habitaciones WHERE id IN (10, 100)");

        // Insertar habitación de prueba
        $this->conn->query("INSERT INTO habitaciones (id, tipo, numero, imagen, precio) 
                            VALUES (100, 'Suite', '201', NULL, 120.00)");

        // Insertar una reserva que sí pertenece al usuario_id_test (1)
        $this->conn->query("INSERT INTO reservas (id, usuario_id, habitacion_id, fecha_inicio, fecha_fin, creado_en)
                            VALUES (1000, {$this->usuario_id_test}, 100, '2099-12-01', '2099-12-05', NOW())");
    }

    public function testObtenerReservasUsuario()
    {
        $reservas = obtenerReservasUsuario($this->conn, $this->usuario_id_test);
        $this->assertIsArray($reservas);
        $this->assertNotEmpty($reservas);
        $this->assertEquals(1000, $reservas[0]['id']);
    }

    public function testCancelarReservaCorrectamente()
    {
        $resultado = cancelarReserva($this->conn, 1000, $this->usuario_id_test);
        $this->assertTrue($resultado);

        $verifica = $this->conn->query("SELECT * FROM reservas WHERE id = 1000");
        $this->assertEquals(0, $verifica->num_rows);
    }

    public function testCancelarReservaNoPerteneceUsuario()
    {
        // Insertar una habitación nueva para esta prueba
        $this->conn->query("INSERT INTO habitaciones (id, tipo, numero, imagen, precio)
                            VALUES (10, 'Suite', '210', '', 150.00)");

        // Crear reserva con usuario_id = 2 (otro usuario diferente al de prueba)
        $this->conn->query("INSERT INTO reservas (id, usuario_id, habitacion_id, fecha_inicio, fecha_fin, creado_en)
                            VALUES (99, 2, 10, '2025-12-10', '2025-12-12', NOW())");

        // Intentar cancelar con usuario_id = 1 (no propietario)
        $resultado = cancelarReserva($this->conn, 99, $this->usuario_id_test);
        $this->assertFalse($resultado, "No se debe poder cancelar una reserva que no pertenece al usuario.");

        // Verificar que la reserva no fue eliminada
        $res = $this->conn->query("SELECT * FROM reservas WHERE id = 99");
        $this->assertEquals(1, $res->num_rows);
    }

    protected function tearDown(): void
    {
        // Eliminar las reservas y habitaciones creadas en las pruebas
        $this->conn->query("DELETE FROM reservas WHERE id IN (99, 1000)");
        $this->conn->query("DELETE FROM habitaciones WHERE id IN (10, 100)");
        $this->conn->close();
    }
}
