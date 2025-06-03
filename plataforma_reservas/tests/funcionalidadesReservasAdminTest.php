<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../admin/funcionalidadesReservasAdmin.php';

class funcionalidadesReservasAdminTest extends TestCase {
    private $mockConn;
    private $mockStmt;
    private $reservaService;

    protected function setUp(): void {
        $this->mockConn = $this->createMock(mysqli::class);
        $this->mockStmt = $this->createMock(mysqli_stmt::class);
        $this->reservaService = new funcionalidadesReservasAdmin($this->mockConn);
    }

    public function testCancelarReservaDevuelveTrueSiExito() {
        $this->mockConn->method('prepare')->willReturn($this->mockStmt);
        $this->mockStmt->method('bind_param')->willReturn(true);
        $this->mockStmt->method('execute')->willReturn(true);
        $this->mockStmt->method('close')->willReturn(true);

        $resultado = $this->reservaService->cancelarReserva(5);
        $this->assertTrue($resultado);
    }

    public function testObtenerReservasDevuelveArray() {
        $fakeData = [
            ['id' => 1, 'fecha_inicio' => '2025-06-01', 'fecha_fin' => '2025-06-05', 'creado_en' => '2025-05-30',
             'usuario' => 'Ana', 'correo' => 'ana@correo.com', 'habitacion_tipo' => 'Suite', 'habitacion_numero' => '101']
        ];

        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_all')->willReturn($fakeData);
        $this->mockConn->method('query')->willReturn($mockResult);

        $reservas = $this->reservaService->obtenerTodasLasReservas();
        $this->assertIsArray($reservas);
        $this->assertCount(1, $reservas);
        $this->assertEquals('Ana', $reservas[0]['usuario']);
    }
}
