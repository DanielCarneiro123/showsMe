<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event_';
    public $timestamps = false;
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'name',
        'location',
        'description',
        //'private', //acho que não é atribuido em grande quantidade por isso não deve estar aqui (?)
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

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'event_id', 'event_id');
    }


    public function comments()
    {
        return $this->hasMany(Comment::class, 'event_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'event_id');
    }

}
