<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liaison extends Model
{
    use HasFactory;

    protected $table = 'liaisons';
    protected $primaryKey = 'liaison_id';

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'sex',
    ];

}
