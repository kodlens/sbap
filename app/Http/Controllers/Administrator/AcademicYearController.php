<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AcademicYear;


class AcademicYearController extends Controller
{
    //

    public function index(){
        return view('administrator.academic_year.academic-year');
    }

    public function show($id){
        return AcademicYear::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);
        return AcademicYear::where('academic_year_desc', 'like', $req->academic_year . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function store(Request $req){
        $req->validate([
            'academic_year_code' => ['required', 'unique:academic_years'],
            'academic_year_desc' => ['required']
        ]);

        AcademicYear::create([
            'academic_year_code' => strtoupper($req->academic_year_code),
            'academic_year_desc' => strtoupper($req->academic_year_desc),
            'active' => $req->active,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'academic_year_code' => ['required', 'unique:academic_years,academic_year_code,'.$id.',academic_year_id'],
            'academic_year_desc' => ['required']
        ]);

        $data = AcademicYear::find($id);
        $data->academic_year_code = strtoupper($req->academic_year_code);
        $data->academic_year_desc = strtoupper($req->academic_year_desc);
        $data->active = $req->active;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function setACtive($ayId){

        DB::table('academic_years')
            ->where('active', 1)
            ->update([
                'active' => 0
            ]);

        DB::table('academic_years')
            ->where('acadyear_id', $ayId)
            ->update([
                'active' => 1
            ]);

        return response()->json([
            'status' => 'active'
        ], 200);
    }

    public function destroy($id){
        AcademicYear::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
