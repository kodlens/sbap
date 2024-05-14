<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingExpenditure extends Model
{
    use HasFactory;

    protected $table = 'accounting_expenditures';
    protected $primaryKey = 'accounting_expenditure_id';


    protected $fillable = [
        'accounting_id',
        'object_expenditure_id',
        'financial_year_id',
        'allotment_class_code',
        'allotment_class',
        'amount'
    ];

    public function object_expenditure(){
        return $this->hasOne(ObjectExpenditure::class, 'object_expenditure_id', 'object_expenditure_id');
    }

    



}
