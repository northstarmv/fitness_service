<?php

namespace App\Http\Controllers;

use App\Models\UserPrescriptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPrescriptionsController extends Controller
{
    public function savePrescription(Request $request):JsonResponse
    {
        try {
            UserPrescriptions::create([
                'user_id' => $request->get('user_id'),
                'doctor_id' => $request->get('doctor_id'),
                'prescription_data' => $request->get('prescription_data'),
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPrescription($user_id):JsonResponse
    {
        try {
            return response()->json(UserPrescriptions::with([
                'user',
                'doctor',
            ])->where('user_id', $user_id)
                ->where('is_archived', false)
                ->get());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function archivePrescription($id):JsonResponse
    {
        try {
            UserPrescriptions::where('id','=',$id)->update(['is_archived' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
