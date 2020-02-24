<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_process', function (Blueprint $table) {
            $table->string('file_name')->primary()->unique();
            $table->string('status')->default(0);
            $table->bigInteger('rows_count')->default(0);
            $table->text('status_report')->nullable();
            $table->dateTime('processed_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_process');
    }
}
