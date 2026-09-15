<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['document_url'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getDocumentUrlAttribute()
    {
        return asset($this->document_file ?? $this->file);
    }
}