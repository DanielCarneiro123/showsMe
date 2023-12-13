<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in the database.
    public $timestamps  = false;
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'promotor_code',
        'phone_number',
        'is_admin',
        'active',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean', // Assuming 'is_admin' is a boolean field.
        'password' => 'hashed',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public static function countUsers()
    {
        try {
            $count = self::query()->count();
            return $count;
        } catch (\Exception $e) {
            echo '<script>console.error("countUsers - erro ao contar usuários: ' . $e->getMessage() . '");</script>';
            return 0; // Ou outro valor padrão apropriado
        }
    }
    

    public function own_events()
    {
        return $this->hasMany(Event::class, 'creator_id');
    }

    public function getProfileImage() {
        return FileController::get('profile', $this->id);
    }

    public function likes($comment_id): bool
    {
        return $this->newQuery()
            ->from('userlikes')
            ->where('user_id', $this->user_id)
            ->where('comment_id', $comment_id)
            ->exists();
    }
  
}