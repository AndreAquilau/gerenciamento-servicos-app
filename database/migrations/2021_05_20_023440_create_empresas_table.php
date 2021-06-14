<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('fantasia');
            $table->string('razao_social');
            $table->string('inscricao_estadual');
            $table->string('cpf');
            $table->string('cnpj');
            $table->string('telefone');
            $table->string('celular');
            $table->string('bairro');
            $table->string('cep');
            $table->string('rua');
            $table->string('cidade');
            $table->string('numero');
            $table->string('estado');
            $table->string('email');
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
        Schema::dropIfExists('empresas');
    }
}
