<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\ObjectExpenditure;
use App\Models\AugmentationBudget;
use Auth;


class AugmentationBudgetController extends Controller
{
    //

    public function index(){
        $user = Auth::user();
        return view('augmentation-budget.augmentation-budget-index')
            ->with('user', $user);
    }

    public function getData(Request $req){

        $fy = FinancialYear::where('active', 1)->first();
        $sort = explode('.', $req->sort_by);

        $data = AugmentationBudget::with(['financial_year', 'object_expenditure'])
            ->where('financial_year_id', $fy->financial_year_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function create(){
        $user = Auth::user()
            ->only('user_id', 'lname', 'fname', 'mname', 'sex', 'role'); 
        return view('augmentation-budget.augmentation-budget-create-edit')
            ->with('id', 0)
            ->with('user', $user);
    }

    public function edit($id){
        $user = Auth::user(); 
        return view('augmentation-budget.augmentation-budget-create-edit')
            ->with('id', $id)
            ->with('user', $user);
    }

    public function store(Request $req){
        
       

        $req->validate([
            'object_expenditure_id' => ['required', 'not_in:0'],
            'financial_year_id' => ['required'],
            'amount_transfer' => ['required', 'not_in:0'],
            'remarks' => ['required']
        ],
        [   
            'financial_year_id.required' => 'Please select financial year.',
            'object_expenditure_id.not_in' => 'Please select Object Expenditure',
            'amount_transfer.not_in' => 'Please input amount to transfer'
        ]);


        $objexp = ObjectExpenditure::find($req->object_expenditure_id);
        $objexp->increment('approved_budget', $req->amount_transfer);
        //$objexp->decrement('beginning_budget', $req->amount_transfer);
        $objexp->save();

        AugmentationBudget::create([
            'financial_year_id' => $req->financial_year_id,
            'remarks' => strtoupper($req->remarks),
            'object_expenditure_id' => $req->object_expenditure_id,
            'amount_transfer' => $req->amount_transfer,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }
}
