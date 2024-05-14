<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AllotmentClassAccount;
use App\Models\AllotmentClass;

class AllotmentClassAccountController extends Controller
{
    //

    public function index(){
        return view('administrator.allotment.allotment-class-account');
    }


    public function show($id){
        return AllotmentClassAccount::with(['allotment_class'])
            ->find($id);
    }

    public function getData(Request $req){
        //return $req;

        $sort = explode('.', $req->sort_by);

        return AllotmentClassAccount::with(['allotment_class.financial_year'])
            ->wherehas('allotment_class', function($q) use ($req){
                $q->where('financial_year_id', $req->financial);
            })
            ->where(function($q) use ($req){
                $q->where('allotment_class_account_code', 'like', '%' . $req->allotment . '%')
                ->orWhere('allotment_class_account', 'like', '%' . $req->allotment . '%');
            })
            //->select('allotment_class_id as id')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }


    public function getModalAllotmentClassAccounts(Request $req){
        $sort = explode('.', $req->sort_by);
        $classId = $req->classid;

        return AllotmentClassAccount::with(['allotment_class'])
            ->where('allotment_class_account_code', 'like', '%' . $req->code . '%')
            ->where('allotment_class_account', 'like', '%' . $req->account . '%')
            ->where('allotment_class_id',  $classId)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }


    public function store(Request $req){
        //return $req;

        $req->validate([
            'allotment_class.*' => ['required'],
            'allotment_class_account_code' => ['required'],
            'allotment_class_account' => ['required'],
        ]);


        $allotmentClassId = $req->allotment_class['allotment_class_id'];

        AllotmentClassAccount::create([
            'allotment_class_id' => $allotmentClassId,
            'allotment_class_account_code' =>strtoupper($req->allotment_class_account_code),
            'allotment_class_account' =>strtoupper($req->allotment_class_account),
            'allotment_class_account_budget' => $req->allotment_class_account_budget,
        ]);

        $allotmentClass = AllotmentClass::find($allotmentClassId);
        $allotmentClass->decrement('allotment_class_budget', $req->allotment_class_account_budget);
        $allotmentClass->save();


        return response()->json([
            'status' => 'saved'
        ], 200);
    }



    public function update(Request $req, $id){

        $req->validate([
            'allotment_class.*' => ['required'],
            'allotment_class_account_code' => ['required'],
            'allotment_class_account' => ['required']
        ]);


        $data = AllotmentClassAccount::find($id);
        $data->allotment_class_id = $req->allotment_class['allotment_class_id'];
        $data->allotment_class_account_code = strtoupper($req->allotment_class_account_code);
        $data->allotment_class_account = strtoupper($req->allotment_class_account);
        $data->allotment_class_account_budget = $req->allotment_class_account_budget;
        $data->save();


        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy(Request $req, $id){
        //return $req;

        $allotmentClassAccount = AllotmentClassAccount::find($id); //call allotment class obj
        $allotmentClassId = $allotmentClassAccount->allotment_class_id;

        $allotmentClass = AllotmentClass::find($allotmentClassId);
        $allotmentClass->increment('allotment_class_budget', $allotmentClassAccount->allotment_class_account_budget);
        $allotmentClass->save();

        AllotmentClassAccount::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }



}
