<?php

namespace App\Http\Controllers\Processor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounting;
use App\Models\User;
use Auth;

class DocumentController extends Controller
{
    //
    public function index(){
        return view('processor.document.document-index');
    }



    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);
        $user = Auth::user();
        

        $data = Accounting::with(['fund_source', 'payee', 'accounting_documentary_attachments.documentary_attachment',
            'accounting_allotment_classes', 'processor'])
            ->where(function($q) use ($req){
                $q->where('particulars', 'like', $req->key . '%')
                ->orWhere('transaction_no', 'like', $req->key . '%')
                ->orWhere('training_control_no', 'like', $req->key . '%');
            })
            ->where('processor_id', $user->user_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;

    }

    public function documentMarkReceive($id){
        $data = Accounting::find($id);
        $data->processor_datetime_received = date('Y-m-d H:i');
        $data->save();

        return response()->json([
            'status' => 'received'
        ], 200);
    }

    public function documentMark($option, $id){
        if($option === 'bid_award_forward'){
            $this->bidAwardForward($id);
        }
        if($option === 'bid_award_retrieve'){
            $this->bidAwardRetrieve($id);
        }

        if($option === 'city_budget_forward'){
            $this->cityBudgetForward($id);
        }
        if($option === 'city_budget_retrieve'){
            $this->cityBudgetRetrieve($id);
        }

        if($option === 'city_accounting_forward'){
            $this->cityAccountingForward($id);
        }
        if($option === 'city_accounting_retrieve'){
            $this->cityAccountingRetrieve($id);
        }

        if($option === 'city_treasurer_forward'){
            $this->cityTreasurerForward($id);
        }
        if($option === 'city_treasurer_retrieve'){
            $this->cityTreasurerRetrieve($id);
        }

        if($option === 'college_updated'){
            $this->collegeUpdated($id);
        }
 

        

        return response()->json([
            'status' => 'saved'
        ], 200);
    }




    public function documentBidAwardUpdate(Request $req, $id){

        $data = Accounting::find($id);
        $data->bids_award_status = $req->status;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function documentBidAwardUpdateRemarks(Request $req, $id){

        $data = Accounting::find($id);
        $data->bids_award_remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    //addtional (separate) function to make codes better in view
    public function bidAwardForward($id){
        $data = Accounting::find($id);
        if(!$data->bids_award_datetime_forwarded){
            $data->bids_award_datetime_forwarded = date('Y-m-d H:i');
        }
        $data->save();
    }

    public function bidAwardRetrieve($id){
        $data = Accounting::find($id);
        if(!$data->bids_award_datetime_retrieved){
            $data->bids_award_datetime_retrieved = date('Y-m-d H:i');
        }
        $data->save();
    }


    //citybudget
    public function cityBudgetUpdateStatus(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_budget_status = $req->status;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function cityBudgetUpdateRemarks(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_budget_remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function cityBudgetForward($id){
        $data = Accounting::find($id);
        if(!$data->city_budget_datetime_forwarded){
            $data->city_budget_datetime_forwarded = date('Y-m-d H:i');
        }
        $data->save();
    }

    public function cityBudgetRetrieve($id){
        $data = Accounting::find($id);
        if(!$data->city_budget_datetime_retrieved){
            $data->city_budget_datetime_retrieved = date('Y-m-d H:i');
        }
        $data->save();
    }


    //city accounting
    public function cityAccountingUpdateStatus(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_accounting_status = $req->status;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function cityAccountingUpdateRemarks(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_accounting_remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function cityAccountingForward($id){
        $data = Accounting::find($id);
        if(!$data->city_accounting_datetime_forwarded){
            $data->city_accounting_datetime_forwarded = date('Y-m-d H:i');
        }
        $data->save();
    }

    public function cityAccountingRetrieve($id){
        $data = Accounting::find($id);
        if(!$data->city_accounting_datetime_retrieved){
            $data->city_accounting_datetime_retrieved = date('Y-m-d H:i');
        }
        $data->save();
    }



    //city treasurer
    public function cityTreasurerUpdateStatus(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_treasurer_status = $req->status;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function cityTreasurerUpdateRemarks(Request $req, $id){

        $data = Accounting::find($id);
        $data->city_treasurer_remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function cityTreasurerForward($id){
        $data = Accounting::find($id);
        if(!$data->city_treasurer_datetime_forwarded){
            $data->city_treasurer_datetime_forwarded = date('Y-m-d H:i');
        }
        $data->save();
    }

    public function cityTreasurerRetrieve($id){
        $data = Accounting::find($id);
        if(!$data->city_treasurer_datetime_retrieved){
            $data->city_treasurer_datetime_retrieved = date('Y-m-d H:i');
        }
        $data->save();
    }


    //College Accounting
    public function collegeAccountingUpdateStatus(Request $req, $id){

        $data = Accounting::find($id);
        $data->final_status = $req->status;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
    public function collegeAccountingUpdateRemarks(Request $req, $id){

        $data = Accounting::find($id);
        $data->final_remarks = $req->remarks;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function collegeUpdated($id){
        $data = Accounting::find($id);
        if(!$data->college_accounting_datetime_updated){
            $data->college_accounting_datetime_updated = date('Y-m-d H:i');
        }
        $data->save();
    }

   









}
