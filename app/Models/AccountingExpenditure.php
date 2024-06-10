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
        'doc_type',
        'object_expenditure_id',
        'financial_year_id',
        'allotment_class_id',
        'allotment_class_code',
        'allotment_class',
        'priority_program',
        'amount'
    ];

    public function object_expenditure(){
        return $this->belongsTo(ObjectExpenditure::class, 'object_expenditure_id', 'object_expenditure_id');
    }

    public function accounting(){
        return $this->belongsTo(Accounting::class, 'accounting_id', 'accounting_id');
    }

    public function financial_year(){
        return $this->hasOne(FinancialYear::class, 'financial_year_id', 'financial_year_id');
    }

    
    public function allotment_class(){
        return $this->hasOne(AllotmentClass::class, 'allotment_class_id', 'allotment_class_id');
    }

    public function accountings(){
        return $this->hasOne(Accounting::class, 'accounting_id', 'accounting_id');
    }

    public function expenditure_doctype(){
        return $this->hasMany(Accounting::class, 'doc_type', 'doc_type')
            ->whereColumn('accountings.financial_year_id', 'accounting_expenditures.financial_year_id');
    }



}
