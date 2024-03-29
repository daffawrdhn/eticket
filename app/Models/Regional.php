<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    use HasFactory;

    protected $table = 'regional_tbl';
    protected $primaryKey = 'regional_id';

    protected $fillable = [
        'regional_id',
        'regional_name', 
    ];


    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function approval()
    {
        return $this->hasMany(Approval::class, 'approval_id', 'approval_id');
    }
    
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'pic_regional_tbl', 'regional_id', 'employee_id');
    }
}
