<?php

namespace App\Http\Controllers;

use App\Console\Kernel;
use App\Models\Food;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function searchFood($search_key): JsonResponse
    {
        $foods = Food::where('name', 'like', '%'.$search_key.'%')->get();
        return response()->json($foods);
    }

    public function getAllFoods(): JsonResponse
    {
        return response()->json(Food::all());
    }

    public function newFood(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|string',
            'potion' => 'required|string',
            'calories' => 'required|numeric',
            'carbs' => 'required|numeric',
            'proteins' => 'required|numeric',
            'fat' => 'required|numeric',
            'sat_fat' => 'required|numeric',
            'fibers' => 'required|numeric',
        ]);
        try{
            $CreatedFood = Food::create([
                'name' => $request->get('name'),
                'potion' => $request->get('potion'),
                'calories' => $request->get('calories'),
                'carbs' => $request->get('carbs'),
                'proteins' => $request->get('proteins'),
                'fat' => $request->get('fat'),
                'sat_fat' => $request->get('sat_fat'),
                'fibers' => $request->get('fibers'),
                'ingredients'=>$request->get('ingredients')
            ]);
            return response()->json(['success' => true,'food'=>$CreatedFood]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editFood(Request $request):JsonResponse
    {
        $this->validate($request, [
            'id'=>'required|integer',
            'name' => 'required|string',
            'potion' => 'required|string',
            'calories' => 'required|numeric',
            'carbs' => 'required|numeric',
            'proteins' => 'required|numeric',
            'fat' => 'required|numeric',
            'sat_fat' => 'required|numeric',
            'fibers' => 'required|numeric',
        ]);

        try {
            Food::where('id', $request->get('id'))->update([
                'name' => $request->get('name'),
                'potion' => $request->get('potion'),
                'calories' => $request->get('calories'),
                'carbs' => $request->get('carbs'),
                'proteins' => $request->get('proteins'),
                'fat' => $request->get('fat'),
                'sat_fat' => $request->get('sat_fat'),
                'fibers' => $request->get('fibers'),
                'ingredients'=>$request->get('ingredients')
            ]);

            return response()->json(['success' => true]);
        }catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteFood(Request $request):JsonResponse
    {
        $this->validate($request, [
            'id'=>'required|integer',
        ]);

        try {
            Food::where('id', $request->get('id'))->delete();
            return response()->json(['success' => true]);
        }catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
