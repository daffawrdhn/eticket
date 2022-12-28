<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'feature_tbl';
    protected $primaryKey = 'feature_id';


    protected $fillable = [
        'feature_id',
        'feature_name'
    ];



    public function subFeatures()
    {
        return $this->hasMany(SubFeature::class, 'feature_id', 'feature_id');
    }

    public function subFeature()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_tbl');
    }

    
}
