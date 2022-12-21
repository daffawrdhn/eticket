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

    // employee_tbl	type data	primary
    // employee_id	varchar(20)	primary_key
    // password_id	int()	
    // organization_id	int()	
    // supervisor_id	int()	
    // regional_id	int()	
    // role_id	int()	
    // device_id	varchar(16)	
    // employee_name	varchar(255)	
    // employee_email	varchar(255)	
    // join_date	date	
    // quit_date	date	
    // created_at	datetime	
    // update_created_at	datetime	

    public function up()
    {
        //
        Schema::create('employee_tbl', function(
            Blueprint $table
        ){
            $table->string('employee_id')->unique()->index();
            // $table->string('password');
            $table->integer('password_id');
            $table->integer('organization_id')->unsigned();
            $table->foreign('organization_id')->references('organization_id')->on('organization_tbl')->onDelete('set null');;
            $table->string('supervisor_id');
            $table->integer('regional_id')->unsigned();
            $table->foreign('regional_id')->references('regional_id')->on('regional_tbl')->onDelete('set null');;
            $table->integer('role_id')->unsigned();
            $table->foreign('role_id')->references('role_id')->on('role_tbl')->onDelete('set null');
            $table->string('device_id')->nullable()->unique();
            $table->string('employee_name');
            $table->string('employee_email')->unique();
            $table->string('employee_ktp')->unique(); //baru
            $table->string('employee_birth'); //baru
            $table->date('join_date')->nullable();
            $table->date('quit_date')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('api_token', 80)->unique()
                  ->nullable()
                  ->default(null);
            $table->rememberToken();

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
        Schema::dropIfExists('employee_tbl');
    }
};
