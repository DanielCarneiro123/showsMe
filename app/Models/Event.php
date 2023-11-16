<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event_';

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        'private',
        'start_timestamp',
        'end_timestamp',
        'creator_id',
    ];

    protected $casts = [
        'private' => 'boolean',
        'start_timestamp' => 'datetime',
        'end_timestamp' => 'datetime',
    ];

    public $timestamps = false;
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'event_id', 'event_id');
    }
}
