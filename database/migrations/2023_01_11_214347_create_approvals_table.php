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
        Schema::create('approval_tbl', function (Blueprint $table) {
            $table->bigIncrements('approval_id');
            $table->integer('regional_id')->nullable();
            $table->string('employee_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('regional_id')->references('regional_id')->on('regional_tbl')->nullOnDelete();
            $table->foreign('employee_id')->references('employee_id')->on('employee_tbl')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approval_tbl');
    }
};
