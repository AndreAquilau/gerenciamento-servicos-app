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
            'data_de_vencimento' => $this->faker->dataDeVencimento,
            'status' => $this->faker->status,
            'descricao_do_servico' => $this->faker->descricaoDoServico,
            'valor' => $this->faker->valor,
            'valor_avista' => $this->faker->valor_avista,
            'valor_parcelado' => $this->faker->valor_parcelado,
            'quantidade_parcela' => $this->faker->quantidadeParcela,
            'desconto' => $this->faker->desconto,
            'acrescimo' => $this->faker->acrescimo,
            'percentual_comissao_colaborador' => $this->faker->percentualComissaoColaborador,
            'created_at' => $this->faker->createAt,
            'updated_at' => $this->faker->updatedAt,
            'user_id' => User::factory(),
            'colaborador_id' => Correntista::factory(),
            'correntista_id' => Colaborador::factory(),
        ];
    }
}
