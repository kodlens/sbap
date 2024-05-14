<?php

namespace App\Http\Controllers\Modal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class ModalCoursesController extends Controller
{
    //

    public function getModalCoursesList(Request $req){
        $sort = explode('.', $req->sort_by);

        return Course::where('course_code', 'like', $req->coursecode . '%')
            ->where('course_desc', 'like', $req->coursedesc . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }
}
