<?php

namespace Tests\Feature\Admin;

use App\Models\Medicamento;
use App\Models\User;
use App\Services\MedicamentoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MedicamentoControllerTest extends TestCase
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
    public function index_retorna_vista_con_medicamentos()
    {
        Medicamento::factory()->count(3)->create();
        $response = $this->get(route('medicamentos.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.medicamentos.index');
        $response->assertViewHas('medicamentos');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function importar_retorna_vista_de_importacion()
    {
        $response = $this->get(route('medicamentos.importar'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.medicamentos.importar');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function procesar_importacion_sin_archivo_falla_validacion()
    {
        $response = $this->post(route('medicamentos.procesar'), []);
        $response->assertSessionHasErrors('archivo');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function procesar_importacion_con_archivo_no_excel_falla_validacion()
    {
        Storage::fake('local');
        $archivo  = UploadedFile::fake()->create('medicamentos.pdf', 100, 'application/pdf');
        $response = $this->post(route('medicamentos.procesar'), ['archivo' => $archivo]);
        $response->assertSessionHasErrors('archivo');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function procesar_importacion_con_archivo_muy_grande_falla_validacion()
    {
        Storage::fake('local');
        $archivo  = UploadedFile::fake()->create('medicamentos.xlsx', 10241, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response = $this->post(route('medicamentos.procesar'), ['archivo' => $archivo]);
        $response->assertSessionHasErrors('archivo');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function procesar_importacion_exitosa_redirige_con_mensaje_success()
    {
        Storage::fake('local');
        $archivo = UploadedFile::fake()->create('medicamentos.xlsx', 500, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->mock(MedicamentoService::class, function ($mock) {
            $mock->shouldReceive('listar')->andReturn(collect([]));
            $mock->shouldReceive('importarDesdeExcel')->once()->andReturn(['importados' => 10, 'omitidos' => 2]);
        });

        $response = $this->post(route('medicamentos.procesar'), ['archivo' => $archivo]);
        $response->assertRedirect(route('medicamentos.index'));
        $response->assertSessionHas('success');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function procesar_importacion_con_excepcion_redirige_con_error()
    {
        Storage::fake('local');
        $archivo = UploadedFile::fake()->create('medicamentos.xlsx', 500, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $this->mock(MedicamentoService::class, function ($mock) {
            $mock->shouldReceive('listar')->andReturn(collect([]));
            $mock->shouldReceive('importarDesdeExcel')->once()->andThrow(new \Exception('Formato de archivo inválido'));
        });

        $response = $this->post(route('medicamentos.procesar'), ['archivo' => $archivo]);
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Formato de archivo inválido');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_elimina_medicamento_y_redirige()
    {
        $medicamento = Medicamento::factory()->create();

        $this->mock(MedicamentoService::class, function ($mock) {
            $mock->shouldReceive('listar')->andReturn(collect([]));
            $mock->shouldReceive('eliminar')->once()->with(\Mockery::type(Medicamento::class));
        });

        $response = $this->delete(route('medicamentos.destroy', $medicamento));
        $response->assertRedirect(route('medicamentos.index'));
        $response->assertSessionHas('success', 'Medicamento eliminado correctamente.');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function destroy_medicamento_inexistente_retorna_404()
    {
        $response = $this->delete(route('medicamentos.destroy', 9999));
        $response->assertStatus(404);
    }
}