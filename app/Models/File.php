<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function scopeFilter($query)
    {

        $authUser = User::find(auth()->user()->id);


        if (request('filter_sent_files')) {

            if (request('filter_sent_files') !== 'all') {
                $query->where('sender_id', $authUser->id)
                    ->where('receiver_id', request('filter_sent_files'));
                    // dd('hi');
            } else {
                $query->where('sender_id', $authUser->id);
            }
        }

        if (request('filter_received_files')) {

            if (request('filter_received_files') !== 'all') {
                $query->where('sender_id', request('filter_received_files'))
                    ->where('receiver_id', $authUser->id);
            } else {
                $query->where('receiver_id', $authUser->id);
            }
        }
        return $query;
    }
}
