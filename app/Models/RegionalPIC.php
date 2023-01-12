<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionalPIC extends Model
{
    use HasFactory;

    protected $table = 'pic_regional_tbl';
    protected $primaryKey = 'id';

    protected $fillable = [
        'regional_id',
        'employee_id', 
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id', 'regional_id');
    }
}
