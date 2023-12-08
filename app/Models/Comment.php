<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text',
        'media',
        'event_id',
        'author_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment_';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'comment_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the event associated with the comment.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    /**
     * Get the author associated with the comment.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'user_id');
    }

    // Comment.php (model)

    public function reports()
    {
        return $this->hasMany(Report::class, 'comment_id');
    }
}