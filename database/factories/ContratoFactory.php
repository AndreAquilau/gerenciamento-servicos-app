<?php

namespace Database\Factories;

use App\Models\Contrato;
use App\Models\Acerto;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Correntista;
use App\Models\Colaborador;
use App\Models\User;

class ContratoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contrato::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'data_de_fechamento' => $this->faker->dataDeFechamento,
            'data_de_emissao' => $this->faker->dataDeEmissao,
            'status' => $this->faker->status,
            'descricao_do_servico' => $this->faker->descricaoDoServico,
            'valor' => $this->faker->valor,
            'acerto_pago' => $this->faker->acertoPago,
            'percentual_comissao_colaborador' => $this->faker->percentualComissaoColaborador,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
            'colaborador_id' => Correntista::factory(),
            'correntista_id' => Colaborador::factory(),
            'acerto_id' => Acerto::factory(),
        ];
    }
}
