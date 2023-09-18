<?php

namespace App\Http\Controllers;

use App\Models\CommercialGymSubscription;
use App\Models\Gym_Gallery;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommercialGymSubscriptionController extends Controller
{
    public function newComGymSub(Request $request):JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'gym_id' => 'required|integer',
            'type' => 'required|string',
            'start_date'=>'required|string',
            'quantity' => 'required|integer',
        ]);

        try {
            $Multiplier = 1;

            if($request->get('type') == 'monthly')
            {
                $Multiplier = 30;
            }
            else if($request->get('type') == 'weekly')
            {
                $Multiplier = 7;
            }

            $TotalDays = $request->get('quantity') * $Multiplier;

            CommercialGymSubscription::create([
                'user_id' => $request->get('user_id'),
                'gym_id' => $request->get('gym_id'),
                'start_date' => Carbon::parse($request->get('start_date')),
                'end_date' => Carbon::parse($request->get('start_date'))->addDays($TotalDays),
                'type' => $request->get('type'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Commercial Gym Subscription Created Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMyComGymSubs($user_id):JsonResponse
    {
        $Schedules = CommercialGymSubscription::with('gym')
            ->with('gymData')
            ->where('end_date', '>=', Carbon::tomorrow())
            ->where('user_id', '=', $user_id)
            ->get();

        foreach ($Schedules as $Schedule){
            $Gallery = Gym_Gallery::where('user_id','=',$Schedule->gymData->user_id)->get();
            $GalleryArray = [];
            foreach ($Gallery as $gallery){
                $GalleryArray[] = $gallery->image_path;
            }

            $Schedule->gymData->gym_gallery = str_replace('\\','',json_encode($GalleryArray));

        }


        return response()->json($Schedules);
    }
}
