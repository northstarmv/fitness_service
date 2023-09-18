<?php

namespace App\Http\Controllers;

use App\Models\ExclusiveGymSchedules;
use App\Models\ExclusiveGymSchedulesClients;
use App\Models\Gym_Gallery;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExclusiveGymScheduleController extends Controller
{
    public function getMyBookings($user_id): JsonResponse
    {
        try {

            if (User::find($user_id)->role == 'trainer') {
                $Schedules = ExclusiveGymSchedules::with('clients')
                    ->with('gymData')
                    ->with('gym')
                    ->with('clients.user')
                    ->where('confirmed','=',true)
                    ->where('trainer_id', '=', $user_id)
                    ->where('start_time', '>', Carbon::now()->subMinutes(30))
                    ->get();
            } else {
                $Schedules = ExclusiveGymSchedules::with('gymData')
                    ->with('gym')
                    ->with(['clients' => function ($query) use ($user_id) {
                        $query->where('client_id', '=', $user_id);
                    }])
                    ->with('clients.user')
                    ->where('confirmed','=',true)
                    ->where('start_time', '>', Carbon::now()->subMinutes(30))
                    ->get();
            }

            foreach ($Schedules as $Schedule){
                $Gallery = Gym_Gallery::where('user_id','=',$Schedule->gymData->user_id)->get();
                $GalleryArray = [];
                foreach ($Gallery as $gallery){
                    $GalleryArray[] = $gallery->image_path;
                }

                $Schedule->gymData->gym_gallery = str_replace('\\','',json_encode($GalleryArray));

            }

            return response()->json($Schedules);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMyUnconfirmedBookings(Request $request): JsonResponse
    {
        try {
            return response()->json(ExclusiveGymSchedules::where('trainer_id', '=', $request->get('user_id'))
                ->where('gym_id', '=', $request->get('gym_id'))
                ->where('confirmed', '=', false)
                ->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAvailableTimeSlots(Request $request): JsonResponse
    {
        try {
            $timeSlots = $request->get('date');
            $availabilities = [
                0 => true,
                1 => true,
                2 => true,
                3 => true,
                4 => true,
                5 => true,
                6 => true,
                7 => true,
                8 => true,
                9 => true,
                10 => true,
                11 => true,
                12 => true,
                13 => true,
                14 => true,
                15 => true,
                16 => true,
                17 => true,
                18 => true,
                19 => true,
                20 => true,
                21 => true,
                22 => true,
                23 => true,
                24 => true,
            ];

            $bookingsForTheDay = ExclusiveGymSchedules::where('gym_id','=',$request->get('gym_id'))
                ->whereDate('start_time', '=', Carbon::parse($timeSlots))
                ->orderBy('start_time')
                ->get();


            foreach ($bookingsForTheDay as $booking) {
                $startTime = Carbon::parse($booking->start_time);
                $endTime = Carbon::parse($booking->end_time);
                $Hours = $startTime->diffInHours($endTime);

                $availabilities[$startTime->hour] = false;
                for ($i = 1; $i < $Hours; $i++) {
                    $availabilities[$startTime->hour + $i] = false;
                }
            }

            return response()->json($availabilities);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function createSchedule(Request $request): JsonResponse
    {
        $this->validate(request(), [
            'gym_id' => 'required|integer',
            'trainer_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
        ]);

        $startTime = Carbon::parse($request->get('start_time'));
        $endTime = Carbon::parse($request->get('end_time'));
        $gymID = $request->get('gym_id');

        try {
            $modStart = $startTime->addMinutes(5);
            $modEnd = $endTime->subMinutes(5);
            $eventsCount = ExclusiveGymSchedules::where('gym_id', '=', $gymID)
                ->whereBetween('start_time', [$modStart, $modEnd])
                ->orWhereBetween('end_time', [$modStart, $modEnd])
                ->count();

            if ($eventsCount > 0) {
                return response()->json([
                    'message' => 'There is already an booking in this time range',
                    'status' => 'error'
                ], 422);
            } else {
                ExclusiveGymSchedules::create([
                    'gym_id' => $request->get('gym_id'),
                    'trainer_id' => $request->get('trainer_id'),
                    'start_time' => $request->get('start_time'),
                    'end_time' => $request->get('end_time'),
                    'client_ids' => $request->get('client_ids'),
                    'confirmed' => false,
                ]);
                return response()->json([
                    'message' => 'Schedule created successfully',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function confirmGymSchedules(Request $request): JsonResponse
    {
        try {
            $Schedules = ExclusiveGymSchedules::whereIn('id', $request->get('schedule_ids'))->get();

            foreach ($Schedules as $Schedule) {
                $Schedule->confirmed = true;
                $Schedule->save();
                $ClientIds = $Schedule->client_ids;

                foreach ($ClientIds as $clientId) {
                    ExclusiveGymSchedulesClients::create([
                        'client_id' => $clientId,
                        'schedule_id' => $Schedule->id
                    ]);

                }
            }

            return response()->json([
                'message' => 'Schedules confirmed',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function deleteMyUnconfirmedSchedule(Request $request): JsonResponse
    {
        try {
            ExclusiveGymSchedules::find($request->get('id'))->delete();

            return response()->json([
                'message' => 'Schedules deleted',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function deleteAllMyUnconfirmedSchedule(Request $request): JsonResponse
    {
        try {
            ExclusiveGymSchedules::where('trainer_id', $request->get('user_id'))
                ->where('confirmed', false)
                ->delete();

            return response()->json([
                'message' => 'Schedules deleted',
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }
}
