<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
