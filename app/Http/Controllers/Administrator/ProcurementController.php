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
            'accounting_expenditures.object_expenditure', 'office'
        ])
            ->find($id);

        return $data;
    }

    public function getData(Request $req){
        $fy = FinancialYear::where('active', 1)->first();
        $sort = explode('.', $req->sort_by);

        $data = Accounting::with(['payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_expenditures.object_expenditure', 'processor'])
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
                        'allotment_class' => $item['allotment_class'],
                        'financial_year_id' => $financialYearId,
                        'allotment_class_code' => $item['allotment_class_code'],
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
                            'procurement_id' => $data->procurement_id,
                            'documentary_attachment_id' => $item['documentary_attachment_id'],
                            'doc_attachment' => is_file($item['file_upload']) ? $path : $data->doc_attachment
                        ]);

                }
                //insert into database after upload 1 image

                if($req->has('allotment_classes')){
                    foreach ($req->allotment_classes as $item) {
                        ProcurementAllotmentClass::updateOrCreate([
                            'procurement_allotment_class_id' => $item['procurement_allotment_class_id']
                            //wala siay ID or 0 id
                        ],[
                            'procurement_id' => $id,
                            'allotment_class_id' => $item['allotment_class_id'],
                            'allotment_class_account_id' => $item['allotment_class_account_id'],
                            'amount' => $item['amount'],
                        ]);
                    }
                }

            }
        }

        $accountingId = $data->accounting_id;
        $financialYearId = $req->financial_year_id;

        if($req->has('object_expenditures')){
            $object_expenditures = [];
            foreach ($req->object_expenditures as $item) {
                AccountingExpenditure::updateOrCreate([
                    'accounting_expenditure_id' => $item['accounting_expenditure_id']
                ],
                [
                    'accounting_id' => $accountingId,
                    'allotment_class' => $item['allotment_class'],
                    'financial_year_id' => $financialYearId,
                    'allotment_class_code' => $item['allotment_class_code'],
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

        return DB::select('
            SELECT
                a.`procurement_id`,
                b.`financial_year_code`,
                b.`financial_year_desc`,
                c.`fund_source`,
                a.`training_control_no`,
                a.`pr_no`,
                a.`pr_status`,
                e.`bank_account_payee`,
                a.`pr_amount`,
                gg.allotment_class_id,
                hh.`allotment_class_account_code`,
                hh.`allotment_class_account`,
                g.`amount`,
                h.`priority_program_code`,
                h.`priority_program`,
                f.`office`

                FROM procurements a
                JOIN `financial_years` b ON a.`financial_year_id` = b.`financial_year_id`
                JOIN fund_sources c ON a.`fund_source_id` = c.`fund_source_id`
                JOIN payee AS e ON a.`payee_id` = e.`payee_id`
                JOIN offices f ON a.`office_id` = f.`office_id`
                LEFT JOIN procurement_allotment_classes g ON a.`procurement_id` = g.procurement_id
                LEFT JOIN `allotment_classes` gg ON g.`allotment_class_id` = gg.`allotment_class_id`
                LEFT JOIN allotment_class_accounts hh ON g.`allotment_class_account_id` = hh.`allotment_class_account_id`
                LEFT JOIN priority_programs h ON a.`priority_program_id` = h.`priority_program_id`
            ');
    }


    public function destroy($id){
        Accounting::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
