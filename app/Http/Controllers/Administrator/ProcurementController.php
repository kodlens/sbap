<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AccountingDocumentaryAttachment;
use Illuminate\Http\Request;

use App\Models\Accounting;

use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\AccountingExpenditure;


class ProcurementController extends Controller
{
    //



    public function index(){
        return view('administrator.procurement.procurement-index');
    }


    public function show($id){
      
        $data = Accounting::with(['payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_expenditures.object_expenditure.allotment_class', 'office'
        ])
            ->find($id);

        return $data;
    }

    public function getData(Request $req){
        $fy = FinancialYear::where('active', 1)->first();
        $sort = explode('.', $req->sort_by);

        $data = Accounting::with(['payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_expenditures.object_expenditure.allotment_class', 'processor'])
            ->where(function($q) use ($req){
                $q->where('particulars', 'like', $req->key . '%')
                    ->orWhere('transaction_no', 'like', $req->key . '%')
                    ->orWhere('training_control_no', 'like', $req->key . '%');
            })
            ->where('doc_type', 'PROCUREMENT')
            ->where('financial_year_id', $fy->financial_year_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function create(){
        return view('administrator.procurement.procurement-create-edit')
            ->with('id', 0);
    }


    public function edit($id){
        return view('administrator.procurement.procurement-create-edit')
            ->with('id', $id);
    }


    public function store(Request $req){
        //return $req;

        $req->validate([
            'financial_year_id' => ['required'],
            'date_transaction' => ['required'],
            'training_control_no' => ['required'],
            'pr_no' => ['required'],
            'particulars' => ['required'],
            'pr_amount' => ['required'],
            'payee_id' => ['required'],
            'office_id' => ['required'],
        ],[
            'payee_id.required' => 'Please select bank account/payee.',
            'office_id.required' => 'Please select office.'
        ]);


        DB::beginTransaction();

        try{
            $data = Accounting::create([
                'financial_year_id' => $req->financial_year_id,
                'doc_type' => 'PROCUREMENT',
                'date_transaction' => $req->date_transaction,
                'training_control_no' => $req->training_control_no,
                'pr_no' => strtoupper($req->pr_no),
                'particulars' => $req->particulars,
                'total_amount' => (float)$req->pr_amount, //PR AMOUNT (total amount)
                'payee_id' => $req->payee_id,
                'pr_status' => $req->pr_status,
                'others' => $req->others,
                'office_id' => $req->office_id
            ]);
    
         
            if($req->has('documentary_attachments')){
                foreach ($req->documentary_attachments as $item) {
                    $n = [];
                    if($item['file_upload']){
                        $pathFile = $item['file_upload']->store('public/doc_attachments'); //get path of the file
                        $n = explode('/', $pathFile); //split into array using /
                    }
    
                    //insert into database after upload 1 image
                    AccountingDocumentaryAttachment::create([
                        'accounting_id' => $data->accounting_id,
                        'documentary_attachment_id' => $item['documentary_attachment_id'],
                        'doc_attachment' => $n[2]
                    ]);
                }
            }
    
            $accountingId = $data->accounting_id;
            $financialYearId = $req->financial_year_id;
    
            if($req->has('object_expenditures')){
                $object_expenditures = [];
                foreach ($req->object_expenditures as $item) {
                    $object_expenditures[] = [
                        'accounting_id' => $accountingId,
                        'doc_type' => 'PROCUREMENT',
                        'financial_year_id' => $financialYearId,
                        'allotment_class_id' => $item['allotment_class_id'],
                        'object_expenditure_id' => $item['object_expenditure_id'],
                        'amount' => $item['amount'],
                    ];
                }
                AccountingExpenditure::insert($object_expenditures);
            }

            DB::commit();
    
           return response()->json([
               'status' => 'saved'
           ], 200);
        }catch (Exception $e) {
            // Handle the exception
            // Log error, return response, etc.
            DB::rollBack();

             return response()->json([
                'status' => 'error',
                'message' => $e
            ], 500);
        }
    }


    public function updateProcurement(Request $req, $id){
        //return $req;

        $req->validate([
            'financial_year_id' => ['required'],
            'date_transaction' => ['required'],
            'training_control_no' => ['required'],
            'pr_no' => ['required'],
            'particulars' => ['required'],
            'pr_amount' => ['required'],
            'payee_id' => ['required'],
            'office_id' => ['required'],
        ],[
            'payee_id.required' => 'Please select bank account/payee.',
            'office_id.required' => 'Please select office.'
        ]);


        $data = Accounting::find($id);
        $data->financial_year_id = $req->financial_year_id;
        $data->date_transaction =  $req->date_transaction;
        $data->training_control_no =  $req->training_control_no;
        $data->payee_id =  $req->payee_id;
        $data->particulars =  $req->particulars;
        $data->pr_no =  $req->pr_no;
        $data->total_amount =  $req->pr_amount;
        $data->pr_status =  $req->pr_status;
        $data->others =  $req->others;
        $data->office_id =  $req->office_id;
        $data->save();

        if($req->has('documentary_attachments')){
            foreach ($req->documentary_attachments as $item) {

                $path = null;
                if($item['file_upload'] && is_file($item['file_upload'])){

                    $pathFile = $item['file_upload']->store('public/doc_attachments'); //get path of the file
                    $n = explode('/', $pathFile); //split into array using /
                    $path = $n[2];
                    ProcurementDocumentaryAttachment::create(
                        [
                            'accounting_id' => $data->accounting_id,
                            'documentary_attachment_id' => $item['documentary_attachment_id'],
                            'doc_attachment' => is_file($item['file_upload']) ? $path : $data->doc_attachment
                        ]);

                }
                //insert into database after upload 1 image
            }
        }

        $accountingId = $data->accounting_id;
        $financialYearId = $req->financial_year_id;

        if($req->has('object_expenditures')){
            foreach ($req->object_expenditures as $item) {
                AccountingExpenditure::updateOrCreate([
                    'accounting_expenditure_id' => $item['accounting_expenditure_id']
                ],
                [
                    'accounting_id' => $accountingId,
                    'doc_type' => 'PROCUREMENT',
                    'financial_year_id' => $financialYearId,
                    'allotment_class_id' => $item['allotment_class_id'],
                    'object_expenditure_id' => $item['object_expenditure_id'],
                    'amount' => $item['amount'],
                ]);

            }
        }


       return response()->json([
           'status' => 'updated'
       ], 200);

    }




    //delete attachment and image from storage
    public function deleteProcurementDocAttachment($id){
        $data = AccountingDocumentaryAttachment::find($id);
        // $attchments = AccountingDocumentaryAttachment::where('accounting_id', $data->accounting_id)
        //         ->get();
        if(Storage::exists('public/accounting_attachments/' . $data->doc_attachment)) {
            Storage::delete('public/accounting_attachments/' . $data->doc_attachment);
        }
        AccountingDocumentaryAttachment::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ],200);

    }




    //for excel
    public function fetchProcurements(){

        return DB::select("
            SELECT
            a.`accounting_id` AS 'REFERENCE',
            b.`financial_year_code` AS 'FINANCIAL YEAR CODE',
            b.`financial_year_desc` AS 'FINANCIAL YEAR DESCRIPTION',
            a.`pr_no` AS 'PR NO',
            a.`training_control_no` AS 'TRAINING CONTROL NO',
            d.`bank_account_payee` AS 'PAYEE',
            a.`total_amount` AS 'PR TOTAL AMOUNT',
            e.`office` AS 'OFFICE',
            h.allotment_class_code AS 'ALLOTMENT CLASS CODE', h.allotment_class AS 'ALLOTMENT CLASS',
            g.account_code AS 'ACCOUNT CODE', g.object_expenditure AS 'OBJECT EXPENDITURE',
            f.amount AS 'AMOUNT',
            g.approved_budget AS 'APPROVED BUDGET', g.beginning_budget AS 'BEGINNING BUDGET'
            
            FROM accountings a
            JOIN `financial_years` b ON a.`financial_year_id` = b.`financial_year_id`
            JOIN payee AS d ON a.`payee_id` = d.`payee_id`
            JOIN offices e ON a.`office_id` = e.`office_id`
            LEFT JOIN accounting_expenditures f ON a.accounting_id = f.accounting_id
            LEFT JOIN object_expenditures g ON f.object_expenditure_id = g.object_expenditure_id
            LEFT JOIN allotment_classes h ON f.allotment_class_id = h.allotment_class_id
            WHERE a.doc_type = 'PROCUREMENT'
            AND a.financial_year_id = (SELECT financial_year_id FROM financial_years WHERE active = 1)
        ");
    }


    public function destroy($id){
        Accounting::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
