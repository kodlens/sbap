<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting;
use App\Models\FinancialYear;
use App\Models\AccountingDocumentaryAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\AllotmentClass;
use App\Models\ObjectExpenditure;
use App\Models\AccountingExpenditure;

class BudgetingController extends Controller
{
    //


    public function index(){
        return view('administrator.budgeting.budgeting-index');
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
            ->where('doc_type', 'BUDGETING')
            ->where('financial_year_id', $fy->financial_year_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function show($id){
        $data = Accounting::with(['payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_expenditures.object_expenditure.allotment_class', 'office'
        ])
            ->find($id);

        return $data;
    }


    public function create(){
        return view('administrator.budgeting.budgeting-create-edit')
            ->with('id', 0);
    }

    public function store(Request $req){
       //return $req->allotment_classes;
        //return $req;

        $req->validate([
            'financial_year_id' => ['required'],
            'date_transaction' => ['required'],
            'transaction_no' => ['required'],
            'training_control_no' => ['required'],
            'transaction_type_id' => ['required'],
            'payee_id' => ['required'],
            'particulars' => ['required'],
            'office_id' => ['required'],
            'object_expenditures' => ['required'],
            'object_expenditures.*' => ['required']
        ],[
            'financial_year_id.required' => 'Please select financial year.',
            'transaction_type_id.required' => 'Please select transaction.',
            'payee_id.required' => 'Please select bank account/payee.',
            'office.required' => 'Please select office.',
        ]);

        DB::beginTransaction();

        try {

            $data = Accounting::create([
                'financial_year_id' => $req->financial_year_id,
                'doc_type' => 'BUDGETING',
                'date_transaction' => $req->date_transaction,
                'transaction_type_id' => $req->transaction_type_id,
                'transaction_no' => $req->transaction_no,
                'training_control_no' => $req->training_control_no,
                'payee_id' => $req->payee_id,
                'particulars' => $req->particulars,
                'total_amount' => (float)$req->total_amount,
                'others' => $req->others,
                'office_id' => $req->office_id,
                'priority_program' => $req->priority_program,

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
                foreach ($req->object_expenditures as $item) {
                    AccountingExpenditure::updateOrCreate([
                        'accounting_expenditure_id' => $item['accounting_expenditure_id']
                    ],
                    [
                        'accounting_id' => $accountingId,
                        'doc_type' => 'BUDGETING',
                        'allotment_class_id' => $item['allotment_class_id'],
                        'financial_year_id' => $financialYearId,
                        'object_expenditure_id' => $item['object_expenditure_id'],
                        'amount' => $item['amount'],
                        'priority_program' => $req->priority_program
                    ]);                    
                }
            }
            DB::commit();
    
            //return $req;
            return response()->json([
                'status' => 'saved'
            ], 200);

        } catch (Exception $e) {
            // Handle the exception
            // Log error, return response, etc.
            DB::rollBack();

             return response()->json([
                'status' => 'error',
                'message' => $e
            ], 500);
        }

        
        
    
    }



    public function edit($id){
        return view('administrator.budgeting.budgeting-create-edit')
            ->with('id', $id);
    }

    public function updateBudgeting (Request $req, $id){
        //return $req;

        $req->validate([
            'financial_year_id' => ['required'],
            'date_transaction' => ['required'],
            'transaction_no' => ['required'],
            'training_control_no' => ['required'],
            'transaction_type_id' => ['required'],
            'payee_id' => ['required'],
            'particulars' => ['required'],
            'office_id' => ['required']
        ],[
            'financial_year_id.required' => 'Please select financial year.',
            'transaction_type_id.required' => 'Please select transaction.',
            'payee_id.required' => 'Please select bank account/payee.',
            'office_id.required' => 'Please select office.'
        ]);

        $data = Accounting::find($id);
        $data->financial_year_id = $req->financial_year_id;
        $data->date_transaction =  $req->date_transaction;
        $data->transaction_no =  $req->transaction_no;
        $data->training_control_no =  $req->training_control_no;
        $data->transaction_type_id =  $req->transaction_type_id;
        $data->payee_id =  $req->payee_id;
        $data->particulars =  $req->particulars;
        $data->total_amount = (float)$req->total_amount;
        $data->office_id =  $req->office_id;
        $data->others =  $req->others;
        $data->priority_program = $req->priority_program;
        


        $data->save();

        if($req->has('documentary_attachments')){
            foreach ($req->documentary_attachments as $item) {

                $path = null;
                if($item['file_upload'] && is_file($item['file_upload'])){

                    $pathFile = $item['file_upload']->store('public/doc_attachments'); //get path of the file
                    $n = explode('/', $pathFile); //split into array using /
                    $path = $n[2];
                    AccountingDocumentaryAttachment::create(
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
        //return $req->object_expenditures;

        if($req->has('object_expenditures')){
            $object_expenditures = [];
            foreach ($req->object_expenditures as $item) {
                AccountingExpenditure::updateOrCreate([
                    'accounting_expenditure_id' => $item['accounting_expenditure_id']
                ],
                [
                    'accounting_id' => $accountingId,
                    'doc_type' => 'BUDGETING',
                    'financial_year_id' => $financialYearId,
                    'allotment_class_id' => $item['allotment_class_id'],
                    'object_expenditure_id' => $item['object_expenditure_id'],
                    'amount' => $item['amount'],
                    'priority_program' => $req->priority_program
                ]);

            }
        }

        return response()->json([
            'status' => 'updated'
        ], 200);

    }



    //delete attachment and image from storage
    public function deleteAcctgDocAttachment($id){

        $data = AccountingDocumentaryAttachment::find($id);

        // $attchments = AccountingDocumentaryAttachment::where('accounting_id', $data->accounting_id)
        //         ->get();
        if(Storage::exists('public/doc_attachments/' . $data->doc_attachment)) {
            Storage::delete('public/doc_attachments/' . $data->doc_attachment);
        }

        AccountingDocumentaryAttachment::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ],200);

    }



    //for excel
    public function fetchBudgetings(){

        return DB::select("
            SELECT
                a.`accounting_id` AS 'REFERENCE',
                b.`financial_year_code` AS 'FINANCIAL YEAR CODE',
                b.`financial_year_desc` AS 'FINANCIAL YEAR DESCRIPTION',
                a.`transaction_no` AS 'TRANSACTION NO',
                a.`training_control_no` AS 'TRAINING CONTROL NO',
                c.`transaction_type` AS 'TRANSACTION TYPE',
                d.`bank_account_payee` AS 'PAYEE',
                a.`total_amount` AS 'TOTAL AMOUNT',
                e.`office` AS 'OFFICE',
                h.allotment_class_code AS 'ALLOTMENT CLASS CODE', h.allotment_class AS 'ALLOTMENT CLASS',
                g.account_code AS 'ACCOUNT CODE', g.object_expenditure AS 'OBJECT EXPENDITURE',
                f.amount AS 'AMOUNT',
                g.approved_budget AS 'APPROVED BUDGET', g.beginning_budget AS 'BEGINNING BUDGET'

                FROM accountings a
                JOIN `financial_years` b ON a.`financial_year_id` = b.`financial_year_id`
                JOIN `transaction_types` c ON a.`transaction_type_id` = c.`transaction_type_id`
                JOIN payee AS d ON a.`payee_id` = d.`payee_id`
                JOIN offices e ON a.`office_id` = e.`office_id`
                LEFT JOIN accounting_expenditures f ON a.accounting_id = f.accounting_id
                LEFT JOIN object_expenditures g ON f.object_expenditure_id = g.object_expenditure_id
                LEFT JOIN allotment_classes h ON f.allotment_class_id = h.allotment_class_id
                WHERE a.doc_type = 'BUDGETING'
                AND a.financial_year_id = (SELECT financial_year_id FROM financial_years WHERE active = 1)

            ");
    }

    public function destroy($id){
        Accounting::destroy($id);


        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
    public function getModalProcessor(Request $req){

        $sort = explode('.', $req->sort_by);

        $data = User::where('role', 'PROCESSOR')
            ->where('lname', 'like', $req->name . '%')
            //->orWhere('fname', 'like', $req->name . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }
    public function assignProcessor(Request $req){

        $req->validate([
            'accounting_id' => ['required'],
            'user_id' => ['required'],
        ]);

        $acctgExpenditures = AccountingExpenditure::where('accounting_id', $id)
            ->get();

        foreach($acctgExpenditures as $item){

            $objExps = ObjectExpenditure::find($item['object_expenditure_id']);
            $objExps->decrement('utilize_budget', $item['amount']);
            $objExps->save();
        }
        

        $data = Accounting::find($req->accounting_id);
        $data->processor_id = $req->user_id;
        $data->save();

        return response()->json([
            'status' => 'assigned'
        ], 200);

    }


}
