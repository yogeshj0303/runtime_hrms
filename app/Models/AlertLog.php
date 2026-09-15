<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'alert_id',
        'channel',
        'status',
        'error_message'
    ];

    public function alert()
    {
        return $this->belongsTo(Alert::class);
    }
}
