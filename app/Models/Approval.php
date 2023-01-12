<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approval_tbl';
    protected $primaryKey = 'approval_id';

    protected $fillable = [
        'approval_id',
        'regional_id',
        'employee_id'
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id', 'regional_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
