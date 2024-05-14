<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementDocumentaryAttachment extends Model
{
    use HasFactory;

    protected $table = 'procurement_documentary_attachments';
    protected $primaryKey = 'procurement_documentary_attachment_id';


    protected $fillable = [
        'procurement_id',
        'documentary_attachment_id',
        'documentary_attachment',
        'doc_attachment',
       
    ];

    
    public function documentary_attachment(){
        return $this->hasOne(DocumentaryAttachment::class, 'documentary_attachment_id', 'documentary_attachment_id');
    }


}
