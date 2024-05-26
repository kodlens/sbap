<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\FinancialYear;
use App\Models\Realignment;

use App\Models\ObjectExpenditure;

class RealignmentController extends Controller
{
    //

    public function index(){
        $user = Auth::user();
        return view('realignment.realignment-index')
            ->with('user', $user);
    }

    public function getData(Request $req){

        $fy = FinancialYear::where('active', 1)->first();
        $sort = explode('.', $req->sort_by);

        $data = Realignment::with(['financial_year', 'object_expenditure_from',
            'object_expenditure_to'])
            ->where('financial_year_id', $fy->financial_year_id)
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function create(){
        $user = Auth::user()
            ->only('user_id', 'lname', 'fname', 'mname', 'sex', 'role'); 
        return view('realignment.realignment-create')
            ->with('id', 0)
            ->with('user', $user);
    }

    public function edit($id){
        $user = Auth::user(); 
        return view('realignment.realignment-create')
            ->with('id', $id)
            ->with('user', $user);
    }

    public function store(Request $req){
        

        $req->validate([
            'object_expenditure_id_from' => ['required', 'not_in:0'],
            'object_expenditure_id_to' => ['required', 'not_in:0'],
            'financial_year_id' => ['required'],
            'amount_transfer' => ['required', 'not_in:0'],
            'balance' => ['required', 'numeric', 'not_in:0', 
                function ($attribute, $value, $fail) use ($req) {
                    if ($value < $req->amount_transfer) {
                        $fail('The amount transfer must be less than the balance.');
                    }
                }
            ],
            'remarks' => ['required']
        ],
        [   
            'financial_year_id.required' => 'Please select financial year.',
            'object_expenditure_id_from.not_in' => 'Please select Object Expenditure',
            'object_expenditure_id_to.not_in' => 'Please select Object Expenditure',
            'amount_transfer.not_in' => 'Please input amount to transfer'
        ]);


        $objexpFrom = ObjectExpenditure::find($req->object_expenditure_id_from);
        $objexpFrom->decrement('approved_budget', $req->amount_transfer);
        //$objexp->decrement('beginning_budget', $req->amount_transfer);
        $objexpFrom->save();


        $objexpTo = ObjectExpenditure::find($req->object_expenditure_id_to);
        $objexpTo->increment('approved_budget', $req->amount_transfer);
        //$objexp->decrement('beginning_budget', $req->amount_transfer);
        $objexpTo->save();

        //return $objexp;


        Realignment::create([
            'financial_year_id' => $req->financial_year_id,
            'remarks' => strtoupper($req->remarks),
            'object_expenditure_id_from' => $req->object_expenditure_id_from,
            'object_expenditure_id_to' => $req->object_expenditure_id_to,
            'amount_transfer' => $req->amount_transfer,

        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

}
