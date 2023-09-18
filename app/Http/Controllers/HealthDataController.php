<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User_Client;
use App\Models\UserBloodPressureData;
use App\Models\UserBloodSugarData;
use App\Models\UserBMIPIData;
use App\Models\UserBodyFatData;
use App\Models\UserDailyFoodData;
use App\Models\UserMacroData;
use App\Models\UserMiscData;
use App\Models\UserWorkoutPlans;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthDataController extends Controller
{
    public function saveBMIPIData(Request $request, $user_id): JsonResponse
    {
        $this->validate($request, [
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);


        try {
            //In Centimeters
            $height = $request->get('height') / 100;

            //In Kilograms
            $weight = $request->get('weight');

            $userBMI = round($weight / ($height * $height), 2);
            $userPI = round($weight / ($height * $height * $height), 2);

            $t = $height * $height;
            error_log("User BMI: " . $t);
            error_log("User PI: " . $weight);

            $userBMIPIData = new UserBMIPIData();
            $userBMIPIData->user_id = $user_id;
            $userBMIPIData->height = $request->get('height');
            $userBMIPIData->weight = $request->get('weight');
            $userBMIPIData->bmi = $userBMI;
            $userBMIPIData->pi = $userPI;

            if ($userBMI > 30) {
                $userBMIPIData->bmi_category = 'Obesity';
            } else if ($userBMI > 25) {
                $userBMIPIData->bmi_category = 'Overweight';
            } else if ($userBMI > 18.5) {
                $userBMIPIData->bmi_category = 'Normal';
            } else {
                $userBMIPIData->bmi_category = 'Underweight';
            }

            if ($userPI > 17) {
                $userBMIPIData->pi_category = 'Obesity';
            } else if ($userPI > 15) {
                $userBMIPIData->pi_category = 'Overweight';
            } else if ($userPI > 11) {
                $userBMIPIData->pi_category = 'Normal';
            } else {
                $userBMIPIData->pi_category = 'Underweight';
            }

            $userBMIPIData->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function saveBodyFatMassData(Request $request, $user_id): JsonResponse
    {
        $this->validate($request, [
            'front_upper_arm' => 'required|numeric',
            'back_upper_arm' => 'required|numeric',
            'side_of_waist' => 'required|numeric',
            'back_below_shoulder' => 'required|numeric',
            'age' => 'required|numeric',
            'gender' => 'required|string',
            'weight' => 'required|numeric'
        ]);

        try {
            //All a in Millimeters
            $front_upper_arm = $request->get('front_upper_arm');
            $back_upper_arm = $request->get('back_upper_arm');
            $side_of_waist = $request->get('side_of_waist');
            $back_below_shoulder = $request->get('back_below_shoulder');

            //In Kilograms
            $weight = $request->get('weight');

            $Log_of_four = log($front_upper_arm + $back_upper_arm + $side_of_waist + $back_below_shoulder, 10);
            $age = $request->get('age');

            $userBodyFatData = new UserBodyFatData();
            $userBodyFatData->user_id = $user_id;
            $userBodyFatData->front_upper_arm = $front_upper_arm;
            $userBodyFatData->back_upper_arm = $back_upper_arm;
            $userBodyFatData->side_of_waist = $side_of_waist;
            $userBodyFatData->back_below_shoulder = $back_below_shoulder;
            $userBodyFatData->weight = $weight;

            $body_fat = 0;
            $body_density = 0;
            $body_fat_category = '';


            if ($request->get('gender') == 'male') {
                if ($age >= 50) {
                    $body_density = 1.1715 - (0.0779 * $Log_of_four);
                } elseif ($age >= 40) {
                    $body_density = 1.1620 - (0.0700 * $Log_of_four);
                } elseif ($age >= 30) {
                    $body_density = 1.1422 - (0.0544 * $Log_of_four);
                } else {
                    $body_density = 1.1631 - (0.0632 * $Log_of_four);
                }

                $body_fat = (495 / $body_density) - 450;
                //Does not match the table values by -0.1 so I am adding it.
                $body_fat = $body_fat + 0.1;

                switch ($age):
                    case ($age >= 50):
                        if ($body_fat >= 12 && $body_fat <= 19) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 12) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                    case ($age >= 30):
                        if ($body_fat >= 11 && $body_fat <= 17) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 11) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                    case ($age < 30):
                        if ($body_fat >= 9 && $body_fat <= 15) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 9) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                endswitch;

            } else {
                if ($age >= 50) {
                    $body_density = 1.1339 - (0.0645 * $Log_of_four);
                } elseif ($age >= 40) {
                    $body_density = 1.1333 - (0.0612 * $Log_of_four);
                } elseif ($age >= 30) {
                    $body_density = 1.1423 - (0.0632 * $Log_of_four);
                } else {
                    $body_density = 1.1599 - (0.0717 * $Log_of_four);
                }

                $body_fat = (495 / $body_density) - 450;
                //Does not match the table values by -0.1 so I am adding it.
                $body_fat = $body_fat + 0.1;

                switch ($age):
                    case ($age >= 50):
                        if ($body_fat >= 16 && $body_fat <= 25) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 25) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                    case ($age >= 30):
                        if ($body_fat >= 15 && $body_fat <= 23) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 15) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                    case ($age < 30):
                        if ($body_fat >= 14 && $body_fat <= 21) {
                            $body_fat_category = 'Good';
                        } elseif ($body_fat < 14) {
                            $body_fat_category = 'Lean';
                        } else {
                            $body_fat_category = 'Above Average';
                        }
                        break;
                endswitch;
            }

            $muscle_mass = ((100 - $body_fat) * $weight) / 100;

            $userBodyFatData->body_fat = round($body_fat, 2);
            $userBodyFatData->muscle_mass = round($muscle_mass, 2);
            $userBodyFatData->body_fat_category = $body_fat_category;

            $userBodyFatData->save();


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function saveBloodSugarData(Request $request, $user_id): JsonResponse
    {
        $this->validate($request, [
            'fbs' => 'required|numeric',
            'rbs' => 'required|numeric',
        ]);

        try {
            $fasting_blood_sugar = $request->get('fbs');
            $random_blood_sugar = $request->get('rbs');

            if ($fasting_blood_sugar > 126) {
                $fbs_category = 'Diabetes';
            } elseif ($fasting_blood_sugar > 100) {
                $fbs_category = 'PreDiabetes';
            } elseif ($fasting_blood_sugar > 70) {
                $fbs_category = 'Normal';
            } else {
                $fbs_category = 'Low';
            }

            if ($random_blood_sugar > 200) {
                $rbs_category = 'Diabetes';
            } elseif ($random_blood_sugar > 120) {
                $rbs_category = 'PreDiabetes';
            } elseif ($random_blood_sugar > 70){
                $rbs_category = 'Normal';
            } else {
                $rbs_category = 'Low';
            }

            $userBloodSugarData = new UserBloodSugarData();
            $userBloodSugarData->user_id = $user_id;
            $userBloodSugarData->fasting_blood_sugar = $fasting_blood_sugar;
            $userBloodSugarData->random_blood_sugar = $random_blood_sugar;
            $userBloodSugarData->fbs_category = $fbs_category;
            $userBloodSugarData->rbs_category = $rbs_category;
            $userBloodSugarData->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function saveBloodPressureData(Request $request, $user_id): JsonResponse
    {

        $this->validate($request, [
            'systolic' => 'required|numeric|min:0',
            'diastolic' => 'required|numeric|min:0',
        ]);

        try {

            $systolic = $request->get('systolic');
            $diastolic = $request->get('diastolic');

            if($systolic > 180 || $diastolic > 120){
                $blood_pressure_category = 'Hypertension Crisis';
            } elseif ($systolic >= 140 || $diastolic >= 90) {
                $blood_pressure_category = 'Hypertension Stage II';
            } elseif ($systolic >= 130 || $diastolic >= 80) {
                $blood_pressure_category = 'Hypertension Stage I';
            } elseif ($systolic > 120 && $diastolic <= 79) {
                $blood_pressure_category = 'Elevated';
            } elseif ($systolic > 90 && $diastolic > 60){
                $blood_pressure_category = 'Normal';
            } else {
                $blood_pressure_category = 'Low';
            }

            $userBloodPressureData = new UserBloodPressureData();
            $userBloodPressureData->user_id = $user_id;
            $userBloodPressureData->systolic = $systolic;
            $userBloodPressureData->diastolic = $diastolic;
            $userBloodPressureData->blood_pressure_category = $blood_pressure_category;
            $userBloodPressureData->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function saveMiscData(Request $request, $user_id): JsonResponse
    {
        $this->validate($request, [
            'bust' => 'required|numeric',
            'stomach' => 'required|numeric',
            'chest' => 'required|numeric',
            'calves' => 'required|numeric',
            'hips' => 'required|numeric',
            'thighs' => 'required|numeric',
            'l_arm' => 'required|numeric',
            'r_arm' => 'required|numeric',
        ]);

        try {
            $misc_data = [
              'bust' => $request->get('bust'),
              'stomach' => $request->get('stomach'),
              'chest' => $request->get('chest'),
              'calves' => $request->get('calves'),
              'hips' => $request->get('hips'),
              'thighs' => $request->get('thighs'),
              'l_arm' => $request->get('l_arm'),
              'r_arm' => $request->get('r_arm'),
            ];

            $userMiscData = new UserMiscData();
            $userMiscData->user_id = $user_id;
            $userMiscData->misc_data = $misc_data;
            $userMiscData->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getFitnessData($user_id): JsonResponse
    {
        try {
            $macros = UserMacroData::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            if ($macros){
                $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $macros->user_id)
                    ->whereDate('created_at', Carbon::today())
                    ->get();

                $macros->daily_calories = 0;
                $macros->daily_carbs = 0;
                $macros->daily_protein = 0;
                $macros->daily_fat = 0;

                foreach ($userDailyFoodData as $food) {
                    $macros->daily_calories += $food->calories;
                    $macros->daily_carbs += $food->carbs;
                    $macros->daily_protein += $food->protein;
                    $macros->daily_fat += $food->fat;
                }
            }
            return response()->json([
                'user_data'=> User_Client::find($user_id),
                'macros' => $macros,
                'bmi_pi' => UserBMIPIData::where('user_id', $user_id)->orderBy('id', 'desc')->first(),
                'body_fat' => UserBodyFatData::where('user_id', $user_id)->orderBy('id', 'desc')->first(),
                'blood_sugar' => UserBloodSugarData::where('user_id', $user_id)->orderBy('id', 'desc')->first(),
                'blood_pressure' => UserBloodPressureData::where('user_id', $user_id)->orderBy('id', 'desc')->first(),
                'misc' => UserMiscData::where('user_id', $user_id)->orderBy('id', 'desc')->first(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getHomeFitnessData($user_id,$day): JsonResponse
    {
        try {
            $macros = UserMacroData::where('user_id', $user_id)->orderBy('id', 'desc')->first();
            $userDailyFoodData = [];

            $Date = Carbon::today();

            if($day == '0') {
                $Date = Carbon::today();
            } elseif ($day == '1') {
                $Date = Carbon::today()->subDays(1);
            } elseif ($day == '2') {
                $Date = Carbon::today()->subDays(2);
            } elseif ($day == null) {
                $Date = Carbon::today();
            }


            if ($macros){
                $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $macros->user_id)
                    ->whereDate('created_at', $Date)
                    ->get();

                $macros->daily_calories = 0;
                $macros->daily_carbs = 0;
                $macros->daily_protein = 0;
                $macros->daily_fat = 0;

                foreach ($userDailyFoodData as $food) {
                    $macros->daily_calories += $food->calories;
                    $macros->daily_carbs += $food->carbs;
                    $macros->daily_protein += $food->protein;
                    $macros->daily_fat += $food->fat;
                }
            }



            $DoneCount = $Workouts = UserWorkoutPlans::where('finished','=',false)
                ->where('user_id','=', $user_id)->sum('completed_steps');
            $TotalCount = $Workouts = UserWorkoutPlans::where('finished','=',false)
                ->where('user_id','=', $user_id)->sum('steps');

            return response()->json([
                'macros' => $macros,
                'meals' => $userDailyFoodData,
                'workouts_total'=> $TotalCount,
                'workouts_done'=> $DoneCount,

            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }
}
