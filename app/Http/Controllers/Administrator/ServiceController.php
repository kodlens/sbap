<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    //

    public function index(){
        return view('administrator.service.service-index');
    }


    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        return Service::with(['financial_year'])
            ->whereHas('financial_year', function($q)use($req){
                $q->where('financial_year_id', $req->financial);
            })
            ->where('service', 'like', '%'. $req->service . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function show($id){
        return Service::with(['financial_year'])
            ->find($id);
    }


    public function store(Request $req){

        $req->validate([
            'budget' => ['required'],
            'service' => ['required'],
            'financial_year_id' => ['required']

        ]);

        Service::create([
            'financial_year_id' => $req->financial_year_id,
            'budget' => $req->budget,
            'balance' => $req->budget,
            'service' => strtoupper($req->service),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){
        $req->validate([
            'budget' => ['required'],
            'service' => ['required'],
            'financial_year_id' => ['required']

        ]);

        $data = Service::find($id);
        $data->financial_year_id = $req->financial_year_id;
        $data->budget = $req->budget;
        $data->balance= $req->balance;
        $data->service = strtoupper($req->service);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    

    public function destroy($id){
        Service::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
