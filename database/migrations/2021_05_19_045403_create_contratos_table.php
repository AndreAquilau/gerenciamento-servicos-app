<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->timestamp('data_de_fechamento')->nullable()->default(NULL);
            $table->timestamp('data_de_emissao')->nullable()->default(NULL);
            $table->boolean('status')->nullable()->default(false);
            $table->string('descricao_do_servico')->nullable()->default(NULL);
            $table->decimal('valor', 11,2)->nullable()->default(NULL);
            $table->boolean('acerto_pago')->nullable()->default(NULL);
            $table->decimal('percentual_comissao_colaborador', 8,2)->nullable()->default(0.00);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('acerto_id')->nullable()->default(NULL);
            $table->foreignId('colaborador_id')->nullable();
            $table->foreignId('correntista_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}
