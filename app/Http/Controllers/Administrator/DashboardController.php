<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinancialYear;

use App\Models\Accounting;
use App\Models\Budgeting;
use App\Models\Procurement;
use App\Models\AllotmentClass;
use App\Models\AccountingExpenditure;

class DashboardController extends Controller
{
    //

    public function index(){

        return view('administrator.dashboard');
    }


    public function loadReportDashboard(Request $req){
        

        if($req->doc === 'ALL'){
            $data = Accounting::with(['accounting_expenditures', 'payee'])
                ->where('financial_year_id', $req->fy)
                ->get();
        }else{
            $data = Accounting::with(['accounting_expenditures', 'payee'])
                ->where('financial_year_id', $req->fy)
                ->where('doc_type', 'LIKE', $req->doc . '%')
                ->get();
        }
        

        return $data;

    }

    public function loadReportByAllotmentClasses(Request $req){

        $data = AllotmentClass::with(['object_expenditures', 'accounting_expenditures.object_expenditure'])
            // ->whereHas('accounting_expenditures.financial_year', function($q) use ($req){
            //     $q->where('financial_year_id', $req->fy);
            // })
            ->get();

        return $data;
    }

    //this will fetch all data for report in dashboard
    // public function loadReportDashboardAccounting(Request $req){
    //     $financialId = $req->fy;
    //     $allotmenClass = $req->allotment;
    //     $fundSource = $req->fundsource;
    //     $doc = $req->doc;
    //     $data = [];

    //     if($allotmenClass != '' || $allotmenClass != null){
    //         $data = DB::select('
    //         SELECT
    //         h.fund_source_id,
    //         h.fund_source,
    //         g.service,
    //         g.budget as service_budget,
    //         g.balance as service_balance,
    //         b.doc_type,
    //         b.transaction_no,
    //         e.financial_year_id,
    //         e.financial_year_code,
    //         e.financial_year_desc,
    //         e.financial_budget,
    //         e.balance,

    //         a.amount,
    //         a.allotment_class_id,
    //         c.allotment_class,
    //         a.allotment_class_account_id,
    //         d.allotment_class_account_code,
    //         d.allotment_class_account,
    //         c.allotment_class_budget,
    //         c.allotment_class_balance,
    //         d.allotment_class_account_budget,
    //         d.allotment_class_account_balance,
    //         f.priority_program,
    //         f.priority_program_code,
    //         f.priority_program_budget,
    //         f.priority_program_balance
            
    //         FROM
    //         accountings b
    //         LEFT JOIN accounting_allotment_classes a ON a.accounting_id = b.accounting_id
    //         LEFT JOIN allotment_classes c ON a.allotment_class_id = c.allotment_class_id
    //         LEFT JOIN allotment_class_accounts d ON a.allotment_class_account_id = d.allotment_class_account_id
    //         LEFT JOIN financial_years e ON b.financial_year_id = e.financial_year_id
    //         LEFT JOIN priority_programs f ON b.priority_program_id = f.priority_program_id
    //         LEFT JOIN services g ON b.doc_type = g.service AND b.financial_year_id = g.financial_year_id
    //         LEFT JOIN fund_sources h ON h.fund_source_id = b.fund_source_id
    //         WHERE h.fund_source LIKE ? AND e.financial_year_id = ? AND c.allotment_class LIKE ?
    //         AND b.doc_type LIKE ?
    //         ', [$fundSource . '%', $financialId, $allotmenClass . '%', $doc . '%']);
    //     }else{
    //         $data = DB::select('
    //         SELECT
    //         h.fund_source_id,
    //         h.fund_source,
    //         g.service,
    //         g.budget as service_budget,
    //         g.balance as service_balance,
    //         b.doc_type,
    //         b.transaction_no,
    //         e.financial_year_id,
    //         e.financial_year_code,
    //         e.financial_year_desc,
    //         e.financial_budget,
    //         e.balance,

    //         a.amount,
    //         a.allotment_class_id,
    //         c.allotment_class,
    //         a.allotment_class_account_id,
    //         d.allotment_class_account_code,
    //         d.allotment_class_account,
    //         c.allotment_class_budget,
    //         c.allotment_class_balance,
    //         d.allotment_class_account_budget,
    //         d.allotment_class_account_balance,
    //         f.priority_program,
    //         f.priority_program_code,
    //         f.priority_program_budget,
    //         f.priority_program_balance
            
    //         FROM
    //         accountings b
    //         LEFT JOIN accounting_allotment_classes a ON a.accounting_id = b.accounting_id
    //         LEFT JOIN allotment_classes c ON a.allotment_class_id = c.allotment_class_id
    //         LEFT JOIN allotment_class_accounts d ON a.allotment_class_account_id = d.allotment_class_account_id
    //         LEFT JOIN financial_years e ON b.financial_year_id = e.financial_year_id
    //         LEFT JOIN priority_programs f ON b.priority_program_id = f.priority_program_id
    //         LEFT JOIN services g ON b.doc_type = g.service AND b.financial_year_id = g.financial_year_id
    //         LEFT JOIN fund_sources h ON h.fund_source_id = b.fund_source_id
    //         WHERE h.fund_source LIKE ? AND e.financial_year_id = ?
    //         AND b.doc_type LIKE ?
    //         ', [$fundSource . '%', $financialId, $doc . '%']);
    //     }
        

    //     return $data;
    // }
    //compute utilize budget of accounting
    // public function loadAccountingUtilizations(Request $req, $financialId){

    //     $doc = $req->doc;

   
    //     $data = [];

    //     if($doc === 'ALL'){
    //         $data = Accounting::with(['service'])
    //             ->where('financial_year_id', $financialId)
    //             ->sum('total_amount');
    //     }else{
    //         $data = Accounting::with(['service'])
    //             ->where('doc_type', $doc)
    //             ->where('financial_year_id', $financialId)
    //             ->sum('total_amount');
    //     }
        

    //     return $data;
    // }

   



}
