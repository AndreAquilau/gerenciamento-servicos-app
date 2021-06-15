<?php

namespace Database\Factories;

use App\Models\Recebe;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Acerto;
use App\Models\User;
use App\Models\Contrato;

class RecebeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recebe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'documento' => $this->faker->documento,
            'ordem_documento' => $this->faker->ordemDocumento,
            'ordem_documento_final' => $this->faker->ordemDocumentoFinal,
            'desconto' => $this->faker->desconto,
            'acrescimo' => $this->faker->acrescimo,
            'pago' => $this->faker->pago,
            'restante' => $this->faker->restante,
            'total' => $this->faker->total,
            'status' => $this->faker->status,
            'data_de_emissao' => $this->faker->dataDeEmissao,
            'data_de_vencimento' => $this->faker->dataDeVencimento,
            'data_de_pagamento' => $this->faker->dataDePagamento,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
            'acerto_id' => Acerto::factory(),
            'contrato_id' => Contrato::factory(),
        ];
    }
}
