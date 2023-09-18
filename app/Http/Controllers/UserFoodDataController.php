<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\UserDailyFoodData;
use App\Models\UserMacroData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFoodDataController extends Controller
{
    public function getLastThirtyDayMeals($user_id): JsonResponse
    {
        try {
            $MealData = UserDailyFoodData::where('user_id','=',$user_id)
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->orderBy('created_at','desc')
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('Y-m-d');
                });



            $Data = [];

            foreach ($MealData as $key=>$value) {
                $calories = 0;
                $carbs = 0;
                $protein = 0;
                $fat = 0;
                $meals = [];
                $macro_profile = [];

                foreach ($value as $meal) {
                    $calories += $meal->calories;
                    $carbs += $meal->carbs;
                    $protein += $meal->protein;
                    $fat += $meal->fat;
                    $meals[] = $meal;
                    $macro_profile = $meal->macro_profile;
                }

                $Data[] = [
                    'date' => $key,
                    'calories' => $calories,
                    'carbs' => $carbs,
                    'protein' => $protein,
                    'fat' => $fat,
                    'meals'=> $meals,
                    'macro_profile' => $macro_profile
                ];

            }


            return response()->json($Data);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }


    public function addMeal(Request $request, $user_id): JsonResponse
    {
        $this->validate($request, [
            'meal_id' => 'required|integer',
            'food_data' => 'required|json',
            'day'=>'required|string',
        ]);

        try {
            $day = $request->get('day');

            if($day == '0') {
                $Date = Carbon::today();
            } elseif ($day == '1') {
                $Date = Carbon::today()->subDays(1);
            } elseif ($day == '2') {
                $Date = Carbon::today()->subDays(2);
            } elseif ($day == null) {
                $Date = Carbon::today();
            }


            $food_data = json_decode($request->get('food_data'), true);

            $calories = 0;
            $carbs = 0;
            $protein = 0;
            $fat = 0;

            foreach ($food_data as $food) {
                $Food = Food::where('id', '=', $food['id'])->first();
                $calories += $Food->calories * $food['no_of_potions'];
                $carbs += $Food->carbs * $food['no_of_potions'];
                $protein += $Food->proteins * $food['no_of_potions'];
                $fat += ($Food->fat + $Food->sat_fat) * $food['no_of_potions'];
            }

            $userDailyFoodData = UserDailyFoodData::where('id', '=', $request->get('meal_id'))->first();

            if (!$userDailyFoodData) {
                $userDailyFoodData = new UserDailyFoodData();
                $userDailyFoodData->created_at = $Date;
                $userDailyFoodData->user_id = $user_id;
            }

            $userDailyFoodData->food_data = $food_data;
            $userDailyFoodData->calories = $calories;
            $userDailyFoodData->carbs = $carbs;
            $userDailyFoodData->protein = $protein;
            $userDailyFoodData->fat = $fat;
            $userDailyFoodData->macro_profile = UserMacroData::where('user_id', '=', $user_id)->first();
            $userDailyFoodData->save();


            return response()->json(['message' => 'Meal Saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMeal(Request $request):JsonResponse
    {
        try {
            UserDailyFoodData::where('id', '=', $request->get('id'))->delete();
            return response()->json(['message' => 'Meal Deleted successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getUserDailyMeals($user_id): JsonResponse
    {
        try {
            $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $user_id)
                ->whereDate('created_at', Carbon::today())
                ->get();

            $calories = 0;
            $carbs = 0;
            $protein = 0;
            $fat = 0;

            foreach ($userDailyFoodData as $food) {
                $calories += $food->calories;
                $carbs += $food->carbs;
                $protein += $food->protein;
                $fat += $food->fat;
            }

            return response()->json([
                'meals' => $userDailyFoodData,
                'overview' => [
                    'calories' => $calories,
                    'carbs' => $carbs,
                    'protein' => $protein,
                    'fat' => $fat
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getUserWeeklyMeals($user_id): JsonResponse
    {
        try {
            $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $user_id)
                ->whereDate('updated_at', '>=', Carbon::today()->subDays(7)->startOfDay())
                ->get();

            $calories = 0;
            $carbs = 0;
            $protein = 0;
            $fat = 0;


            foreach ($userDailyFoodData as $food) {
                $calories += $food->calories;
                $carbs += $food->carbs;
                $protein += $food->protein;
                $fat += $food->fat;
            }

            return response()->json([
                'meals' => $userDailyFoodData,
                'overview' => [
                    'calories' => $calories,
                    'carbs' => $carbs,
                    'protein' => $protein,
                    'fat' => $fat
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllUserDailyMealsOverview(Request $request, $trainer_id): JsonResponse
    {
        $this->validate($request, [
            'user_ids' => 'required|array',
        ]);

        try {
            $userIds = $request->get('user_ids');
            $users = [];
            foreach ($userIds as $uid) {
                $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $uid)
                    ->whereDate('created_at', Carbon::today())
                    ->get();

                $calories = 0;
                $carbs = 0;
                $protein = 0;
                $fat = 0;

                foreach ($userDailyFoodData as $food) {
                    $calories += $food->calories;
                    $carbs += $food->carbs;
                    $protein += $food->protein;
                    $fat += $food->fat;
                }

                $users[] = [
                    'user_id' => $uid,
                    'calories' => $calories,
                    'carbs' => $carbs,
                    'protein' => $protein,
                    'fat' => $fat
                ];
            }

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
