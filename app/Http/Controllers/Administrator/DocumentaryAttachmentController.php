<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentaryAttachment;

class DocumentaryAttachmentController extends Controller
{
    //

    public function index(){
        return view('administrator.documentary_attachment.documentary-attachment');
    }

    public function show($id){
        return DocumentaryAttachment::find($id);
    }

    public function getData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = DocumentaryAttachment::where('documentary_attachment', 'like', $req->documentary_attachment . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function store(Request $req){

        $req->validate([
            'documentary_attachment' => ['required', 'unique:documentary_attachments'],
        ]);

        DocumentaryAttachment::create([
            'documentary_attachment' => strtoupper($req->documentary_attachment),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){

        $req->validate([
            'documentary_attachment' => ['required', 'unique:documentary_attachments,documentary_attachment,'. $id . ',documentary_attachment_id'],
        ]);

        DocumentaryAttachment::where('documentary_attachment_id', $id)
            ->update([
                'documentary_attachment' => strtoupper($req->documentary_attachment),
            ]);

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        DocumentaryAttachment::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
