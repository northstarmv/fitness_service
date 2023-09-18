<?php

namespace App\Http\Controllers;

use App\Models\Workouts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class WorkoutsController extends Controller
{
    public function searchWorkouts($search_key):JsonResponse
    {
        if ($search_key == 'LIMIT25RESULTS') {
            $workouts = Workouts::all();
            return response()->json($workouts);
        }
        $workouts = Workouts::where('title', 'LIKE', '%'.$search_key.'%')->get();
        return response()->json($workouts);
    }

    public function getAllWorkouts():JsonResponse
    {
        return response()->json(Workouts::all());
    }

    public function addWorkout(Request $request):JsonResponse
    {
        try {
            Workouts::create([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'animation_url' => $request->get('animation_url'),
                'categories' => $request->get('categories'),
                'preview_animation_url' => $request->get('preview_animation_url'),
                'optional' => $request->get('optional'),
            ]);
            return response()->json(['message' => 'Workout added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateWorkout(Request $request):JsonResponse
    {
        try {
            Workouts::where('id','=', $request->get('id'))->update([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'animation_url' => $request->get('animation_url'),
                'preview_animation_url' => $request->get('preview_animation_url'),
                'categories' => $request->get('categories'),
                'optional' => $request->get('optional'),
            ]);
            return response()->json(['message' => 'Workout added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteWorkout(Request $request):JsonResponse
    {
        try {
            Workouts::where('id', '=', $request->get('id'))->delete();
            return response()->json(['message' => 'Workout deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
