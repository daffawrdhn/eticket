<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'employee_tbl';
    protected $primaryKey = 'employee_id';
    public $incrementing = false;

protected $fillable = [
    'employee_id',
    // 'password',
    'password_id',
    'organization_id',
    'supervisor_id',
    'regional_id',
    'role_id',
    'device_id',
    'employee_name',
    'employee_email',
    'join_date',
    'quit_date',
    'created_at',
    'updated_at',
    'remember_token',
];

    

/**
 * The attributes that should be hidden for serialization.
 *
 * @var array<int, string>
 */

protected $hidden = [
    // 'password',
    'remember_token',
];


//relation

    protected $with = ['role_tbl'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
