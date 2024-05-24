<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;

class FinancialYearController extends Controller
{
    //
    public function index(){
        return view('administrator.financial_year.financial-year');
    }

    public function show($id){
        return FinancialYear::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);
        return FinancialYear::where('financial_year_desc', 'like', $req->financial_year . '%')
            ->select(
                'financial_year_id',
                'financial_year_code',
                'financial_year_desc',
                'approved_budget',
                'beginning_budget',
                DB::raw('(select sum(amount) from accounting_expenditures where financial_years.financial_year_id = accounting_expenditures.financial_year_id) as utilize_budget'),
                'active',
                'created_at',
                'updated_at'
            )
            ->orWhere('financial_year_code', 'like', $req->financial_year . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function store(Request $req){
        $req->validate([
            'financial_year_code' => ['required', 'unique:financial_years'],
            'financial_year_desc' => ['required'],
            'approved_budget' => ['required'],
            'beginning_budget' => ['required']

        ]);

        FinancialYear::create([
            'financial_year_code' => strtoupper($req->financial_year_code),
            'financial_year_desc' => strtoupper($req->financial_year_desc),
            'approved_budget' => $req->approved_budget,
            'beginning_budget' => $req->beginning_budget,
            'active' => $req->active,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'financial_year_code' => ['required', 'unique:financial_years,financial_year_code,'.$id.',financial_year_id'],
            'financial_year_desc' => ['required'],
            'approved_budget' => ['required'],
            'beginning_budget' => ['required']
        ]);

        $data = FinancialYear::find($id);
        $data->financial_year_code = strtoupper($req->financial_year_code);
        $data->financial_year_desc = strtoupper($req->financial_year_desc);
        $data->approved_budget = $req->approved_budget;
        $data->beginning_budget = $req->beginning_budget;
        $data->active = $req->active;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function setActive($id){

        DB::table('financial_years')
            ->where('active', 1)
            ->update([
                'active' => 0
            ]);

        DB::table('financial_years')
            ->where('financial_year_id', $id)
            ->update([
                'active' => 1
            ]);

        return response()->json([
            'status' => 'active'
        ], 200);
    }

    public function destroy($id){
        FinancialYear::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

  

}
