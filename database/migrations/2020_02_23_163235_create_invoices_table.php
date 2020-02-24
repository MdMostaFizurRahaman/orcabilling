<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('inv_number');
            $table->bigInteger('company_id');
            $table->bigInteger('client_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('tariff_prefix')->nullable();
            $table->bigInteger('user_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('invoices');
    }
}
