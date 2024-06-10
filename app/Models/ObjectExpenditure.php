<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectExpenditure extends Model
{
    use HasFactory;

    protected $primaryKey = 'object_expenditure_id';
    protected $table = 'object_expenditures';

    protected $fillable = [
        'financial_year_id',
        'object_expenditure',
        'account_code',
        'allotment_class_id',
        'approved_budget',
        'beginning_budget',
        'end_budget',
        'utilize_budget',
        'is_priority_program'
    ];

    public function financial_year(){
        return $this->hasOne(FinancialYear::class, 'financial_year_id', 'financial_year_id')
            ->select('financial_year_id', 'financial_year_code', 'financial_year_desc', 'approved_budget');
    }

    public function allotment_class(){
        return $this->hasOne(AllotmentClass::class, 'allotment_class_id', 'allotment_class_id');
    }

    public function accounting_expenditure(){
        return $this->hasOne(AccountingExpenditure::class, 'object_expenditure_id', 'object_expenditure_id');
    }

   

}
