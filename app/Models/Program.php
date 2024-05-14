<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey = 'program_id';
    protected $table = 'programs';

    protected $fillable = [
        'program_code' ,'program_desc', 'active'
    ];

}
