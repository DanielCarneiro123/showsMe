<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'timestamp',
        'promo_code',
        'buyer_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'timestamp' => 'datetime',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'TicketOrder';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the buyer associated with the ticket order.
     */
    public function buyer()
    {
        return $this->belongsTo(UserClass::class, 'buyer_id', 'user_id');
    }
}