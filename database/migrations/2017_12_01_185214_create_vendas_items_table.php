<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendasItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('venda_id')->unsigned();
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('restrict');
            $table->integer('produto_id')->unsigned();
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('restrict');
            $table->decimal('valor',15, 2);
           
            $table->integer('qtde');
            
            $table->decimal('total_venda',15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendas_items');
    }
}
