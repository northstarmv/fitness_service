<?php

namespace App\Http\Controllers;

use App\Models\LabReports;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LabReportController extends Controller
{
    public function saveLabReport(Request $request): JsonResponse
    {
        $this->validate($request, [
            'client_id' => 'required|integer',
            'report_name' => 'required|string',
            'report_result' => 'required|string',
            'report_type' => 'required|string',
            'report_date' => 'required|date',
            'report_description' => 'required|string',
            'report_url' => 'required|string',
        ]);


        try {


            $labReport = new LabReports();
            $labReport->client_id = $request->get('client_id');
            $labReport->report_name = $request->get('report_name');
            $labReport->report_result = $request->get('report_result');
            $labReport->report_type = $request->get('report_type');
            $labReport->report_date = $request->get('report_date');
            $labReport->report_description = $request->get('report_description');
            $labReport->report_url = $request->get('report_url');
            $labReport->save();


            return response()->json([
                'success' => true,
                'message' => 'Lab Report Uploaded Successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 200);
        }
    }

    public function getMyReports(Request $request)
    {
        return response()->json(LabReports::where('client_id','=', $request->get('client_id'))->get());
    }

    public function deleteMyReports(Request $request): JsonResponse
    {
        LabReports::find($request->get('id'))->delete();
        return response()->json(['success' => true, 'message' => 'Lab Report Deleted Successfully']);
    }
}
