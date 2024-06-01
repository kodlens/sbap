<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AllotmentClass;
use Illuminate\Validation\Rule;

class AllotmentClassController extends Controller
{
    //

    public function index(){
        return view('administrator.allotment.allotment-class');
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        return AllotmentClass::where('allotment_class', 'like', '%'. $req->allotment . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function show($id){
        return AllotmentClass::find($id);
    }


    public function store(Request $req){
        //return $req;
        $req->validate([
            'allotment_class' => ['required'],
            'allotment_class_code' => ['required', 'unique:allotment_classes'],
        ]);

        AllotmentClass::create([
            'allotment_class' => strtoupper($req->allotment_class),
            'allotment_class_code' => strtoupper($req->allotment_class_code),

        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){

        $req->validate([
            'allotment_class' => ['required'],
            'allotment_class_code' => ['required', 'unique:allotment_classes,allotment_class_code,' . $id . ',allotment_class_id'],
        ]);

        $data = AllotmentClass::find($id);
        $data->allotment_class = strtoupper($req->allotment_class);
        $data->allotment_class_code = strtoupper($req->allotment_class_code);
        $data->save();

        return response()->json([
        'status' => 'updated'
        ], 200);
    }



    public function destroy($id){
        AllotmentClass::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
