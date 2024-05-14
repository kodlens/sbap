<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\TransactionType;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    //

    public function index(){
        return view('administrator.transaction_type.transaction-type');
    }

    public function show($id){
        return TransactionType::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = TransactionType::where('transaction_type', 'like', $req->transaction . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function store(Request $req){

        $req->validate([
            'transaction_type' => ['required', 'unique:transaction_types'],
        ]);

        TransactionType::create([
            'transaction_type' => strtoupper($req->transaction_type),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){

        $req->validate([
            'transaction_type' => ['required', 'unique:transaction_types,transaction_type,'. $id . ',transaction_type_id'],
        ]);

        TransactionType::where('transaction_type_id', $id)
            ->update([
                'transaction_type' => strtoupper($req->transaction_type),
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        TransactionType::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
