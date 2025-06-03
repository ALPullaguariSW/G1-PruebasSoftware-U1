<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../admin/funcionalidadesGestionEspacios.php';

class funcionalidadesGestionEspaciosTest extends TestCase {
    private $mockConn;
    private $controller;
    private $mockStmt;

    protected function setUp(): void {
        $this->mockConn = $this->createMock(mysqli::class);
        $this->mockStmt = $this->createMock(mysqli_stmt::class);
        $this->controller = new funcionalidadesGestionEspacios($this->mockConn);
    }

    public function testCrearHabitacionFallaPorCamposObligatorios() {
        $datos = [
            'numero' => '',
            'tipo' => '',
            'precio' => ''
        ];

        $resultado = $this->controller->crearHabitacion($datos);

        $this->assertEquals("El número, tipo y precio son obligatorios.", $resultado['mensaje']);
        $this->assertEquals("message-error", $resultado['clase']);
    }

    public function testCrearHabitacionExito() {
        $datos = [
            'numero' => '101',
            'tipo' => 'Suite',
            'descripcion' => 'Lujosa',
            'servicios' => 'TV, WiFi',
            'imagen' => 'img.jpg',
            'precio' => '150'
        ];

        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO habitaciones'))
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())->method('bind_param');
        $this->mockStmt->expects($this->once())->method('execute');
        $this->mockStmt->expects($this->once())->method('close');

        $resultado = $this->controller->crearHabitacion($datos);

        $this->assertEquals("Habitación creada correctamente.", $resultado['mensaje']);
        $this->assertEquals("message-success", $resultado['clase']);
    }

    public function testEditarHabitacionExito() {
        $datos = [
            'numero' => '101',
            'tipo' => 'Suite',
            'descripcion' => 'Renovada',
            'servicios' => 'TV, WiFi',
            'imagen' => 'img_new.jpg',
            'precio' => '180'
        ];

        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('UPDATE habitaciones'))
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())->method('bind_param');
        $this->mockStmt->expects($this->once())->method('execute');
        $this->mockStmt->expects($this->once())->method('close');

        $resultado = $this->controller->editarHabitacion(1, $datos);

        $this->assertEquals("Habitación actualizada correctamente.", $resultado['mensaje']);
        $this->assertEquals("message-success", $resultado['clase']);
    }

    public function testEliminarHabitacionExito() {
        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('DELETE FROM habitaciones'))
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())->method('bind_param');
        $this->mockStmt->expects($this->once())->method('execute');
        $this->mockStmt->expects($this->once())->method('close');

        $resultado = $this->controller->eliminarHabitacion(1);

        $this->assertEquals("Habitación eliminada.", $resultado['mensaje']);
        $this->assertEquals("message-success", $resultado['clase']);
    }

    public function testObtenerHabitacionPorId() {
        $expectedData = [
            'id' => 1,
            'numero' => '101',
            'tipo' => 'Suite',
            'descripcion' => 'Moderna',
            'servicios' => 'TV, WiFi',
            'imagen' => 'img.jpg',
            'precio' => '150'
        ];

        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')->willReturn($expectedData);

        $this->mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT * FROM habitaciones WHERE id=?'))
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())->method('bind_param');
        $this->mockStmt->expects($this->once())->method('execute');
        $this->mockStmt->expects($this->once())->method('get_result');
        $this->mockStmt->expects($this->once())->method('close');

        $resultado = $this->controller->obtenerHabitacionPorId(1);

        $this->assertEquals($expectedData, $resultado);
    }

    public function testListarHabitacionesSinFiltros() {
        $mockResult = $this->createMock(mysqli_result::class);
        $mockResult->method('fetch_assoc')
            ->will($this->onConsecutiveCalls(
                ['numero' => '101', 'tipo' => 'Suite'],
                ['numero' => '102', 'tipo' => 'Doble'],
                null
            ));

        $this->mockStmt->method('get_result')->willReturn($mockResult);

        $this->mockConn->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT * FROM habitaciones'))
            ->willReturn($this->mockStmt);

        $this->mockStmt->expects($this->once())->method('execute');
        $this->mockStmt->expects($this->once())->method('get_result');
        $this->mockStmt->expects($this->once())->method('close');

        $resultado = $this->controller->listarHabitaciones();

        $this->assertCount(2, $resultado);
        $this->assertEquals('101', $resultado[0]['numero']);
        $this->assertEquals('Suite', $resultado[0]['tipo']);
        $this->assertEquals('102', $resultado[1]['numero']);
        $this->assertEquals('Doble', $resultado[1]['tipo']);
    }
}
