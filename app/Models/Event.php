<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'Event_';

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
