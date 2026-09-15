<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    protected $fillable = [

        'user_id',
        'business_id',
        'unit_name',
        'report_title',
        'sub_header_1',
        'sub_header_2',
        'footer_line_1',
        'footer_line_2',
        'is_default',
        'header_image',
        'footer_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}