<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role_tbl';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_id',
        'role_name', 
    ];


    protected $with = ['employee_tbl'];
    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
