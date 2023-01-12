<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helpdesk extends Model
{
    use HasFactory;

    protected $table = 'helpdesk_tbl';
    protected $primaryKey = 'id';

    protected $fillable = [
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
