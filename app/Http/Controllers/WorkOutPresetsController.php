<?php

namespace App\Http\Controllers;

use App\Models\WorkOutPresets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkOutPresetsController extends Controller
{
    public function newOrEditWorkoutPreset(Request $request): JsonResponse
    {
        $this->validate($request, [
            'trainer_id' => 'required|integer',
            'workout_plan' => 'required|json',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {

            $WorkoutPlan = WorkOutPresets::find($request->get('workout_id'));

            if (!$WorkoutPlan) {
                $WorkoutPlan = new WorkOutPresets();
                $WorkoutPlan->trainer_id = $request->get('trainer_id');
                $WorkoutPlan->workout_plan = $request->get('workout_plan');
            }

            $workouts = json_decode($request->get('workout_plan'), true);

            $WorkoutPlan->title = $request->get('title');
            $WorkoutPlan->description = $request->get('description');
            $WorkoutPlan->workout_plan = $workouts;
            $WorkoutPlan->save();


            return response()->json([
                'success' => true,
                'message' => 'Workout Plan Saved Successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWorkoutPresets(Request $request):JsonResponse
    {
        return response()->json(
            WorkOutPresets::where('trainer_id', '=', $request->get('trainer_id'))->get()
        );
    }

    public function searchWorkoutPresets($search_key):JsonResponse
    {
        return response()->json(
            WorkOutPresets::where('title', 'LIKE', '%'.$search_key.'%')->get()
        );
    }

    public function deleteWorkoutPreset(Request $request):JsonResponse
    {
        $WorkoutPlan = WorkOutPresets::where('id', '=', $request->get('workout_id'))->first();
        $WorkoutPlan->delete();
        return response()->json([
            'success' => true,
            'message' => 'Workout Plan Deleted Successfully',
        ]);
    }
}
