<?php

namespace App\Http\Controllers;

use App\Models\UserBodyFatData;
use App\Models\UserDailyFoodData;
use App\Models\UserMacroData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MacroDataController extends Controller
{
    public function saveMacroProgram(Request $request, $user_id): JsonResponse
    {
        try {

            $this->validate($request, [
                'selectedFitCategory' => 'required|in:Loss,Gain',
                'selectedFitProgram' => 'required|in:Moderate,Intense,HighActive',
                'selectedFitMode' => 'required|in:Controlled,Accelerated,SuperAccelerated,CalBoost,ProteinBoost',
            ]);


            $fatMM = UserBodyFatData::where('user_id', $user_id)->orderBy('id', 'desc')->first();

            if (!$fatMM) {
                return response()->json(['error' => 'No Body Fat Data Found'], 500);
            }

            $leanBodyMass = ($fatMM->weight * $fatMM->body_fat) / 100;
            $activeCalCount = ((370 + (21.6 * $leanBodyMass)) * 1.375) * 0.7;
            $targetCalCount = $activeCalCount;

            $carbs = 0;
            $protein = 0;
            $fat = 0;

            $selectedFitCategory = $request->get('selectedFitCategory');
            // Loss | Gain

            $selectedFitProgram = $request->get('selectedFitProgram');
            // Loss --> Moderate | Intense
            // Gain --> Moderate | Intense | HighActive

            $selectedFitMode = $request->get('selectedFitMode');
            // Loss --> Controlled | Accelerated | SuperAccelerated
            // Gain --> CalBoost | ProteinBoost

            if ($selectedFitCategory == 'Loss') {
                if ($selectedFitProgram == 'Moderate') {
                    $targetCalCount = $activeCalCount * 1.375 * 0.7;
                } else {
                    $targetCalCount = $activeCalCount * 1.550 * 0.7;
                }

                if ($selectedFitMode == 'Controlled') {
                    $carbs = $targetCalCount * 0.5 / 4;
                    $protein = $targetCalCount * 0.3 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } elseif ($selectedFitMode == 'Accelerated') {
                    $carbs = $targetCalCount * 0.4 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } else {
                    $carbs = $targetCalCount * 0.3 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.3 / 9;
                }
            } else {
                if ($selectedFitProgram == 'Moderate') {
                    $targetCalCount = $activeCalCount * 1.375 * 1.2;
                } elseif ($selectedFitProgram == 'Intense') {
                    $targetCalCount = $activeCalCount * 1.550 * 1.2;
                } else {
                    $targetCalCount = $activeCalCount * 1.725 * 1.2;
                }

                if ($selectedFitMode == 'CalBoost') {
                    $carbs = $targetCalCount * 0.5 / 4;
                    $protein = $targetCalCount * 0.3 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } else {
                    $carbs = $targetCalCount * 0.4 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                }
            }


            $userMacroData = UserMacroData::where('user_id', $user_id)->first();

            if(!$userMacroData) {
                $userMacroData = new UserMacroData();
                $userMacroData->user_id = $user_id;
                $userMacroData->trainer_id = $request->get('trainer_id');
            }

            $userMacroData->trainer_id = $request->get('trainer_id');


            $userMacroData->fit_category = $selectedFitCategory;
            $userMacroData->fit_program = $selectedFitProgram;
            $userMacroData->fit_mode = $selectedFitMode;

            $userMacroData->lean_body_mass = round($leanBodyMass, 2);
            $userMacroData->active_calories = round($activeCalCount, 2);
            $userMacroData->target_calories = round($targetCalCount, 2);

            $userMacroData->target_carbs = round($carbs, 2);
            $userMacroData->target_protein = round($protein, 2);
            $userMacroData->target_fat = round($fat, 2);
            $userMacroData->override = false;

            $userMacroData->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function overrideMacros(Request $request, $user_id):JsonResponse
    {
        $this->validate($request, [
            'target_carbs' => 'required|numeric',
            'target_protein' => 'required|numeric',
            'target_fat' => 'required|numeric',
        ]);

        try {
            $target_calories = ($request->get('target_carbs')  * 4) + ($request->get('target_protein') * 4) + ($request->get('target_fat') * 9);

            UserMacroData::updateOrCreate(
                ['id' => $request->get('id')],
                [
                    'user_id' => $user_id,
                    'trainer_id' => $user_id,
                    'fit_category' => 'OVERRIDE',
                    'fit_program' => 'OVERRIDE',
                    'fit_mode' => 'OVERRIDE',
                    'lean_body_mass' => 0,
                    'active_calories' => 0,

                    'target_calories' => $target_calories,
                    'target_carbs' => $request->get('target_carbs'),
                    'target_protein' => $request->get('target_protein'),
                    'target_fat' => $request->get('target_fat'),

                    'override'=>true,
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function overrideAdvMacros(Request $request, $user_id):JsonResponse
    {
        $this->validate($request, [
            'selectedFitCategory' => 'required|in:Loss,Gain',
            'selectedFitProgram' => 'required|in:Moderate,Intense,HighActive',
            'selectedFitMode' => 'required|in:Controlled,Accelerated,SuperAccelerated,CalBoost,ProteinBoost',
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

            $body_fat = 0;
            $body_density = 0;

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
            }

            $muscle_mass = ((100 - $body_fat) * $weight) / 100;

            $leanBodyMass = ($weight * $body_fat) / 100;
            $activeCalCount = ((370 + (21.6 * $leanBodyMass)) * 1.375) * 0.7;
            $targetCalCount = $activeCalCount;

            $carbs = 0;
            $protein = 0;
            $fat = 0;

            $selectedFitCategory = $request->get('selectedFitCategory');
            // Loss | Gain

            $selectedFitProgram = $request->get('selectedFitProgram');
            // Loss --> Moderate | Intense
            // Gain --> Moderate | Intense | HighActive

            $selectedFitMode = $request->get('selectedFitMode');
            // Loss --> Controlled | Accelerated | SuperAccelerated
            // Gain --> CalBoost | ProteinBoost

            if ($selectedFitCategory == 'Loss') {
                if ($selectedFitProgram == 'Moderate') {
                    $targetCalCount = $activeCalCount * 1.375 * 0.7;
                } else {
                    $targetCalCount = $activeCalCount * 1.550 * 0.7;
                }

                if ($selectedFitMode == 'Controlled') {
                    $carbs = $targetCalCount * 0.5 / 4;
                    $protein = $targetCalCount * 0.3 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } elseif ($selectedFitMode == 'Accelerated') {
                    $carbs = $targetCalCount * 0.4 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } else {
                    $carbs = $targetCalCount * 0.3 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.3 / 9;
                }
            } else {
                if ($selectedFitProgram == 'Moderate') {
                    $targetCalCount = $activeCalCount * 1.375 * 1.2;
                } elseif ($selectedFitProgram == 'Intense') {
                    $targetCalCount = $activeCalCount * 1.550 * 1.2;
                } else {
                    $targetCalCount = $activeCalCount * 1.725 * 1.2;
                }

                if ($selectedFitMode == 'CalBoost') {
                    $carbs = $targetCalCount * 0.5 / 4;
                    $protein = $targetCalCount * 0.3 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                } else {
                    $carbs = $targetCalCount * 0.4 / 4;
                    $protein = $targetCalCount * 0.4 / 4;
                    $fat = $targetCalCount * 0.2 / 9;
                }
            }

            $DTX = UserMacroData::updateOrCreate(
                ['id' => $request->get('id')],
                [
                    'user_id' => $user_id,
                    'trainer_id' => $user_id,
                    'fit_category' => 'OVERRIDE',
                    'fit_program' => 'OVERRIDE',
                    'fit_mode' => 'OVERRIDE',
                    'lean_body_mass' => round($leanBodyMass,2),
                    'active_calories' => round($activeCalCount,2),

                    'target_calories' => round($targetCalCount,2),
                    'target_carbs' => round($carbs,2),
                    'target_protein' => round($protein,2),
                    'target_fat' => round($fat,2),

                    'override'=>true,
                ]
            );

            return response()->json([
                'success' => true,
                'meta'=>$DTX
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllUserMacrosData($trainer_id): JsonResponse
    {
        try {
            $macrosData = UserMacroData::with('user')->where('trainer_id', '=', $trainer_id)
                ->orderBy('created_at', 'desc')
                ->get();
            foreach ($macrosData as $uid){

                $userDailyFoodData = UserDailyFoodData::where('user_id', '=', $uid->user_id)
                    ->whereDate('created_at', Carbon::today())
                    ->get();

                $uid->daily_calories = 0;
                $uid->daily_carbs = 0;
                $uid->daily_protein = 0;
                $uid->daily_fat = 0;

                foreach ($userDailyFoodData as $food) {
                    $uid->daily_calories += $food->calories;
                    $uid->daily_carbs += $food->carbs;
                    $uid->daily_protein += $food->protein;
                    $uid->daily_fat += $food->fat;
                }
            }

            $DataArray = [];
            foreach ($macrosData as $macro) {
                $DataArray[] = $macro;
            }


            return response()->json($DataArray);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
