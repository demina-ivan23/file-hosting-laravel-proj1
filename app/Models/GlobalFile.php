<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GlobalFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class);
    }
    public function scopeFilter($query)
    {
        if(request()->has('sort_by')) {
            $sortField = request()->input('sort_by');
        
            if ($sortField == 'latest') {
                $query->latest();
            } elseif ($sortField == 'oldest') {
                $query->oldest();
            } elseif ($sortField == 'most-viewed') {
                $query->orderBy('views', 'desc');
           
            }
             elseif ($sortField == 'most-liked') {
                $query->orderBy('likes', 'desc');
           
            }
            elseif ($sortField == 'most-downloaded') {
                $query->orderBy('downloads', 'desc');
           
            }
            elseif ($sortField == 'least-viewed') {
                $query->orderBy('views', 'asc');
           
            }
            elseif ($sortField == 'least-liked') {
                $query->orderBy('likes', 'asc');
           
            }
            elseif ($sortField == 'least-downloaded') {
                $query->orderBy('downloads', 'asc');
           
            } else {
                //
            }
        }
        else {
            return $query->latest();
        }

        return $query;
    }
}
