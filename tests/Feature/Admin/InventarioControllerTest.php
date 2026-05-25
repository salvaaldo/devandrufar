<?php

namespace Tests\Feature\Admin;

use App\Models\Inventario;
use App\Models\Producto;
use App\Models\User;
use App\Services\InventarioService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class InventarioControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_retorna_vista_con_inventarios()
    {
        $response = $this->get(route('inventario.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.inventario.index');
        $response->assertViewHas('inventarios');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_filtra_por_lote()
    {
        $producto1 = Producto::factory()->create();
        $producto2 = Producto::factory()->create();
        Inventario::factory()->create(['producto_id' => $producto1->id, 'lote' => 'LOTE001']);
        Inventario::factory()->create(['producto_id' => $producto2->id, 'lote' => 'LOTE002']);

        $response    = $this->get(route('inventario.index', ['search' => 'LOTE001']));
        $inventarios = $response->viewData('inventarios');
        $this->assertCount(1, $inventarios);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_filtra_por_estado_vencido()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vencido']);
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vigente']);
        Inventario::factory()->create(['producto_id' => $producto->id, 'estado' => 'vigente']);

        $response    = $this->get(route('inventario.index', ['estado' => 'vencido']));
        $inventarios = $response->viewData('inventarios');
        $this->assertCount(1, $inventarios);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function index_sin_filtros_retorna_todos()
    {
        $producto = Producto::factory()->create();
        Inventario::factory()->count(5)->create(['producto_id' => $producto->id]);

        $response    = $this->get(route('inventario.index'));
        $inventarios = $response->viewData('inventarios');
        $this->assertCount(5, $inventarios);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function create_retorna_vista_con_productos()
    {
        $this->mock(InventarioService::class, function ($mock) {
            $mock->shouldReceive('obtenerProductos')->andReturn(collect([]));
        });

        $response = $this->get(route('inventario.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.inventario.create');
        $response->assertViewHas('productos');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_crea_inventario_y_redirige()
    {
        $producto = Producto::factory()->create();

        $this->mock(InventarioService::class, function ($mock) {
            $mock->shouldReceive('obtenerProductos')->andReturn(collect([]));
            $mock->shouldReceive('crear')->once();
        });

        $response = $this->post(route('inventario.store'), [
            'producto_id'       => $producto->id,
            'lote'              => 'LOTE_NUEVO',
            'cantidad'          => 100,
            'fecha_vencimiento' => Carbon::today()->addYear()->format('Y-m-d'),
            'fecha_ingreso'     => Carbon::today()->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('success', 'Stock registrado correctamente.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function store_falla_sin_datos_requeridos()
    {
        $response = $this->post(route('inventario.store'), []);
        $response->assertSessionHasErrors();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_elimina_inventario_y_redirige()
    {
        $producto   = Producto::factory()->create();
        $inventario = Inventario::factory()->create(['producto_id' => $producto->id]);

        $this->mock(InventarioService::class, function ($mock) {
            $mock->shouldReceive('obtenerProductos')->andReturn(collect([]));
            $mock->shouldReceive('eliminar')->once();
        });

        $response = $this->delete(route('inventario.destroy', $inventario));
        $response->assertRedirect(route('inventario.index'));
        $response->assertSessionHas('success', 'Registro eliminado correctamente.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_inventario_inexistente_retorna_404()
    {
        $response = $this->delete(route('inventario.destroy', 9999));
        $response->assertStatus(404);
    }
}