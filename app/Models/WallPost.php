<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WallPost extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function targetEmployee()
    {
        return $this->belongsTo(Employee::class, 'target_employee_id');
    }

    public function comments()
    {
        return $this->hasMany(WallComment::class)->latest();
    }

    public function likes()
    {
        return $this->hasMany(WallLike::class);
    }
}
