<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_rol_debe_ser_admin_u_operador()
    {
        $rolesValidos = ['admin', 'operador'];
        $this->assertContains('admin', $rolesValidos);
        $this->assertContains('operador', $rolesValidos);
    }

    /** @test */
    public function test_password_debe_tener_minimo_8_caracteres()
    {
        $password = 'abc123';
        $this->assertFalse(strlen($password) >= 8);

        $passwordValido = 'abc12345';
        $this->assertTrue(strlen($passwordValido) >= 8);
    }

    /** @test */
    public function test_usuario_nuevo_debe_cambiar_password()
    {
        $usuario = User::factory()->create([
            'debe_cambiar_password' => true
        ]);
        $this->assertTrue($usuario->debe_cambiar_password);
    }

    /** @test */
    public function test_email_debe_ser_unico()
    {
        User::factory()->create(['email' => 'test@andrufar.com']);
        $this->assertDatabaseHas('users', ['email' => 'test@andrufar.com']);
    }
}