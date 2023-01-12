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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'feature_id', 'feature_id');
    }

    // public static function boot() {
    //     parent::boot();

    //     static::deleting(function($feature) { // before delete() method call this
    //          $feature->subFeatures()->delete();
    //          // do the rest of the cleanup...
    //     });
    // }
}
