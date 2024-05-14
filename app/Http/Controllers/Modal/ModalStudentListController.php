<?php

namespace App\Http\Controllers\Modal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ModalStudentListController extends Controller
{
    //

    public function getModalStudentList(Request $req){
       
        $sort = explode('.', $req->sort_by);

        $std = User::with(['office', 'program'])
            ->where('role', 'STUDENT')
            ->where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $std;
        
    }

}
