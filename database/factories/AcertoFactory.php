<?php

namespace Database\Factories;

use App\Models\Acerto;
use App\Models\User;
use App\Models\Contrato;
use App\Models\Recebe;
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
            'valor_colaborador' => $this->faker->valorColaborador,
            'valor_empresa' => $this->faker->valorEmpresa,
            'pago' => $this->faker->pago,
            'restante' => $this->faker->restante,
            'total' => $this->faker->total,
            'acrescimo' => $this->faker->acrescimo,
            'desconto' => $this->faker->desconto,
            'status' => $this->faker->status,
            'data_de_emissao' => $this->faker->dataDeEmissao,
            'data_de_pagamento' => $this->faker->dataDePagamento,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
            'recebe_id' => Recebe::factory(),
            'contrato_id' => Contrato::factory(),
        ];
    }
}
