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
        
        // $data = AllotmentClass::with(['accounting_expenditures'])
        //     ->whereHas('accounting_expenditures', function($q) use ($req){
        //         $q->where('doc_type', $req->doc);
        //     })
        //     ->get();
        // $data = AllotmentClass::with(['accounting_expenditures.object_expenditure', 'accounting_expenditures.accounting'])
        //     ->whereHas('accounting_expenditures.accounting', function($query) use ($req) {
        //         $query->where('doc_type', $req->doc);
        //     })
        //     ->get();
        $data = [];

        if($req->doc == 'ALL'){
            $allotments = AccountingExpenditure::join('allotment_classes', 'accounting_expenditures.allotment_class_id', '=', 'allotment_classes.allotment_class_id')
                ->join('financial_years', 'accounting_expenditures.financial_year_id', '=', 'financial_years.financial_year_id')
                ->join('object_expenditures', 'accounting_expenditures.object_expenditure_id', '=', 'object_expenditures.object_expenditure_id')
                ->join('accountings', 'accounting_expenditures.accounting_id', '=', 'accountings.accounting_id')
                ->select(
                    'accounting_expenditures.accounting_expenditure_id',
                    'accounting_expenditures.accounting_id',
                    'accountings.doc_type',
                    'accounting_expenditures.doc_type as acctg_doc_type',
                    'accounting_expenditures.allotment_class_id',
                    'accounting_expenditures.amount',
                    'accounting_expenditures.financial_year_id',
                    'financial_years.financial_year_code', 
                    'financial_years.financial_year_desc',
                    'accounting_expenditures.object_expenditure_id',
                    'allotment_classes.allotment_class_code', 
                    'allotment_classes.allotment_class',
                    'object_expenditures.object_expenditure', 
                    'object_expenditures.approved_budget',
                    'object_expenditures.beginning_budget',
                    DB::raw('SUM(accounting_expenditures.amount) AS utilize_budget')
                )
                ->groupBy('accounting_expenditures.allotment_class_id')
                ->where('accounting_expenditures.financial_year_id', $req->fy)
                ->get();

            foreach($allotments as $item){

                $accExps = DB::select("
                    SELECT
                    a.financial_year_id,
                    a.doc_type,
                    a.allotment_class_id,
                    a.object_expenditure_id,
                    a.amount,
                    b.account_code,
                    b.object_expenditure,
                    b.approved_budget,
                    c.allotment_class_code,
                    c.allotment_class
                    FROM
                    accounting_expenditures a
                    JOIN object_expenditures b ON a.object_expenditure_id = b.object_expenditure_id
                    JOIN allotment_classes c ON b.allotment_class_id = c.allotment_class_id
                    WHERE b.allotment_class_id = ? AND a.financial_year_id = ?
                ", [
                    $item['allotment_class_id'], 
                    $item['financial_year_id']
                ]);

                $data[] = [
                    'allotment_class_id' => $item['allotment_class_id'],
                    'allotment_class' => $item['allotment_class'],
                    'doc_type' => $item['doc_type'],
                    'financial_year_id' => $item['financial_year_id'],
                    'approved_budget' => $item['approved_budget'],
                    'amount' => $item['amount'],
                    'utilize_budget' => $item['utilize_budget'],
                    'details' => $accExps
                ];
            }

        }else{
            // $data = DB::select('
            //     SELECT
            //     a.accounting_expenditure_id,
            //     a.accounting_id,
            //     e.doc_type,
            //     a.allotment_class_id,
            //     a.amount,
            //     a.financial_year_id,
            //     c.financial_year_code, c.financial_year_desc,
            //     a.object_expenditure_id,
            //     b.allotment_class_code, b.allotment_class,
            //     d.object_expenditure, d.approved_budget,
            //     d.beginning_budget,
            //     SUM(a.amount) AS utilize_budget

            //     FROM accounting_expenditures a
            //     JOIN allotment_classes b ON a.allotment_class_id = b.allotment_class_id
            //     JOIN financial_years c ON a.financial_year_id = c.financial_year_id
            //     JOIN object_expenditures d ON a.object_expenditure_id = d.object_expenditure_id
            //     JOIN accountings e ON a.accounting_id = e.accounting_id
            //     WHERE e.doc_type = ?
            //     GROUP BY a.allotment_class_id
            // ', [$req->doc]);

            $allotments = AccountingExpenditure::join('allotment_classes', 'accounting_expenditures.allotment_class_id', '=', 'allotment_classes.allotment_class_id')
                ->join('financial_years', 'accounting_expenditures.financial_year_id', '=', 'financial_years.financial_year_id')
                ->join('object_expenditures', 'accounting_expenditures.object_expenditure_id', '=', 'object_expenditures.object_expenditure_id')
                ->join('accountings', 'accounting_expenditures.accounting_id', '=', 'accountings.accounting_id')
                ->select(
                    'accounting_expenditures.accounting_expenditure_id',
                    'accounting_expenditures.accounting_id',
                    'accountings.doc_type',
                    'accounting_expenditures.doc_type as acctg_doc_type',
                    'accounting_expenditures.allotment_class_id',
                    'accounting_expenditures.amount',
                    'accounting_expenditures.financial_year_id',
                    'financial_years.financial_year_code', 
                    'financial_years.financial_year_desc',
                    'accounting_expenditures.object_expenditure_id',
                    'allotment_classes.allotment_class_code', 
                    'allotment_classes.allotment_class',
                    'object_expenditures.object_expenditure', 
                    'object_expenditures.approved_budget',
                    'object_expenditures.beginning_budget',
                    DB::raw('SUM(accounting_expenditures.amount) AS utilize_budget')
                )
                ->where('accountings.doc_type', $req->doc)
                ->where('accounting_expenditures.financial_year_id', $req->fy)
                ->groupBy('accounting_expenditures.allotment_class_id')
                ->get();
            
            foreach($allotments as $item){

                $accExps = DB::select("
                    SELECT
                    a.financial_year_id,
                    a.doc_type,
                    a.allotment_class_id,
                    a.object_expenditure_id,
                    a.amount,
                    b.account_code,
                    b.object_expenditure,
                    b.approved_budget,
                    c.allotment_class_code,
                    c.allotment_class
                    FROM
                    accounting_expenditures a
                    JOIN object_expenditures b ON a.object_expenditure_id = b.object_expenditure_id
                    JOIN allotment_classes c ON b.allotment_class_id = c.allotment_class_id
                    WHERE b.allotment_class_id = ? AND a.financial_year_id = ?
                    AND a.doc_type = ?
                ", [
                    $item['allotment_class_id'], 
                    $item['financial_year_id'],
                    $item['doc_type']
                ]);

                $data[] = [
                    'allotment_class_id' => $item['allotment_class_id'],
                    'allotment_class' => $item['allotment_class'],
                    'doc_type' => $item['doc_type'],
                    'financial_year_id' => $item['financial_year_id'],
                    'approved_budget' => $item['approved_budget'],
                    'amount' => $item['amount'],
                    'utilize_budget' => $item['utilize_budget'],
                    'details' => $accExps
                ];
            }
           
        }
        

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
