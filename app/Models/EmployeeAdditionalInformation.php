<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAdditionalInformation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'custom_fields' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
