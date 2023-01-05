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
        Schema::create('ticket_status_tbl', function (Blueprint $table) {
            $table->bigIncrements('ticket_status_id');
            $table->string('ticket_status_name');
            $table->string('ticket_status_next');
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
        //public function down()
    {
        Schema::dropIfExists('ticket_status_tbl');
    }
        
    }
};