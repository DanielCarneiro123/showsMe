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
        return $this->hasMany(Rating::class, 'event_id', 'event_id');
    }
    public function getAverageRatingAttribute()
    {
        $ratings = $this->ratings;
        $totalRatings = $ratings->count();

        if ($totalRatings > 0) {
            $sum = $ratings->sum('rating');
            return $sum / $totalRatings;
        }

        return 0; // Default value if there are no ratings
    }
    public function userRating()
    {
        if (auth()->check()) {
            $userId = auth()->user()->user_id;

            return $this->ratings()->where('author_id', $userId)->first();
        }

        return null;
    }
    public function soldTickets()
    {
        // Retorna todas as instâncias de TicketInstance associadas aos TicketTypes deste evento
        return TicketInstance::whereIn('ticket_type_id', $this->ticketTypes->pluck('ticket_type_id'))
            ->get();
    }
}