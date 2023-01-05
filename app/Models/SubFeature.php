<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubFeature extends Model
{
    use HasFactory;

    protected $table = 'sub_feature_tbl';
    protected $primaryKey = 'sub_feature_id';

    protected $fillable = [
        'sub_feature_id',
        'feature_id',
        'sub_feature_name'
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id', 'feature_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'sub_feature_id', 'sub_feature_id');
    }
}

