<?php

namespace App\Http\Controllers;

use App\Models\ExclusiveGymSchedules;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonEventsController extends Controller
{
    public function getCommonEvents($user_id,$user_role):JsonResponse
    {
        try {
            if (User::find($user_id)->role == 'trainer') {
                $ExclusiveGymBookings = ExclusiveGymSchedules::where('confirmed','=',true)
                    ->where('trainer_id', '=', $user_id)
                    ->where('start_time', '>', Carbon::now()->subMinutes(30))
                    ->get();
            } else {
                $ExclusiveGymBookings = ExclusiveGymSchedules::with('gymData')
                    ->with(['clients' => function ($query) use ($user_id) {
                        $query->where('client_id', '=', $user_id);
                    }])
                    ->where('confirmed','=',true)
                    ->where('start_time', '>', Carbon::now()->subMinutes(30))
                    ->get();
            }

            $EXGymBookings = [];
            foreach ($ExclusiveGymBookings as $BKX) {
                $EXGymBookings[] = [
                    'id' => $BKX->id,
                    'title' => 'Your Exclusive Gym Booking',
                    'description' => 'Your Exclusive Gym Booking',
                    'start' => Carbon::parse($BKX->start_time),
                    'end' => Carbon::parse($BKX->end_time),
                    'owner'=>User::find($user_id)->name,
                    'type'=>'GYM BOOKING',
                ];
            }


            return response()->json($EXGymBookings);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
