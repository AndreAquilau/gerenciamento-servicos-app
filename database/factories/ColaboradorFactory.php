<?php

namespace Database\Factories;

use App\Models\Colaborador;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ColaboradorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Colaborador::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome_completo' => $this->faker->nomeCompleto,
            'rg' => $this->faker->rg,
            'rua' => $this->faker->rg,
            'registro' => $this->faker->rgregistro
            'cpf_cnpj' => $this->faker->cpfCnpj,
            'comissao' => $this->faker->comissao,
            'telefone' => $this->faker->telefone,
            'bairro' => $this->faker->bairro,
            'cidade' => $this->faker->cidade,
            'numero' => $this->faker->numero,
            'estado' => $this->faker->estado,
            'inativo' => $this->faker->inatico,
            'experiencia' => $this->faker->experiencia,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
        ];
    }
}
