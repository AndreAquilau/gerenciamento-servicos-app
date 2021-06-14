<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColaboradorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colaboradors', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->string('cpf_cnpj');
            $table->string('registro');
            $table->string('rg');
            $table->decimal('comissao', 8,2);
            $table->string('telefone');
            $table->string('bairro');
            $table->string('rua');
            $table->string('cidade');
            $table->string('numero');
            $table->string('estado');
            $table->string('experiencia');
            $table->boolean('inativo')->default(false);
            $table->foreignId('user_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colaboradores');
    }
}
