<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeItDeclarationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'financial_year',
        'it_declaration_item_id',
        'declared_amount',
        'verified_amount',
        'remarks',
        'proof_path'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function item()
    {
        return $this->belongsTo(ItDeclarationItem::class, 'it_declaration_item_id');
    }
}
