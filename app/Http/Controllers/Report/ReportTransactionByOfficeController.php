<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelName;
use Illuminate\Support\Facades\DB;


class ReportTransactionByOfficeController extends Controller
{
    //

    public function index(){
        return view('report.report-transcation-by-office');
    }


    public function loadReportTransacationByOffice(Request $req){
        $officeId = $req->office;
        $fy = $req->fy;

        $data = DB::select("
            SELECT
            a.`accounting_id`,
            a.doc_type,
            b.`financial_year_code`,
            b.`financial_year_desc`,
            a.`pr_no`,
            a.transaction_no,
            a.`training_control_no`,
            d.`bank_account_payee`,
            a.`total_amount`,
            e.`office`,
            h.allotment_class_code, h.allotment_class,
            g.account_code, g.object_expenditure,
            SUM(f.amount) AS 'total_utilize',
            g.approved_budget, g.beginning_budget
            
            FROM accountings a
            JOIN `financial_years` b ON a.`financial_year_id` = b.`financial_year_id`
            JOIN payee AS d ON a.`payee_id` = d.`payee_id`
            JOIN offices e ON a.`office_id` = e.`office_id`
            LEFT JOIN accounting_expenditures f ON a.accounting_id = f.accounting_id
            LEFT JOIN object_expenditures g ON f.object_expenditure_id = g.object_expenditure_id
            LEFT JOIN allotment_classes h ON f.allotment_class_id = h.allotment_class_id
            WHERE a.office_id = ?
            AND a.financial_year_id = ?
            GROUP BY a.office_id, a.doc_type
            
        ", [$officeId, $fy]);

        return $data;
    }
}
