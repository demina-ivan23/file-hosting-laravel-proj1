<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
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
        if (request()->has('search')) {
            $query->where(function ($query) {
                $search = request('search');
    
                $query
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhereHas('sender', function ($senderQuery) use ($search) {
                        $senderQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('receiver', function ($receiverQuery) use ($search) {
                        $receiverQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhere('path', 'like', '%' . $search . '%');
            });
        }
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
