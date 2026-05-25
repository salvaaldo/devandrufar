<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MedicamentoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'codigo'             => strtoupper($this->faker->bothify('LIN-####')),
            'nombre'             => $this->faker->randomElement([
                'Paracetamol', 'Ibuprofeno', 'Amoxicilina',
                'Ciprofloxacino', 'Metformina', 'Losartán',
            ]) . ' ' . $this->faker->bothify('###mg'),
            'forma_farmaceutica' => $this->faker->randomElement(['Tableta', 'Cápsula', 'Jarabe']),
            'concentracion'      => $this->faker->bothify('###mg'),
            'precio_referencial' => $this->faker->randomFloat(2, 5, 300),
            'aclaracion'         => null,
            'activo'             => true,
        ];
    }
}