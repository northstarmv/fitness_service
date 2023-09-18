<?php

namespace App\Http\Controllers;

use App\Models\DietConsultations;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DietConsultationsController extends Controller
{
    public function addDietConsult(Request $request):JsonResponse
    {
        $this->validate($request, [
            'client_id' => 'required|integer',
            'data' => 'required',
        ]);

        try {
            $DietConsultation = new DietConsultations();
            $DietConsultation->client_id = $request->get('client_id');
            $DietConsultation->data = $request->get('data');
            $DietConsultation->save();

            return response()->json(['success' => true, 'message' => 'Diet Consultation added successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDietConsults($user_id):JsonResponse
    {
        try {
            $DietConsultations = DietConsultations::where('client_id','=',$user_id)->get();

            return response()->json($DietConsultations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
