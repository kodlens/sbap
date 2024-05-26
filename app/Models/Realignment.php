<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realignment extends Model
{
    use HasFactory;

    protected $table = 'realignments';
    protected $primaryKey = 'realignment_id';


    protected $fillable = ['financial_year_id', 'remarks', 'object_expenditure_id_from', 'object_expenditure_id_to', 'amount_transfer'];


    public function financial_year(){
        return $this->hasOne(FinancialYear::class, 'financial_year_id', 'financial_year_id');
    }

    public function object_expenditure_from(){
        return $this->hasOne(ObjectExpenditure::class, 'object_expenditure_id', 'object_expenditure_id_from');
    }

    public function object_expenditure_to(){
        return $this->hasOne(ObjectExpenditure::class, 'object_expenditure_id', 'object_expenditure_id_to');
    }

    
}
