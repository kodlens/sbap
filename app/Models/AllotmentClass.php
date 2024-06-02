<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllotmentClass extends Model
{
    use HasFactory;

    protected $table = 'allotment_classes';
    protected $primaryKey = 'allotment_class_id';


    protected $fillable = [
        'allotment_class_code',
        'allotment_class', 
        'active'
    ];


    // public function accounting_expenditures(){
    //     return $this->hasMany(AccountingExpenditure::class, 'allotment_class_id', 'allotment_class_id')
    //         ->leftJoin('object_expenditures', 'accounting_expenditures.object_expenditure_id', 'object_expenditures.object_expenditure_id')
    //         ->leftJoin('accountings', 'accounting_expenditures.accounting_id', 'accountings.accounting_id');
    // }

    public function accounting_expenditures(){
        return $this->hasMany(AccountingExpenditure::class, 'allotment_class_id', 'allotment_class_id');
    }
}
