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
        'allotment_class',
        'allotment_class_code',
        'approved_budget',
        'beginning_budget',
        'end_budget',
        'utilize_budget'
    ];

    public function financial_year(){
        return $this->hasOne(FinancialYear::class, 'financial_year_id', 'financial_year_id')
            ->select('financial_year_id', 'financial_year_code', 'financial_year_desc', 'approved_budget');
    }

}
