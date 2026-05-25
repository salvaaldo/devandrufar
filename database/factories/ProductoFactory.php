<?php

namespace Database\Factories;

use App\Models\Medicamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'codigo'             => strtoupper($this->faker->bothify('MED-####')),
            'medicamento_id'     => Medicamento::factory(), // ← crea un Medicamento automáticamente
            'nombre'             => $this->faker->randomElement([
                'Paracetamol', 'Ibuprofeno', 'Amoxicilina',
                'Metformina', 'Atorvastatina', 'Omeprazol',
            ]) . ' ' . $this->faker->bothify('###mg'),
            'forma_farmaceutica' => $this->faker->randomElement(['Tableta', 'Cápsula', 'Jarabe', 'Inyectable']),
            'concentracion'      => $this->faker->bothify('###mg'),
            'precio_referencial' => $this->faker->randomFloat(2, 5, 200),
            'origen'             => $this->faker->randomElement(['Nacional', 'Importado']),
            'marca'              => $this->faker->company(),
        ];
    }
}