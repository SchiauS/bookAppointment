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
        ]);

        if ($validator->fails())
            return response()->json(['status'=>0]);
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
        $dayIntervals = [];

        dd($day);
        while($startDay <= $stopDay - $appointmentTime){
            $interval = '';
            if($startDay > $pauseStart && $startDay < ($pauseStart - $pauseTime))
                break;
            $interval .= ''.floor($startDay);
            if(fmod($startDay, 1) !== 0.00)    
                $interval .= ':30';
            else
                $interval .= ':00';
            $startDay += 0.5;    
            array_push($dayIntervals, $interval); 
        }

        // Appointment::where('day', '')->all();        
        return $dayIntervals;
    }
}
