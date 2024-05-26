<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AugmentationBudget extends Model
{
    use HasFactory;

    protected $table = 'augmentation_budgets';
    protected $primaryKey = 'augmentation_budget_id';


    protected $fillable = [
        'financial_year_id', 
        'remarks', 
        'object_expenditure_id',
        'amount_transfer'
    ];


    public function financial_year(){
        return $this->hasOne(FinancialYear::class, 'financial_year_id', 'financial_year_id');
    }

    public function object_expenditure(){
        return $this->hasOne(ObjectExpenditure::class, 'object_expenditure_id', 'object_expenditure_id');
    }

   
}
