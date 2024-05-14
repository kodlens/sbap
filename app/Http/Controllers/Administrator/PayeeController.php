<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payee;

class PayeeController extends Controller
{
    //

    public function index(){
        return view('administrator.supplierpayee.supplier-payee');
    }

    public function show($id){
        return Payee::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Payee::where('bank_account_payee', 'like', $req->payee . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function getModalPayee(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Payee::where('bank_account_payee', 'like', $req->payee . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function store(Request $req){

        $req->validate([
            'bank_account_payee' => ['required', 'unique:payee'],
            // 'owner' => ['required'],
            // 'tin' => ['required'],
            // 'contact_no' => ['required'],
            // 'email' => ['required'],
        ]);

        Payee::create([
            'bank_account_payee' => strtoupper($req->bank_account_payee),
            'owner' => strtoupper($req->owner),
            'tin' => strtoupper($req->tin),
            'contact_no' => strtoupper($req->contact_no),
            'email' => $req->email,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){

        $req->validate([
            'bank_account_payee' => ['required', 'unique:payee,bank_account_payee,'. $id . ',payee_id'],
            'owner' => ['required'],
            'tin' => ['required'],
            'contact_no' => ['required'],
            'email' => ['required'],
        ]);

        Payee::where('payee_id', $id)
            ->update([
                'bank_account_payee' => strtoupper($req->bank_account_payee),
                'owner' => strtoupper($req->owner),
                'tin' => strtoupper($req->tin),
                'contact_no' => strtoupper($req->contact_no),
                'email' => $req->email,
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Payee::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
