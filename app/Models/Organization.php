<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization_tbl';
    protected $primaryKey = 'organization_id';

    protected $fillable = [
        'organization_id',
        'organization_name', 
    ];


    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
