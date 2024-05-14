<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payee extends Model
{
    use HasFactory;


    protected $table = 'payee';
    protected $primaryKey = 'payee_id';


    protected $fillable = [
        'bank_account_payee', 
        'owner',
        'tin',
        'contact_no',
        'email',
    ];


}
