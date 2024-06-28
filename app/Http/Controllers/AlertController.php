<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertController extends Controller
{   
    public function index(){

        try {          

            return Alert::all();

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show alerts',
                'error' => $th->getMessage()
            ], 400);
        } 
    }   

    public function create(Request $request): JsonResponse {

        try {
            $path = $request->file->store('alerts', 'public');
            
            if($path){

                Alert::create([
                    'type' => $request->type,
                    'detection' => $request->detection,
                    'confiance' => $request->confiance,
                    'img_url' => $path
                ]);
    
                return response()->json([
                    'message' => 'Alert created successfully'
                ], 200);
            }            

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to created alert',
                'error' => $th->getMessage()
            ], 400);
        }       
    }

    public function show(int $alertId): JsonResponse {

        try {
            $alert = Alert::find($alertId);

            if($alert){
                return response()->json([
                    'alert' => $alert
                ], 200);
            }

            return response()->json([
                'message' => 'Alert not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show alert',
            ], 400);
        }       
    }
}
