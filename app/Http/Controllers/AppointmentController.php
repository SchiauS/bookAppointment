<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'email' => 'required|email|max:255',
            'date' => 'required|min:5',
            'hour_interval'=>'required'
        ]);
        if ($validator->fails())
            return back()->withErrors($validator->errors()->toArray());
        else
        {
            try {
                $appointement = Appointment::create($request->all());      
            }
            catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        }
       
              
        return response()->json(['status'=>1]);
    }

    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function getAvailableHours(Request $request)
    {
        $day = $request->input('day');
        $startDay = 9;
        $appointmentTime = 1;
        $stopDay = 21;
        $pauseTime = 2.5;
        $pauseStart = 13;
        $dayIntervals = new \stdClass();
        $appointments = Appointment::where('date', $day)->get();
        $i = 0;
        while($startDay <= ($stopDay - $appointmentTime)){
            $interval = '';
            $hour = new \stdClass();

            if($startDay > $pauseStart && $startDay < ($pauseStart - $pauseTime))
                exit;
            $interval .= $this->convertNumberToHours($startDay).' - '.$this->convertNumberToHours($startDay+1);
            $hour->interval = $interval;
            $lastIntervalAvailable = false;
            if(isset($dayIntervals->{$i-1})){
                $lastInterval = $this->convertNumberToHours($startDay-0.5).' - '.$this->convertNumberToHours($startDay+0.5);
                if($dayIntervals->{$i-1}->available == 0 && !$appointments->where('hour_interval', $lastInterval)->isEmpty())
                    $lastIntervalAvailable = true;
            }
                
            if(!$appointments->where('hour_interval', $interval)->isEmpty() || $lastIntervalAvailable)
                $hour->available = 0;
            else    
                $hour->available = 1;
            $dayIntervals->{$i} = $hour;
            $startDay+=0.5;
            $i++;
        }    
        return response()->json($dayIntervals);
    }


    private function convertNumberToHours($decimal){
        $hours = floor($decimal);
        $minutes = round((((($decimal - $hours) / 100.0) * 60.0) * 100), 0);
        return str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" . str_pad($minutes, 2, "0", STR_PAD_LEFT);
    }
}
