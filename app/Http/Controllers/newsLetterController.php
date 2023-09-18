<?php

namespace App\Http\Controllers;


use App\Models\newsletters;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class newsLetterController extends Controller
{
    public function GetNewsletters(): JsonResponse
    {
        try {
            return response()->json(newsletters::all());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function AddOrUpdateNewsletter(Request $request): JsonResponse
    {
        try {
            newsletters::updateOrCreate([
                'id' => $request->get('id')
            ], [
                'title' => $request->get('title'),
                'article' => $request->get('article'),
                'image' => $request->get('image')
            ]);

            return response()->json(['message' => 'Newsletter upserted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function DeleteNewsletter(Request $request):JsonResponse
    {
        try {
            newsletters::where('id', $request->get('id'))->delete();
            return response()->json(['message' => 'Newsletter deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchNewsletter(Request $request): JsonResponse
    {
        if ($request->get('search_key') == 'ALL') {
            return response()->json(newsletters::all());
        } else {
            $resource = $request->input('resource');
            $resource = newsletters::where('title', 'LIKE', '%' . $resource . '%')->get();
            return response()->json($resource);
        }
    }
}
