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

        $data = DB::select('
            SELECT
            g.service,
            g.budget as service_budget,
            g.balance as service_balance,
            b.doc_type,
            b.transaction_no,
            e.financial_year_id,
            e.financial_year_code,
            e.financial_year_desc,
            e.financial_budget,
            e.balance,
            h.fund_source,

            a.amount,
            a.allotment_class_id,
            c.allotment_class,
            a.allotment_class_account_id,
            d.allotment_class_account_code,
            d.allotment_class_account,
            c.allotment_class_budget,
            c.allotment_class_balance,
            d.allotment_class_account_budget,
            d.allotment_class_account_balance,
            f.priority_program,
            f.priority_program_code,
            f.priority_program_budget,
            f.priority_program_balance
            
            FROM
            accounting_allotment_classes a
            JOIN accountings b ON a.accounting_id = b.accounting_id
            JOIN allotment_classes c ON a.allotment_class_id = c.allotment_class_id
            JOIN allotment_class_accounts d ON a.allotment_class_account_id = d.allotment_class_account_id
            JOIN financial_years e ON b.financial_year_id = e.financial_year_id
            JOIN priority_programs f ON b.priority_program_id = f.priority_program_id
            LEFT JOIN services g ON b.doc_type = g.service
            LEFT JOIN fund_sources h ON h.fund_source_id = b.fund_source_id
            WHERE b.financial_year_id = ? AND b.office_id = ?
            ', [$fy, $officeId]);

        return $data;
    }
}
