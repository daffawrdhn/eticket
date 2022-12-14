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
}
