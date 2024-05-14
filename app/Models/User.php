<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username', 
        'lname', 
        'fname', 
        'mname', 
        'suffix', 
        'sex', 
        'role',
        'password',
        'province',
        'city',
        'barangay',
        'street'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function office(){
        return $this->hasOne(Office::class, 'office_id', 'office_id');
    }

    public function faculty_loads(){
        return $this->hasMany(FacultyLoad::class, 'user_id', 'user_id')
            ->leftJoin('schedules', 'faculty_loads.schedule_id', 'schedules.schedule_id')
            ->leftJoin('academic_years', 'faculty_loads.academic_year_id', 'academic_years.academic_year_id');

    }

    public function program(){
        return $this->hasOne(Program::class, 'program_id', 'program_id');
    }


    public function province(){
        return $this->hasOne(Province::class, 'provCode', 'province');
    }
    public function city(){
        return $this->hasOne(City::class, 'citymunCode', 'city');
    }
    public function barangay(){
        return $this->hasOne(Barangay::class, 'brgyCode', 'barangay');
    }
}
