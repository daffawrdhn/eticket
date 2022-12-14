<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Password extends Model
{

            // $table->integer('employee_id');
            // $table->string('password');
            // $table->dateTime('created_at');
            // $table->date('non_active_date');

    use HasFactory;

    protected $table = 'employee_password_tbl';
    protected $primaryKey = 'password_id';
    // public $incrementing = true;

    protected $fillable = [
        'employee_id',
        'password',
        'non_active_date'
    ];

    protected $hidden = [
        'password',
    ];
}
