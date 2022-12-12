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
        //
        Schema::create('employee_password_tbl', function(
            Blueprint $table
        ){
            $table->bigIncrements('password_id');
            $table->string('employee_id');
            $table->string('password');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->date('non_active_date')->nullable();
            
            // $table->foreign('employee_id')->references('id')->on('employee_tbl');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('employee_password_tbl');
    }
};
