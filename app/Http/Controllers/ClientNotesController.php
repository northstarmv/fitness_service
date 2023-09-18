<?php

namespace App\Http\Controllers;

use App\Models\ClientNotes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientNotesController extends Controller
{
    public function getClientNotes(Request $request):JsonResponse
    {
        $user = User::find($request->get('auth')['id']);
        if($user->role == 'trainer'){
            return response()->json(ClientNotes::with('client')
                ->where('trainer_id','=', $user->id)
                ->get());
        } else {
            return response()->json(ClientNotes::with('trainer')
                ->where('client_id','=', $user->id)
                ->get());
        }

    }

    public function createClientNote(Request $request)
    {
        $this->validate($request,[
            'client_id' => 'required|integer',
            'trainer_id' => 'required|integer',
            'note' => 'required|string',
            'amount' => 'required|numeric',
            'payment_term' => 'required|string',
            'start_date' => 'required|date',
        ]);

        try {
            $CN = ClientNotes::create([
                'client_id' => $request->get('client_id'),
                'trainer_id' => $request->get('trainer_id'),
                'note' => $request->get('note'),
                'service'=> $request->get('service'),
                'amount' => $request->get('amount'),
                'payment_term' => $request->get('payment_term'),
                'start_date' => $request->get('start_date'),
            ]);
            return response()->json(
                ['success' => true,
                    'message' => 'Client note created successfully.',
                    'data' => $CN
                ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false,
                'data' => $e->getMessage(),
                'message' => 'Something went wrong. Try Again!'],500);
        }
    }

    public function toggleClientNote(Request $request)
    {
        try {
            $clientNote = ClientNotes::find($request->get('id'));
            $clientNote->active = !$clientNote->active;
            $clientNote->save();
            return response()->json(['success' => true, 'message' => 'Client note deactivated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try Again!']);
        }
    }

    public function deleteClientNote(Request $request)
    {
        try {
            ClientNotes::find($request->get('id'))->delete();
            return response()->json(['success' => true, 'message' => 'Client note deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Something went wrong. Try Again!']);
        }
    }
}
