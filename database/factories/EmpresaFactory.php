<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpresaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Empresa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fantasia' => $this->faker->fantasia,
            'razao_social' => $this->faker->razaoSocial,
            'inscricao_estadual' => $this->faker->inscricaoEstadual,
            'cpf' => $this->faker->cpf,
            'cnpj' => $this->faker->cnpj,
            'telefone' => $this->faker->telefone,
            'celular' => $this->faker->celular,
            'bairro' => $this->faker->bairro,
            'cidade' => $this->faker->cidade,
            'numero' => $this->faker->numero,
            'estado' => $this->faker->estado,
            'cep' => $this->faker->cep,
            'rua' => $this->faker->rua,
            'email' => $this->faker->email,
            'created_at' => $this->faker->createdAt,
            'updated_at' => $this->faker->updatedAt,
        ];
    }
}
