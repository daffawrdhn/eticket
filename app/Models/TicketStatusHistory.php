<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
    use HasFactory;
    protected $table = 'ticket_status_history_tbl';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_id',
        'status_before',
        'status_after',
        'description',
        'supervisor_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function beforeStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status_before');
    }

    public function afterStatus()
    {
        return $this->belongsTo(TicketStatus::class, 'status_after');
    }
    
}