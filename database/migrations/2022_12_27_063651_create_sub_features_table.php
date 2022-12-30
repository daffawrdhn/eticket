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
        Schema::create('sub_feature_tbl', function (Blueprint $table) {
            $table->bigIncrements('sub_feature_id');
            $table->integer('feature_id');
            $table->string('sub_feature_name');
            $table->timestamps();

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
        Schema::dropIfExists('sub_feature_tbl');
    }
};
