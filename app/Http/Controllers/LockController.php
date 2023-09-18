<?php

namespace App\Http\Controllers;

use App\Models\CommercialGymSubscription;
use App\Models\ExclusiveGymSchedules;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;

class LockController extends Controller
{
    /**
     * @throws DatabaseException
     */
    public function Unlock(Request $request):JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'gym_id' => 'required|integer',
        ]);
        try {
            $client_id = $request->get('user_id');
            $gym_id = $request->get('gym_id');

            $Schedule = ExclusiveGymSchedules::with(['clients' => function ($query) use ($client_id) {
                $query->where('client_id', '=', $client_id);
            }])
                ->where('start_time', '<=', Carbon::now())
                ->where('end_time', '>=', Carbon::now()->addMinutes(15))
                ->where('gym_id','=',$gym_id)
                ->exists();

            $ComSchedule = CommercialGymSubscription::where('user_id','=',$client_id)
                ->where('gym_id','=',$gym_id)
                ->where('start_date','<=',Carbon::now())
                ->where('end_date','>=',Carbon::now()->addMinutes(60))
                ->exists();


            if($Schedule || $ComSchedule){
                Firebase::database()->getReference('/'.$gym_id.'/')->set(true);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Unlocked',
                ]);
            } else {
                return response()->json(['message' => 'You are not allowed to unlock this gym'], 401);
            }

        } catch (DatabaseException $e) {
            return response()->json($e->getMessage(),200);
        }
    }
}
