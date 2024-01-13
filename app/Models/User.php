<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserContact;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'user_user', 'user_id_1', 'user_id_2')->withTimestamps();
    }
    public function sentFiles()
    {
        return $this->belongsToMany(File::class, 'file_user', 'userSender', 'file' )
        ->withPivot(['userReceiver']);
    }
    public function receivedFiles()
    {
        return $this->belongsToMany(File::class, 'file_user', 'userReceiver', 'file' )
        ->withPivot(['userSender']);
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'message_user', 'userReceiver', 'message');
    }

    
}
