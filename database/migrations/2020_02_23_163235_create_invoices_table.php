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
            $table->integer('total_calls');
            $table->double('total_duration');
            $table->double('sub_total');
            $table->double('vat_total');
            $table->double('total_inc_vat');
            $table->double('inv_total');
            $table->date('inv_date');
            $table->date('due_date');
            $table->string('inv_currency');
            $table->bigInteger('user_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
