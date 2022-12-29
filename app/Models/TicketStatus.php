<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    use HasFactory;
    protected $table = 'ticket_status_tbl';
    protected $primaryKey = 'ticket_status_id';

    protected $fillable = [
        'ticket_status_id',
        'ticket_status_name',
    ];


    public function tickets()
    {
        return $this->belongsTo(Ticket::class);
    }
    
}