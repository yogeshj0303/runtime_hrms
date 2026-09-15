<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form16Info extends Model
{
    use HasFactory;
    
    protected $table = 'form16_infos';

    protected $fillable = [
        'business_id',
        'user_id',
        'full_name',
        'designation',
        'father_name',
        'signature_image',
        'employer_name',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'place_of_issue',
        'cit_name',
        'cit_address_line_1',
        'cit_address_line_2',
        'cit_address_line_3',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
