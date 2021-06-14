<?php

namespace Database\Factories;

use App\Models\Acerto;
use App\Models\User;
use App\Models\Contrato;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcertoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Acerto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'data_de_pagamento' => $this->faker->dataDePagamento,
            'total' => $this->faker->total,
            'valor_empresa' => $this->faker->valorEmpresa,
            'valor_colaborador' => $this->faker->valorColaborador,
            'pago' => $this->faker->pago,
            'restante' => $this->faker->restante,
            'status' => $this->faker->status,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
            'contrato_id' => Contrato::factory(),
        ];
    }
}
