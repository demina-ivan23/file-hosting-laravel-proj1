<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function reciever()
    {
        return $this->belongsTo(User::class, 'reciever_id');
    }
}
