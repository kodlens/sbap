<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\FinancialYear;

use App\Models\Accounting;
use App\Models\Budgeting;
use App\Models\Procurement;
use App\Models\AccountingAllotmentClasses;

class DashboardController extends Controller
{
    //

    public function index(){

        $fy = FinancialYear::where('active', 1)
            ->first();


        // $cfy = DB::select('
        //     SELECT
        //     c.`allotment_class`,
        //     SUM(b.`amount`) as amount
        //     FROM accountings a
        //     JOIN `accounting_allotment_classes` b ON a.`accounting_id`= b.`accounting_id`
        //     JOIN `allotment_classes` c ON b.`allotment_class_id` = c.`allotment_class_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY b.`allotment_class_id`', [$fy->financial_year_id]);
        //return $cfy;

        // $fundSources = DB::select('
        //     SELECT
        //     a.`fund_source_id`,
        //     b.`fund_source`,
        //     SUM(a.`total_amount`) AS total_amount
        //     FROM accountings a
        //     JOIN `fund_sources` b ON a.`fund_source_id` = b.`fund_source_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY a.fund_source_id
        //     ', [$fy->financial_year_id]);



        // $budgetingCurrentFY = DB::select('
        //     SELECT
        //     c.`allotment_class`,
        //     SUM(b.`amount`) as amount
        //     FROM budgetings a
        //     JOIN `budgeting_allotment_classes` b ON a.`budgeting_id`= b.`budgeting_id`
        //     JOIN `allotment_classes` c ON b.`allotment_class_id` = c.`allotment_class_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY b.`allotment_class_id`', [$fy->financial_year_id]);

        // $budgetingFundSources = DB::select('
        //     SELECT
        //     a.`fund_source_id`,
        //     b.`fund_source`,
        //     SUM(a.`total_amount`) AS total_amount
        //     FROM budgetings a
        //     JOIN `fund_sources` b ON a.`fund_source_id` = b.`fund_source_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY a.fund_source_id
        //     ', [$fy->financial_year_id]);


        // $procurementCurrentFY = DB::select('
        //     SELECT
        //     c.`allotment_class`,
        //     SUM(b.`amount`) as amount
        //     FROM procurements a
        //     JOIN `procurement_allotment_classes` b ON a.`procurement_id`= b.`procurement_id`
        //     JOIN `allotment_classes` c ON b.`allotment_class_id` = c.`allotment_class_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY b.`allotment_class_id`', [$fy->financial_year_id]);

        // $procurementFundSources = DB::select('
        //     SELECT
        //     a.`fund_source_id`,
        //     b.`fund_source`,
        //     SUM(a.`pr_amount`) AS total_amount
        //     FROM procurements a
        //     JOIN `fund_sources` b ON a.`fund_source_id` = b.`fund_source_id`
        //     WHERE a.`financial_year_id` = ?
        //     GROUP BY a.fund_source_id
        //     ', [$fy->financial_year_id]);

        return view('administrator.dashboard');
            // ->with('budgetingCurrentFY', $budgetingCurrentFY)
            // ->with('budgetingFundSources', $budgetingFundSources)
            // ->with('procurementCurrentFY', $procurementCurrentFY)
            // ->with('procurementFundSources', $procurementFundSources);
    }


   

    // public function loadBudgetingUtilizations(Request $req, $financialId){

    //     $data = Budgeting::where('financial_year_id', $financialId)
    //         ->sum('total_amount');

    //     return $data;

    // }

    // public function loadProcurementUtilizations(Request $req, $financialId){

    //     $data = Procurement::where('financial_year_id', $financialId)
    //         ->sum('pr_amount');

    //     return $data;

    // }

    public function loadReportDashboard(){
        
        $data = Accounting::with(['accounting_expenditures', 'payee'])
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
