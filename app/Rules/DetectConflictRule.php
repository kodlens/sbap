<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Schedule;

class DetectConflictRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

     private $ayid, $startTime, $endTime, $mon, $tue, $wed,$thu, $fri, $sat, $sun, $room_id,$id, $data;

    public function __construct($ayid, $startTime, $endTime,  $mon, $tue, $wed, $thu, $fri, $sat, $sun, $room_id, $id)
    {
        //
        $this->ayid = $ayid;

        $this->startTime = $startTime;
        $this->endTime = $endTime;

        $this->mon = $mon;
        $this->tue = $tue;
        $this->wed = $wed;
        $this->thu = $thu;
        $this->fri = $fri;
        $this->sat = $sat;
        $this->sun = $sun;
        $this->room_id = $room_id;

        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $sTime = $this->startTime;
        $eTime = $this->endTime;

        $ayid = $this->ayid;
        $mon = $this->mon;
        $tue = $this->tue;
        $wed = $this->wed;
        $thu = $this->thu;
        $fri = $this->fri;
        $sat = $this->sat;
        $sun = $this->sun;
        $room_id = $this->room_id;


        $countExist = Schedule::with('acadyear', 'program', 'course', 'room')
            ->whereHas('acadyear', function($q) use ($ayid){
                $q->where('academic_year_id', $ayid);
            })
            ->where(function($query) use ($sTime, $eTime){
                $query->whereBetween('start_time', [$sTime, $eTime])
                    ->orWhereBetween('end_time', [$sTime, $eTime]);
            });

        if($mon == 1 || $tue == 1 || $wed == 1 || $thu == 1 || $fri == 1 || $sat == 1 || $sun == 1){
            $countExist->where(function($q) use ($mon, $tue,$wed, $thu, $fri, $sat, $sun){
                $mon == 1 ? $q->orWhere('mon', 1) : '';
                $tue == 1 ? $q->orWhere('tue', 1) : '';
                $wed == 1 ? $q->orWhere('wed', 1) : '';
                $thu == 1 ? $q->orWhere('thu', 1) : '';
                $fri == 1 ? $q->orWhere('fri', 1) : '';
                $sat == 1 ? $q->orWhere('sat', 1) : '';
                $sun == 1 ? $q->orWhere('sun', 1): '';
            });
        }
        $countExist->where('room_id', $room_id)
            ->where('schedule_id', '!=',$this->id);

        if($this->id > 0){
            //update but not this data
            $countExist->where('schedule_id', '!=', $this->id);
        }

        $count = $countExist->count();
        $this->data = $countExist->get();

        if($count > 0){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        //return 'The validation error message.';

        return [
            'msg' => 'Schedule already exist.',
            'data' => $this->data
        ];
    }
}
