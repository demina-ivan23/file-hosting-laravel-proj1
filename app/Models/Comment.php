<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function global_file()
    {
        return $this->belongsTo(GlobalFile::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
