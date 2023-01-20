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
        Schema::create('depthead_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unsigned();
            $table->timestamps();



            $table->foreign('employee_id')->references('employee_id')->on('employee_tbl')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depthead_tbl');
    }
};
