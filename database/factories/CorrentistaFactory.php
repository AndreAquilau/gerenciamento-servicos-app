<?php

namespace Database\Factories;

use App\Models\Correntista;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorrentistaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Correntista::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->nome,
            'cpf' => $this->faker->cpf,
            'rua' => $this->faker->cpf,
            'cnpj' => $this->faker->cnpj,
            'telefone' => $this->faker->telefone,
            'bairro' => $this->faker->bairro,
            'cidade' => $this->faker->cidade,
            'numero' => $this->faker->numero,
            'estado' => $this->faker->estado,
            'inativo' => $this->faker->inativo,
            'created_at' => $this->faker->createdAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
        ];
    }


}
