<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementAllotmentClass extends Model
{
    use HasFactory;

    protected $table = 'procurement_allotment_classes';
    protected $primaryKey = 'procurement_allotment_class_id';


    protected $fillable = [
        'procurement_id',
        'allotment_class_id',
        'allotment_class_account_id',
        'amount',

    ];


    public function allotment_class(){
        return $this->hasOne(AllotmentClass::class, 'allotment_class_id', 'allotment_class_id');
    }

    public function allotment_class_account(){
        return $this->hasOne(AllotmentClassAccount::class, 'allotment_class_account_id', 'allotment_class_account_id');
    }


}
