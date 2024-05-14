<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentaryAttachment extends Model
{
    use HasFactory;

    
    protected $table = 'documentary_attachments';
    protected $primaryKey = 'documentary_attachment_id';


    protected $fillable = ['documentary_attachment'];

}
