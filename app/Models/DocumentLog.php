<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentLog extends Model
{
    use HasFactory;


     
    protected $table = 'document_logs';
    protected $primaryKey = 'document_log_id';


    protected $fillable = [
        'accounting_id',
        'datetime_forwarded',
        'datetime_received',
        'status',
        'remarks',
    ];

}
