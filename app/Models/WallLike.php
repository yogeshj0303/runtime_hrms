<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WallLike extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function post()
    {
        return $this->belongsTo(WallPost::class, 'wall_post_id');
    }
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
