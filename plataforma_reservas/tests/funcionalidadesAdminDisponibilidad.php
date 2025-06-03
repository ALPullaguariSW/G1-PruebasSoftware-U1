<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../admin/funcionalidadesDisponibilidad.php';

class funcionalidadesAdminDisponibilidad extends TestCase {
    private $mysqliMock;
    private $stmtMock;
    private $resultMock;

    protected function setUp(): void {
        $this->mysqliMock = $this->createMock(mysqli::class);
        $this->stmtMock = $this->createMock(mysqli_stmt::class);
        $this->resultMock = $this->createMock(mysqli_result::class);
    }

    public function testFechasInvalidasDevuelvenArrayVacio() {
        $servicio = new funcionalidadesDisponibilidad($this->mysqliMock);
        $this->assertEmpty($servicio->buscarHabitacionesDisponibles('', ''));
        $this->assertEmpty($servicio->buscarHabitacionesDisponibles('2025-06-10', '2025-06-05'));
    }

    public function testHabitacionesDisponiblesDevuelveResultados() {
        $this->mysqliMock->method('prepare')->willReturn($this->stmtMock);
        $this->stmtMock->method('bind_param');
        $this->stmtMock->method('execute');
        $this->stmtMock->method('get_result')->willReturn($this->resultMock);

        // Simular resultado
        $this->resultMock->method('fetch_assoc')->will($this->onConsecutiveCalls(
            ['id' => 1, 'numero' => '101', 'tipo' => 'Doble', 'descripcion' => 'Vista al mar'],
            null // para detener el bucle
        ));
        $this->stmtMock->method('close');

        $servicio = new funcionalidadesDisponibilidad($this->mysqliMock);
        $habitaciones = $servicio->buscarHabitacionesDisponibles('2025-06-10', '2025-06-15');

        $this->assertCount(1, $habitaciones);
        $this->assertEquals('101', $habitaciones[0]['numero']);
    }
}
