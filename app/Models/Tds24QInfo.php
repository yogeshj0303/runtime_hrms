<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tds24QInfo extends Model
{
    use HasFactory;

    protected $table = 'tds_24q_infos';

    protected $fillable = [
        'business_id',
        'user_id',
        
        // General Info
        'deductor_type',
        'section_code',
        'state',
        'ministry',
        'ministry_name',
        'ain_number',
        'pao_code',
        'pao_registration_number',
        'ddo_code',
        'ddo_registration_number',

        // Employer Details
        'employer_name',
        'branch_division',
        'emp_address_line_1',
        'emp_address_line_2',
        'emp_address_line_3',
        'emp_address_line_4',
        'emp_address_line_5',
        'emp_state',
        'emp_pin',
        'emp_pan',
        'emp_tan',
        'emp_email',
        'emp_std_code',
        'emp_phone',
        'emp_alternate_email',
        'emp_alternate_std_code',
        'emp_alternate_phone',
        'emp_gst_number',

        // Responsible Person Details
        'resp_name',
        'resp_designation',
        'resp_address_line_1',
        'resp_address_line_2',
        'resp_address_line_3',
        'resp_address_line_4',
        'resp_address_line_5',
        'resp_state',
        'resp_pin',
        'resp_pan',
        'resp_mobile',
        'resp_email',
        'resp_std_code',
        'resp_phone',
        'resp_alternate_email',
        'resp_alternate_std_code',
        'resp_alternate_phone',
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
