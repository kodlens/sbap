<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;

    protected $table = 'procurements';
    protected $primaryKey = 'procurement_id';


    protected $fillable = [
        'financial_year_id',
        'fund_source_id',
        'date_time',
        'training_control_no',
        'pr_no',
        'particulars',
        'pr_amount',
        'payee_id',
        'pr_status',

        'others',
        'office_id',
    ];


    public function financial_year(){
        return $this->hasMany(FinancialYear::class, 'financial_year_id', 'financial_year_id');
    }

    public function fund_source(){
        return $this->hasOne(FundSource::class, 'fund_source_id', 'fund_source_id');
    }


    public function procurement_documentary_attachments(){
        return $this->hasMany(ProcurementDocumentaryAttachment::class, 'procurement_id', 'procurement_id');
    }

    public function procurement_allotment_classes(){
        return $this->hasMany(ProcurementAllotmentClass::class, 'procurement_id', 'procurement_id')
            ->with(['allotment_class', 'allotment_class_account']);
    }


    public function allotment_class(){
        return $this->hasOne(AllotmentClass::class, 'allotment_class_id', 'allotment_class_id');
    }

    public function allotment_class_account(){
        return $this->hasOne(AllotmentClassAccount::class, 'allotment_class_account_id', 'allotment_class_account_id');
    }


    public function payee(){
        return $this->hasOne(Payee::class, 'payee_id', 'payee_id');
    }

    public function priority_program(){
        return $this->hasOne(PriorityProgram::class, 'priority_program_id', 'priority_program_id');
    }
    public function office(){
        return $this->hasOne(Office::class, 'office_id', 'office_id');
    }



}
