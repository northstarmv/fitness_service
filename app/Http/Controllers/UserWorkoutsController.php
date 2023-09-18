<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserWorkoutPlans;
use App\Models\Workouts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserWorkoutsController extends Controller
{

    //Deprecated Function.
    public function saveWorkout(Request $request, $trainer_id, $user_id): JsonResponse
    {
        $this->validate($request, [
            'workout_id' => 'required|integer',
            'workouts' => 'required|json',
            'count' => 'required|integer',
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        try {

            $WorkoutPlan = UserWorkoutPlans::where('id', '=', $request->get('workout_id'))->first();

            if (!$WorkoutPlan) {
                $WorkoutPlan = new UserWorkoutPlans();
                $WorkoutPlan->user_id = $user_id;
                $WorkoutPlan->user_name = $request->get('client_name');
                $WorkoutPlan->user_email = $request->get('client_email');
                $WorkoutPlan->trainer_id = $trainer_id;
            }
            $workouts = json_decode($request->get('workouts'), true);

            $WorkoutPlan->title = $request->get('title');
            $WorkoutPlan->description = $request->get('description');
            $WorkoutPlan->workout_plan = $workouts;
            $WorkoutPlan->steps = $request->get('count');
            $WorkoutPlan->save();


            return response()->json([
                'success' => true,
                'message' => 'Workout Plan Saved Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }


    }

    public function addNew(Request $request): JsonResponse
    {
        $this->validate($request, [
            'workout_id' => 'required|integer',
            'workouts' => 'required|json',
            'count' => 'required|integer',
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'title' => 'required|string',
            'description' => 'required|string',
            'user_id' => 'required|integer',
            'trainer_id' => 'required|integer',
        ]);

        try {

            $WorkoutPlan = UserWorkoutPlans::where('id', '=', $request->get('workout_id'))->first();

            if (!$WorkoutPlan) {
                $WorkoutPlan = new UserWorkoutPlans();
                $WorkoutPlan->user_id = $request->get('user_id');
                $WorkoutPlan->user_name = $request->get('client_name');
                $WorkoutPlan->user_email = $request->get('client_email');
                $WorkoutPlan->trainer_id = $request->get('trainer_id');
            }

            $workouts = json_decode($request->get('workouts'), true);

            $WorkoutPlan->title = $request->get('title');
            $WorkoutPlan->description = $request->get('description');
            $WorkoutPlan->workout_plan = $workouts;
            $WorkoutPlan->steps = $request->get('count');
            $WorkoutPlan->save();


            return response()->json([
                'success' => true,
                'message' => 'Workout Plan Saved Successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function completeWorkout(Request $request)
    {
        $this->validate($request, [
            'workout_plan_id' => 'required|integer',
            'workout' => 'required|json',
        ]);

        try {
            $workout = json_decode($request->get('workout'), true);
            $workout_id = $workout['id'];

            $workout_plan = UserWorkoutPlans::where('id', '=', $request->get('workout_plan_id'))->first();

            $workout_data = $workout_plan->workout_plan;

            foreach ($workout_data as $key => $value) {
                if ($value['id'] == $workout_id) {
                    $workout_data[$key]['has_completed'] = true;
                }
            }

            $workout_plan->workout_plan = $workout_data;
            $workout_plan->completed_steps = $workout_plan->completed_steps + 1;
            $workout_plan->save();

            return response()->json([
                'success' => true,
                'message' => 'Workout Completed Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function finishWorkout(Request $request): JsonResponse
    {
        $this->validate($request, [
            'workout_plan_id' => 'required|integer',
            'feedback' => 'required|json',
        ]);

        try {
            UserWorkoutPlans::where('id', '=', $request->get('workout_plan_id'))
                ->update([
                    'feedback' => $request->get('feedback'),
                    'finished' => false,
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Workout Finished Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWorkout($workout_id): JsonResponse
    {
        return response()->json(
            UserWorkoutPlans::where('id', '=', $workout_id)->first()
        );
    }

    public function getWorkoutsTrainer($trainer_id): JsonResponse
    {
        try {
            $GroupedByUsers = UserWorkoutPlans::where('trainer_id', '=', $trainer_id)
                ->get()
                ->groupBy('user_id');

            $OutPut = [];

            //return response()->json($GroupedByUsers);

            foreach ($GroupedByUsers as $userWorkout) {

                $totalSteps = 0;
                $completedSteps = 0;

                foreach ($userWorkout as $workout) {
                    $totalSteps += $workout->steps;
                    $completedSteps += $workout->completed_steps;
                }

                $OutPut[] =
                    [
                        'user' => User::find($userWorkout[0]->user_id),
                        'totalSteps' => $totalSteps,
                        'completed_steps' => $completedSteps,
                        'workouts' => $userWorkout
                    ]
                ;
            }

            return response()->json($OutPut);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFinishedWorkoutsTrainer($trainer_id): JsonResponse
    {
        return response()->json(
            UserWorkoutPlans::where('finished', '=', true)
                ->where('trainer_id', $trainer_id)->get()
        );
    }

    public function getWorkoutsClient($user_id): JsonResponse
    {
        return response()->json(
            UserWorkoutPlans::with('trainer')->where('finished', '=', false)->where('user_id', $user_id)->get()
        );
    }

    public function deleteWorkout($workout_id): JsonResponse
    {
        UserWorkoutPlans::find($workout_id)->delete();
        return response()->json(
            ['success' => true, 'message' => 'Workout Deleted Successfully']
        );
    }
}
