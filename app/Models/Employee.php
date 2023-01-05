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
    'password_id',
    'organization_id',
    'supervisor_id',
    'regional_id',
    'role_id',
    'device_id',
    'employee_name',
    'employee_email',
    'employee_ktp',
    'employee_birth',
    'join_date',
    'quit_date',
    'created_at',
    'updated_at',
    'api_token',
    'remember_token',
];

    

/**
 * The attributes that should be hidden for serialization.
 *
 * @var array<int, string>
 */

protected $hidden = [
    // 'password',
    'password_id',
    'organization_id',
    'regional_id',
    'role_id',
    'created_at',
    'updated_at',
    'remember_token',
];


//relation

    

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'employee_id', 'employee_id');
    }
}
