<?php

namespace App\Http\Controllers;

use App\Models\ResourceData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function GetResources(): JsonResponse
    {
        try {
            return response()->json(ResourceData::all());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function AddOrUpdateResource(Request $request): JsonResponse
    {
        try {
            ResourceData::updateOrCreate([
                'id' => $request->get('id')
            ], [
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'category' => $request->get('category'),
                'article' => $request->get('article'),
                'image' => $request->get('image')
            ]);

            return response()->json(['message' => 'Resource added successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function DeleteResource(Request $request):JsonResponse
    {
        try {
            ResourceData::where('id', $request->get('id'))->delete();
            return response()->json(['message' => 'Resource deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchResourceData(Request $request): JsonResponse
    {
        if ($request->get('search_key') == 'ALL') {
            return response()->json(ResourceData::all());
        } else {
            $resource = $request->get('search_key');
            $resource = ResourceData::where('title', 'LIKE', '%' . $resource . '%')->get();
            return response()->json($resource);
        }
    }
}
