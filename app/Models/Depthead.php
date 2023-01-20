<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depthead extends Model
{
    use HasFactory;


    protected $table = 'depthead_tbl';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 
    ];
}
