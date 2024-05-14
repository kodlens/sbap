<?php

namespace App\Http\Controllers;

use App\Models\DocumentaryAttachment;
use App\Models\FundSource;
use Illuminate\Http\Request;
use App\Models\TransactionType;
use App\Models\FinancialYear;
use App\Models\AllotmentClass;
use App\Models\Office;

class OpenController extends Controller
{
    //

    public function loadOffices(Request $req){
        return Office::orderBy('office', 'asc')
            ->get();
    }


    public function loadFinancialYears(Request $req){
        return FinancialYear::orderBy('financial_year_code', 'desc')
            ->get();
    }

    public function loadAllotmentClasses(Request $req){
        return AllotmentClass::orderBy('allotment_class', 'asc')
            ->get();
    }
    public function loadAllotmentClassesByFinancial($financial){
        return AllotmentClass::where('financial_year_id', $financial)
            ->orderBy('allotment_class', 'asc')
            ->get();
    }
    

    public function loadTransactionTypes(Request $req){
        return TransactionType::orderBy('transaction_type', 'asc')
            ->get();
    }

    public function loadDocumentaryAttachments(Request $req){
        return DocumentaryAttachment::orderBy('documentary_attachment', 'asc')
            ->get();
    }

    public function loadFundSources(Request $req){
        return FundSource::orderBy('fund_source', 'asc')
            ->get();
    }






}
