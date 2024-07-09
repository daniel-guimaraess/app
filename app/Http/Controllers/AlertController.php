<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlertController extends Controller
{   
    public function index(){

        try {      
            $alerts = Alert::orderBy('id', 'desc')->get();

            $formattedAlerts = $alerts->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'detection' => $alert->detection,
                    'confidence' => $alert->confidence,
                    'img_url' => $alert->img_url,
                    'created_at' => $alert->created_at_for_humans,
                ];
            });

            return response()->json($formattedAlerts);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show alerts',
                'error' => $th->getMessage()
            ], 400);
        } 
    }   

    public function lastAlerts()
    {
        try {
            $alerts = Alert::orderBy('id', 'desc')->take(5)->get();

            $formattedAlerts = $alerts->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'detection' => $alert->detection,
                    'confidence' => $alert->confidence,
                    'img_url' => $alert->img_url,
                    'created_at' => $alert->created_at_for_humans,
                ];
            });

            return response()->json($formattedAlerts);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to show alerts',
                'error' => $th->getMessage()
            ], 400);
        }
    }

    public function countAlertsToday()
    {
        try {
            $count = Alert::whereDate('created_at', Carbon::today())->count();

            return response()->json([
                'count' => $count,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to count alerts',
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
                    'confidence' => $request->confidence,
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

            $formattedAlert = [
                'id' => $alert->id,
                'type' => $alert->type,
                'detection' => $alert->detection,
                'confidence' => $alert->confidence,
                'img_url' => $alert->img_url,
                'created_at' => $alert->created_at->diffForHumans(),
            ];

            if($formattedAlert){
                return response()->json($formattedAlert, 200);
            }

            return response()->json([
                'message' => 'Alert not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show alert',
                'error' => $th->getMessage()
            ], 400);
        }       
    }
}
