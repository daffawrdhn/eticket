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
            $table->string('supervisor_id');
            $table->integer('feature_id')->unsigned();
            $table->integer('sub_feature_id')->unsigned();
            $table->integer('ticket_status_id')->unsigned();
            $table->string('ticket_title');
            $table->text('ticket_description');
            $table->string('photo');
            $table->timestamps();

            $table->foreign('employee_id')->references('employee_id')->on('employee_tbl')->onDelete('set null');
            $table->foreign('feature_id')->references('feature_id')->on('feature_tbl')->onDelete('set null');
            $table->foreign('sub_feature_id')->references('sub_feature_id')->on('sub_feature_tbl')->onDelete('set null');
            $table->foreign('ticket_status_id')->references('ticket_status_id')->on('ticket_status_tbl')->onDelete('set null');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_tbl');
    }
};
