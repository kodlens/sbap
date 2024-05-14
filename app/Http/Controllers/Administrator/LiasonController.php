<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Liason;
use Illuminate\Http\Request;

class LiasonController extends Controller
{
    //


    public function index(){
        return view('administrator.liason.liason-index');
    }

    public function show($id){
        return TransactionType::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Liason::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function store(Request $req){

        $req->validate([
            'lname' => ['required'],
            'fname' => ['required'],
            'sex' => ['required'],

        ]);

        Liason::create([
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'sex' => strtoupper($req->sex),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){

        $req->validate([
            'lname' => ['required'],
            'fname' => ['required'],
            'sex' => ['required'],
        ]);

        Liason::where('payee_id', $id)
            ->update([
                'lname' => strtoupper($req->lname),
                'fname' => strtoupper($req->fname),
                'mname' => strtoupper($req->mname),
                'sex' => strtoupper($req->sex),
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Liason::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
