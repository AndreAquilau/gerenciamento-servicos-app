<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecebesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recebes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('documento')->nullable()->default(NULL);
            $table->bigInteger('ordem_documento')->nullable()->default(NULL);
            $table->bigInteger('ordem_documento_final')->nullable()->default(NULL);
            $table->decimal('desconto', 11,2)->nullable()->default(NULL);
            $table->decimal('acrescimo', 11,2)->nullable()->default(NULL);
            $table->decimal('pago', 11,2)->nullable()->default(NULL);
            $table->decimal('restante', 11,2)->nullable()->default(NULL);
            $table->decimal('total', 11,2)->nullable()->default(NULL);
            $table->boolean('status')->nullable()->default(false);
            $table->timestamp('data_de_vencimento')->nullable()->default(NULL);
            $table->timestamp('data_de_pagamento')->nullable()->default(NULL);
            $table->timestamp('data_de_emissao')->nullable()->default(NULL);
            $table->timestamp('created_at')->nullable()->default(NULL);
            $table->timestamp('updated_at')->nullable()->default(NULL);
            $table->foreignId('user_id')->nullable()->default(NULL);
            $table->foreignId('contrato_id')->nullable()->default(NULL);
            $table->foreignId('acerto_id')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recebes');
    }
}
