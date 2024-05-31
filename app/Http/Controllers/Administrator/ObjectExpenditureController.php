<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ObjectExpenditure;
use Illuminate\Support\Facades\DB;
use App\Models\FinancialYear;

class ObjectExpenditureController extends Controller
{
    //


    public function index(){
        return view('administrator.object-expenditure.object-expenditure');
    }

    public function getData(Request $req){
        $fy = FinancialYear::where('active', 1)->first();

        $sort = explode('.', $req->sort_by);

        return ObjectExpenditure::with(['financial_year', 'allotment_class'])
            ->select(
                'object_expenditure_id',
                'financial_year_id',
                'allotment_class_id',
                'object_expenditure',
                'account_code',
                'approved_budget',
                'beginning_budget',
                DB::raw('(
                        select sum(amount) from accounting_expenditures 
                        where object_expenditures.financial_year_id = accounting_expenditures.financial_year_id
                        and object_expenditures.object_expenditure_id = accounting_expenditures.object_expenditure_id
                    ) as utilize_budget'
                ),
                'created_at',
                'updated_at'
            )
            ->where('object_expenditure', 'like', '%'. $req->allotment . '%')
            ->where('financial_year_id', $fy->financial_year_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function show($id){
        return ObjectExpenditure::with('financial_year')
            ->find($id);
    }



    public function getModalObjectExpenditures(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = ObjectExpenditure::with(['allotment_class'])
            ->where('financial_year_id', $req->financial)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function store(Request $req){
        //return $req;

        $req->validate([
            'financial_year_id' => ['required'],
            'object_expenditure' => ['required'],
            'allotment_class' => ['required']
        ]);

        $exists = ObjectExpenditure::where('object_expenditure', strtoupper($req->object_expenditure))
            ->where('financial_year_id', $req->financial_year_id)
            ->where('allotment_class', strtoupper($req->allotment_class['allotment_class']))
            ->exists();

        if($exists){
            return response()->json([
                
                'errors' => [
                    'object_expenditure' => ['Object of expenditures existed.'],
                    'message' => 'Data cannot accepted.'
                ],
                
            ], 422);
        }

        ObjectExpenditure::create([
            'financial_year_id' => $req->financial_year_id,
            'object_expenditure' => strtoupper($req->object_expenditure),
            'account_code' => strtoupper($req->account_code),
            'allotment_class' => strtoupper($req->allotment_class['allotment_class']),
            'allotment_class_code' => strtoupper($req->allotment_class['allotment_class_code']),
            'approved_budget' => $req->approved_budget,
            'beginning_budget' => $req->beginning_budget,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);

    }

    public function update(Request $req, $id){
        
        $req->validate([
            'financial_year_id' => ['required'],
            'object_expenditure' => ['required'],
            'allotment_class' => ['required']
        ]);

        $exists = ObjectExpenditure::where('object_expenditure', strtoupper($req->object_expenditure))
            ->where('financial_year_id', $req->financial_year_id)
            ->where('allotment_class', strtoupper($req->allotment_class['allotment_class']))
            ->where('object_expenditure_id', '!=', $id)
            ->exists();

        if($exists){
            return response()->json([
                
                'errors' => [
                    'object_expenditure' => ['Object of expenditures existed.'],
                    'message' => 'Data cannot accepted.'
                ],
                
            ], 422);
        }

        ObjectExpenditure::where('object_expenditure_id', $id)
            ->update([
                'financial_year_id' => $req->financial_year_id,
                'object_expenditure' => strtoupper($req->object_expenditure),
                'account_code' => strtoupper($req->account_code),
                'allotment_class' => strtoupper($req->allotment_class['allotment_class']),
                'allotment_class_code' => strtoupper($req->allotment_class['allotment_class_code']),
                'approved_budget' => $req->approved_budget,
                'beginning_budget' => $req->beginning_budget,
            ]);
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        ObjectExpenditure::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
