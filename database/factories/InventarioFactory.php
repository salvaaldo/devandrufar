<?php

namespace Database\Factories;

use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventarioFactory extends Factory
{
    public function definition(): array
    {
        $fechaVenc = Carbon::today()->addDays($this->faker->numberBetween(100, 500));

        return [
            'producto_id'       => Producto::factory(),
            'lote'              => strtoupper($this->faker->bothify('LOTE-####')),
            'cantidad'          => $this->faker->numberBetween(10, 500),
            'fecha_vencimiento' => $fechaVenc,
            'fecha_ingreso'     => Carbon::today()->subDays($this->faker->numberBetween(1, 30)),
            'estado'            => 'vigente',
        ];
    }

    public function vencido(): static
    {
        return $this->state(fn () => [
            'fecha_vencimiento' => Carbon::today()->subDays($this->faker->numberBetween(1, 60)),
            'estado'            => 'vencido',
        ]);
    }

    public function porVencer(): static
    {
        return $this->state(fn () => [
            'fecha_vencimiento' => Carbon::today()->addDays($this->faker->numberBetween(1, 89)),
            'estado'            => 'por_vencer',
        ]);
    }
}