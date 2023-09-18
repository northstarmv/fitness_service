<?php

namespace App\Http\Controllers;

use App\Models\UserWatchData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserWatchDataController extends Controller
{
    public function CreateOrUpdateData(Request $request):JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'data' => 'required',
        ]);

        try {
            $DTX  = UserWatchData::where('user_id', $request->get('user_id'))->first();
            if($DTX) {
                $DT = $DTX->data;

                if($DT != $request->get('data')){
                    UserWatchData::updateOrCreate(
                        ['user_id' => $request->get('user_id'),],
                        [
                            'data' => $request->get('data'),
                        ]
                    );
                    return response()->json(['status' => 'success', 'message' => 'Data Updated Successfully']);
                } else {
                    return response()->json(['status' => 'success', 'message' => 'Data Is Same as before!']);
                }
            } else {
                UserWatchData::updateOrCreate(
                    ['user_id' => $request->get('user_id'),],
                    [
                        'data' => $request->get('data'),
                    ]
                );
                return response()->json(['status' => 'success', 'message' => 'Data Added Successfully']);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function GetData(Request $request):JsonResponse
    {
        try {
            $HasData = UserWatchData::where('user_id','=' ,$request->get('user_id'))->first();

            if($HasData) {
                return response()->json([
                    'status' => 'success',
                    'data' => $HasData->data,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'data' => [],
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
