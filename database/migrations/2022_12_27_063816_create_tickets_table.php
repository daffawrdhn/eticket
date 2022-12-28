<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_tbl', function (Blueprint $table) {
            $table->bigIncrements('ticket_id');
            $table->string('employee_id')->unsigned();
            $table->unsignedBigInteger('feature_id');
            $table->string('ticket_title');
            $table->string('photo');
            $table->text('ticket_description');
            $table->string('ticket_status');
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employee_tbl')->onDelete('set null');
            $table->foreign('feature_id')->references('feature_id')->on('feature_tbl')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
