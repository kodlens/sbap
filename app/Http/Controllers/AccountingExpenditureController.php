<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountingExpenditure;
use App\Models\ObjectExpenditure;
use App\Models\FinancialYear;


class AccountingExpenditureController extends Controller
{
    //


    public function destroy($id){

        $data = AccountingExpenditure::find($id);

        $fy = FinancialYear::find($data->financial_year_id);
        $obj = ObjectExpenditure::find($data->object_expenditure_id);

        if($fy->utilize_budget >= $data->amount){
            $fy->decrement('utilize_budget',$data->amount);
            $fy->save();
        }
        if($obj->utilize_budget >= $data->amount){
            $obj->decrement('utilize_budget',$data->amount);
            $obj->save();
        }

        AccountingExpenditure::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
