<?php

namespace App\Http\Controllers;

use App\Models\UserBloodPressureData;
use App\Models\UserBloodSugarData;
use App\Models\UserBodyFatData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthServicesController extends Controller
{
    public function getBMIPIData(Request $request): JsonResponse
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

            if ($userBMI > 30) {
                $userBMICat = 'Obesity';
            } else if ($userBMI > 25) {
                $userBMICat = 'Overweight';
            } else if ($userBMI > 18.5) {
                $userBMICat = 'Normal';
            } else {
                $userBMICat = 'Underweight';
            }

            if ($userPI > 17) {
                $userPICat = 'Obesity';
            } else if ($userPI > 15) {
                $userPICat = 'Overweight';
            } else if ($userPI > 11) {
                $userPICat = 'Normal';
            } else {
                $userPICat = 'Underweight';
            }

            return response()->json([
                'success' => true,
                'BMI' => $userBMI,
                'PI' => $userPI,
                'BMICat' => $userBMICat,
                'PICat' => $userPICat,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getBodyFatMassData(Request $request): JsonResponse
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


            return response()->json([
                'success' => true,
                'BodyFat'=> round($body_fat, 2),
                'MuscleMass'=> round($muscle_mass, 2),
                'BodyFatCategory'=> $body_fat_category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getBloodSugarData(Request $request): JsonResponse
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

            return response()->json([
                'success' => true,
                'FBS'=> $fasting_blood_sugar,
                'RBS'=> $random_blood_sugar,
                'FBSCategory'=> $fbs_category,
                'RBSCategory'=> $rbs_category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getBloodPressureData(Request $request): JsonResponse
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

            return response()->json([
                'success' => true,
                'Systolic'=> $systolic,
                'Diastolic'=> $diastolic,
                'BloodPressureCategory'=> $blood_pressure_category,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }
}
