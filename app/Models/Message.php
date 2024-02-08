<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $guarded = ['id'];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'messages', 'userReceiver');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'messages', 'userSender');
    }

    public function getDateOfCreationAttribute()
    {
        return date('F d, Y', strtotime($this->created_at));
    }
}
