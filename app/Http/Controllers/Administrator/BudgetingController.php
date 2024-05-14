<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\AccountingAllotmentClasses;
use Illuminate\Http\Request;
use App\Models\Accounting;
use App\Models\FinancialYear;
use App\Models\AccountingDocumentaryAttachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\PriorityProgram;
use App\Models\AllotmentClass;
use App\Models\AllotmentClassAccount;
use App\Models\Service;

class BudgetingController extends Controller
{
    //


    public function index(){
        return view('administrator.budgeting.budgeting-index');
    }


    public function show($id){
        $data = Accounting::with(['payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_allotment_classes.allotment_class', 'accounting_allotment_classes.allotment_class_account',
            'priority_program', 'office'
        ])
            ->find($id);

        return $data;
    }


    public function getData(Request $req){

        $sort = explode('.', $req->sort_by);

        $data = Accounting::with(['fund_source', 'payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_allotment_classes', 'processor'])
        ->where(function($q) use ($req){
            $q->where('particulars', 'like', $req->key . '%')
                ->orWhere('transaction_no', 'like', $req->key . '%')
                ->orWhere('training_control_no', 'like', $req->key . '%');
        })
        ->where('doc_type', 'BUDGETING')
        ->orderBy($sort[0], $sort[1])
        ->paginate($req->perpage);

        return $data;
    }


    public function create(){
        return view('administrator.budgeting.budgeting-create-edit')
            ->with('id', 0);
    }


    public function edit($id){
        return view('administrator.budgeting.budgeting-create-edit')
            ->with('id', $id);
    }




    public function store(Request $req){
        //return $req->allotment_classes;
        //return $req;
        $req->validate([
            'financial_year_id' => ['required'],
            'fund_source_id' => ['required'],
            'date_time' => ['required'],
            'transaction_no' => ['required'],
            'training_control_no' => ['required'],
            'transaction_type_id' => ['required'],
            'payee_id' => ['required'],
            'particulars' => ['required'],
            'total_amount' => ['required'],
            'office_id' => ['required']

        ],[
            'financial_year_id.required' => 'Please select financial year.',
            'transaction_type_id.required' => 'Please select transaction.',
            'payee_id.required' => 'Please select bank account/payee.',
            'allotment_class_id.required' => 'Please allotment class.',
            'allotment_class_account_id.required' => 'Please allotment class account.',
            'office.required' => 'Please select office.',

        ]);

        $data = Accounting::create([
            'financial_year_id' => $req->financial_year_id,
            'doc_type' => 'BUDGETING',
            'fund_source_id' => $req->fund_source_id,
            'date_time' => $req->date_time,
            'transaction_no' => $req->transaction_no,
            'training_control_no' => $req->training_control_no,
            'transaction_type_id' => $req->transaction_type_id,
            'payee_id' => $req->payee_id,
            'particulars' => $req->particulars,
            'total_amount' => (float)$req->total_amount,
            'priority_program_id' => $req->priority_program_id,
            'others' => $req->others,
            'office_id' => $req->office_id

        ]);

        $finance = FinancialYear::find($req->financial_year_id);
        $finance->decrement('balance', (float)$req->total_amount);
        $finance->save();

        $service = Service::where('financial_year_id', $req->financial_year_id)
            ->where('service', 'BUDGETING')->first();
        $service->decrement('balance', (float)$req->total_amount);
        $service->save();

        $pp = PriorityProgram::find($req->priority_program_id);
        $pp->decrement('priority_program_balance', (float)$req->total_amount);
        $pp->save();

        
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
        if($req->has('allotment_classes')){
            $allotmentClasses = [];
            foreach ($req->allotment_classes as $item) {
                $allotmentClasses[] = [
                    'accounting_id' => $accountingId,
                    'allotment_class_id' => $item['allotment_class_id'],
                    'allotment_class_account_id' => $item['allotment_class_account_id'],
                    'amount' => $item['amount'],
                ];
            
                $data = AllotmentClassAccount::find($item['allotment_class_account_id']);
                $data->decrement('allotment_class_account_balance', $item['amount']);
                $data->save();

                $data = AllotmentClass::find($item['allotment_class_id']);
                $data->decrement('allotment_class_balance', $item['amount']);
                $data->save();
            }

            AccountingAllotmentClasses::insert($allotmentClasses);
        }

         return response()->json([
             'status' => 'saved'
         ], 200);

    }


    public function updateBudgeting(Request $req, $id){

        $req->validate([
            'financial_year_id' => ['required'],
            'fund_source_id' => ['required'],
            'date_time' => ['required'],
            'transaction_no' => ['required'],
            'training_control_no' => ['required'],
            'transaction_type_id' => ['required'],
            'payee_id' => ['required'],
            'particulars' => ['required'],
            'total_amount' => ['required'],
            'office_id' => ['required']
        ],[
            'financial_year_id.required' => 'Please select financial year.',
            'fund_source_id.required' => 'Please select financial year.',
            'transaction_type_id.required' => 'Please select transaction.',
            'payee_id.required' => 'Please select bank account/payee.',
            'office_id.required' => 'Please select office.'
        ]);

        $data = Accounting::find($id);

        $data->financial_year_id = $req->financial_year_id;
        $data->fund_source_id =  $req->fund_source_id;
        $data->date_time =  $req->date_time;
        $data->transaction_no =  $req->transaction_no;
        $data->training_control_no =  $req->training_control_no;
        $data->transaction_type_id =  $req->transaction_type_id;
        $data->payee_id =  $req->payee_id;
        $data->particulars =  $req->particulars;
        $data->total_amount =  $req->total_amount;
        $data->priority_program_id =  $req->priority_program_id ? $req->priority_program_id : null;
        $data->office_id =  $req->office_id;
        $data->others =  $req->others;
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


        if($req->has('allotment_classes')){
            foreach ($req->allotment_classes as $item) {
                AccountingAllotmentClasses::updateOrCreate([
                    'accounting_allotment_class_id' => $item['accounting_allotment_class_id']
                ],[
                    'accounting_id' => $id,
                    'allotment_class_id' => $item['allotment_class_id'],
                    'allotment_class_account_id' => $item['allotment_class_account_id'],
                    'amount' => $item['amount'],
                ]);
            }
        }

       return response()->json([
           'status' => 'updated'
       ], 200);

    }



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
    //for excel
    public function fetchAccountings(){

        return DB::select('
        SELECT
            a.`accounting_id`,
            b.`financial_year_code`,
            b.`financial_year_desc`,
            c.`fund_source`,
            a.`transaction_no`,
            a.`training_control_no`,
            d.`transaction_type`,
            e.`bank_account_payee`,
            a.`total_amount`,
            gg.`allotment_class`,
            hh.`allotment_class_account_code`,
            hh.`allotment_class_account`,
            g.`amount`,
            h.`priority_program_code`,
            h.`priority_program`,
            f.`office`

            FROM accountings a
            JOIN `financial_years` b ON a.`financial_year_id` = b.`financial_year_id`
            JOIN fund_sources c ON a.`fund_source_id` = c.`fund_source_id`
            JOIN `transaction_types` d ON a.`transaction_type_id` = d.`transaction_type_id`
            JOIN payee AS e ON a.`payee_id` = e.`payee_id`
            JOIN offices f ON a.`office_id` = f.`office_id`
            LEFT JOIN `accounting_allotment_classes` g ON a.`accounting_id` = g.`accounting_id`
            LEFT JOIN `allotment_classes` gg ON g.`allotment_class_id` = gg.`allotment_class_id`
            LEFT JOIN `allotment_class_accounts` hh ON g.`accounting_allotment_class_id` = hh.`allotment_class_account_id`
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
