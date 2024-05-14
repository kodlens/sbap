<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office;

class OfficeController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        return view('administrator.office.office');
    }

    public function show($id){
        return Office::find($id);
    }

    public function getOffices(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Office::where('office', 'like', $req->office . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

    public function getOfficesForRoutes(){
        return Office::all();
    }


    public function store(Request $req){
        $req->validate([
            'office' => ['required']
        ]);

        Office::create([
            'office' => strtoupper($req->office)
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'office' => ['required', 'unique:offices,office,'.$id.',office_id']
        ]);

        $data = Office::find($id);
        $data->office = strtoupper($req->office);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }



    public function destroy($id){
        Office::destroy($id);


        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
