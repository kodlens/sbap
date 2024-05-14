<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;

class OpenModalResourcesController extends Controller
{
    //



    public function getModalOffices(Request $req){
        return Office::where('office', 'like', $req->office .'%')
            ->orderBy('office', 'desc')
            ->paginate();
    }


}
