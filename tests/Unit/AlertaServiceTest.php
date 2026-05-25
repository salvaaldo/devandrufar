<?php

namespace Tests\Unit;

use App\Models\Inventario;
use App\Models\Producto;
use App\Services\AlertaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AlertaServiceTest extends TestCase
{
    use RefreshDatabase;

    private AlertaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AlertaService();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function vencidos_retorna_solo_medicamentos_vencidos()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vencido']);
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vigente']);
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'por_vencer']);

        $resultado = $this->service->vencidos();
        $this->assertCount(1, $resultado);
        $this->assertEquals('vencido', $resultado->first()->estado);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function vencidos_retorna_coleccion_vacia_si_no_hay_vencidos()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vigente']);

        $resultado = $this->service->vencidos();
        $this->assertCount(0, $resultado);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function por_vencer_retorna_solo_medicamentos_por_vencer()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'por_vencer']);
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vencido']);

        $resultado = $this->service->porVencer();
        $this->assertCount(1, $resultado);
        $this->assertEquals('por_vencer', $resultado->first()->estado);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contar_alertas_retorna_conteos_correctos()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->count(3)->create(['producto_id' => $producto->id, 'estado' => 'vencido']);
        Inventario::factory()->count(2)->create(['producto_id' => $producto->id, 'estado' => 'por_vencer']);
        Inventario::factory()->count(5)->create(['producto_id' => $producto->id, 'estado' => 'vigente']);

        $resultado = $this->service->contarAlertas();
        $this->assertEquals(3, $resultado['vencidos']);
        $this->assertEquals(2, $resultado['por_vencer']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function contar_alertas_retorna_cero_si_no_hay_alertas()
    {
        $resultado = $this->service->contarAlertas();
        $this->assertEquals(0, $resultado['vencidos']);
        $this->assertEquals(0, $resultado['por_vencer']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function actualizar_estados_corrige_estado_incorrecto()
    {
        $producto = Producto::factory()->create();
        $inv = Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'fecha_vencimiento' => Carbon::today()->subDays(5),
            'estado'            => 'vigente', // estado incorrecto intencionalmente
        ]);

        $this->service->actualizarEstados();
        $this->assertEquals('vencido', $inv->fresh()->estado);
    }
}