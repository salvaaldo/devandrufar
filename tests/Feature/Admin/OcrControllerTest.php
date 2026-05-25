<?php

namespace Tests\Feature\Admin;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class OcrControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    // index()
    #[\PHPUnit\Framework\Attributes\Test]
    public function ocr_index_returns_view()
    {
        $response = $this->get('/ocr');
        $response->assertStatus(200);
        $response->assertViewIs('admin.ocr.index');
    }

    // buscarPorLote() - lote vacío
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_sin_lote_retorna_no_encontrado()
    {
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => '']);
        $response->assertStatus(200)
                 ->assertJson(['encontrado' => false, 'mensaje' => 'No se recibió lote válido']);
    }

    // buscarPorLote() - solo caracteres inválidos
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_solo_caracteres_invalidos_retorna_no_encontrado()
    {
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => '--- !!!']);
        $response->assertStatus(200)->assertJson(['encontrado' => false]);
    }

    // buscarPorLote() - lote no existe en BD
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_no_existente_retorna_no_encontrado()
    {
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => 'LOTE999XYZ']);
        $response->assertStatus(200)->assertJson(['encontrado' => false, 'lote_buscado' => 'LOTE999XYZ']);
    }

    // buscarPorLote() - estado VIGENTE (más de 90 días)
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_vigente_retorna_estado_vigente()
    {
        $producto = Producto::factory()->create(['nombre' => 'Paracetamol 500mg']);
        Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'lote'              => 'LOTE001',
            'fecha_vencimiento' => Carbon::today()->addDays(200),
            'cantidad'          => 50,
        ]);
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => 'LOTE001']);
        $response->assertStatus(200)
                 ->assertJson(['encontrado' => true, 'estado' => 'VIGENTE', 'nombre' => 'Paracetamol 500mg']);
    }

    // buscarPorLote() - estado PROXIMO (menos de 90 días)
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_proximo_a_vencer_retorna_estado_proximo()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'lote'              => 'LOTEPROX',
            'fecha_vencimiento' => Carbon::today()->addDays(30),
            'cantidad'          => 10,
        ]);
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => 'LOTEPROX']);
        $response->assertStatus(200)->assertJson(['encontrado' => true, 'estado' => 'PROXIMO']);
    }

    // buscarPorLote() - estado VENCIDO (fecha pasada)
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_vencido_retorna_estado_vencido()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'lote'              => 'LOTEVENC',
            'fecha_vencimiento' => Carbon::today()->subDays(10),
            'cantidad'          => 5,
        ]);
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => 'LOTEVENC']);
        $response->assertStatus(200)->assertJson(['encontrado' => true, 'estado' => 'VENCIDO']);
    }

    // buscarPorLote() - limpieza de texto (minúsculas y espacios)
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_por_lote_normaliza_minusculas_y_espacios()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'lote'              => 'LOTEABC',
            'fecha_vencimiento' => Carbon::today()->addDays(200),
        ]);
        $response = $this->postJson('/ocr/buscar-por-lote', ['lote' => ' lote abc ']);
        $response->assertStatus(200)->assertJson(['encontrado' => true]);
    }

    // guardar()
    #[\PHPUnit\Framework\Attributes\Test]
    public function guardar_retorna_success_true()
    {
        $response = $this->postJson('/ocr/guardar', []);
        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    // buscarLoteVencido() - sin nombre
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_lote_vencido_sin_nombre_retorna_no_encontrado()
    {
        $response = $this->postJson('/ocr/buscar-lote-vencido', ['nombre' => '']);
        $response->assertStatus(200)->assertJson(['encontrado' => false]);
    }

    // buscarLoteVencido() - nombre no existe
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_lote_vencido_nombre_no_existente_retorna_no_encontrado()
    {
        $response = $this->postJson('/ocr/buscar-lote-vencido', ['nombre' => 'MedicamentoInexistente']);
        $response->assertStatus(200)->assertJson(['encontrado' => false]);
    }

    // buscarLoteVencido() - nombre existe y está vencido
    #[\PHPUnit\Framework\Attributes\Test]
    public function buscar_lote_vencido_nombre_existente_retorna_datos()
    {
        $producto = Producto::factory()->create(['nombre' => 'Ibuprofeno 400mg']);
        Inventario::factory()->create([
            'producto_id'       => $producto->id,
            'lote'              => 'LOTEVENC2',
            'estado'            => 'vencido',
            'fecha_vencimiento' => Carbon::today()->subDays(5),
            'cantidad'          => 20,
        ]);
        $response = $this->postJson('/ocr/buscar-lote-vencido', ['nombre' => 'ibuprofeno']);
        $response->assertStatus(200)->assertJson(['encontrado' => true, 'lote' => 'LOTEVENC2']);
    }

    // darDeBaja() - lote no existe
    #[\PHPUnit\Framework\Attributes\Test]
    public function dar_de_baja_lote_inexistente_retorna_error()
    {
        $response = $this->postJson('/ocr/dar-de-baja', ['lote_id' => 9999]);
        $response->assertStatus(200)->assertJson(['success' => false, 'mensaje' => 'Lote no encontrado']);
    }

    // darDeBaja() - lote existe, se mueve a historial
    #[\PHPUnit\Framework\Attributes\Test]
    public function dar_de_baja_lote_existente_mueve_a_historial_y_elimina_inventario()
    {
        $producto   = Producto::factory()->create();
        $inventario = Inventario::factory()->vencido()->create([
            'producto_id' => $producto->id,
            'lote'        => 'LOTE_BAJA',
            'cantidad'    => 15,
        ]);

        $response = $this->postJson('/ocr/dar-de-baja', [
            'lote_id'     => $inventario->id,
            'observacion' => 'Prueba de baja',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);
        $this->assertDatabaseMissing('inventario', ['id' => $inventario->id]);
        $this->assertDatabaseHas('historial_bajas', ['lote' => 'LOTE_BAJA']);
    }

    // darDeBaja() - observación por defecto
    #[\PHPUnit\Framework\Attributes\Test]
    public function dar_de_baja_usa_observacion_por_defecto_si_no_se_envia()
    {
        $producto   = Producto::factory()->create();
        $inventario = Inventario::factory()->vencido()->create([
            'producto_id' => $producto->id,
            'lote'        => 'LOTE_DEF',
        ]);

        $this->postJson('/ocr/dar-de-baja', ['lote_id' => $inventario->id]);
        $this->assertDatabaseHas('historial_bajas', [
            'lote'        => 'LOTE_DEF',
            'observacion' => 'Baja automática por detección OCR',
        ]);
    }
}