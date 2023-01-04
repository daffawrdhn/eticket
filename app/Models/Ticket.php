<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'ticket_tbl';
    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'ticket_id',
        'employee_id',
        'supervisor_id',
        'feature_id',
        'sub_feature_id',  // add this line
        'ticket_title',
        'photo',
        'ticket_description',
        'ticket_status_id',
        'ticket_status',
    ];

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function subFeature()
    {
        return $this->belongsTo(SubFeature::class, 'sub_feature_id');
    }

    public function ticketStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
