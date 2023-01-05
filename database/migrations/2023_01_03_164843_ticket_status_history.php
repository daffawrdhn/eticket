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
        Schema::create('ticket_status_history_tbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ticket_id')->unsigned();
            $table->integer('status_before')->unsigned();
            $table->integer('status_after')->unsigned();
            $table->text('description')->nullable();
            $table->text('supervisor_id')->nullable();
            $table->timestamps();

            $table->foreign('ticket_id')->references('ticket_id')->on('ticket_tbl')->onDelete('cascade');
            $table->foreign('status_before')->references('ticket_status_id')->on('ticket_status_tbl')->onDelete('set null');
            $table->foreign('status_after')->references('ticket_status_id')->on('ticket_status_tbl')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_status_history');
    }
};
