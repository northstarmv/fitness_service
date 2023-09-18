<?php

namespace App\Http\Controllers;

use App\Models\UserBloodPressureData;
use App\Models\UserBloodSugarData;
use App\Models\UserBMIPIData;
use App\Models\UserDailyFoodData;
use App\Models\UserMiscData;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserHealthChartsController extends Controller
{
    public function userHealthCharts(Request $request):JsonResponse
    {
        try {
            $MacroChartData = [];
            $MealData = UserDailyFoodData::where('user_id','=',$request->get('user_id'))
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->get();



            foreach ($MealData as $meal) {
                $MacroChartData[]=[
                    'date' => $meal->created_at->format('Y-m-d'),
                    'calories' => $meal->calories,
                    'carbs' => $meal->carbs,
                    'protein' => $meal->protein,
                    'fat' => $meal->fat,
                ];
            }


            $BMIChartData = [];
            $BMIData = UserBMIPIData::where('user_id','=',$request->get('user_id'))
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->get();

            foreach ($BMIData as $bmi) {
                $BMIChartData[]=[
                    'date' => $bmi->created_at->format('Y-m-d'),
                    'bmi' => $bmi->bmi,
                    'pi' => $bmi->pi,
                    'weight' => $bmi->weight,
                    'height' => $bmi->height,
                ];
            }

            $BPChartData = [];
            $BPData = UserBloodPressureData::where('user_id','=',$request->get('user_id'))
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->get();

            foreach ($BPData as $bp) {
                $BPChartData[]=[
                    'date' => $bp->created_at->format('Y-m-d'),
                    'systolic' => $bp->systolic,
                    'diastolic' => $bp->diastolic,
                ];
            }

            $BSChartData = [];
            $BSData = UserBloodSugarData::where('user_id','=',$request->get('user_id'))
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->get();

            foreach ($BSData as $bs) {
                $BSChartData[]=[
                    'date' => $bs->created_at->format('Y-m-d'),
                    'fasting' => $bs->fasting_blood_sugar,
                    'random' => $bs->random_blood_sugar,
                ];
            }

            $MiscChartData = [];
            $MiscData = UserMiscData::where('user_id','=',$request->get('user_id'))
                ->where('created_at','>=',Carbon::now()->subDays(30))
                ->get();

            foreach ($MiscData as $ms) {
                $MiscChartData[]=[
                    'date' => $ms->created_at->format('Y-m-d'),
                    //parse string to float.
                    'bust' => floatval($ms->misc_data['bust']),
                    'stomach' => floatval($ms->misc_data['stomach']),
                    'calves' => floatval($ms->misc_data['calves']),
                    'hips' => floatval($ms->misc_data['hips']),
                    'thighs' => floatval($ms->misc_data['thighs']),
                    'l_arm' => floatval($ms->misc_data['l_arm']),
                    'r_arm' => floatval($ms->misc_data['r_arm']),
                ];
            }


            return response()->json([
                'MacroChartData' => $MacroChartData,
                'BMIPIChartData' => $BMIChartData,
                'BPChartData' => $BPChartData,
                'BSChartData' => $BSChartData,
                'MiscChartData' => $MiscChartData,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
