<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class RealignmentController extends Controller
{
    //

    public function index(){
        $user = Auth::user();
        return view('realignment.realignment-index')
            ->with('user', $user);
    }
}
