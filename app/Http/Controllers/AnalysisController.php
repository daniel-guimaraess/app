<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(){

        try {         
            $analysis = Analysis::all();

            $formattedAlerts = $analysis->map(function ($analysis) {
                return [
                    'id' => $analysis->id,
                    'analysis' => $analysis->analysis,
                    'created_at' => $analysis->created_at_for_humans,
                ];
            });

            return response()->json($formattedAlerts);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show analysis',
                'error' => $th->getMessage()
            ], 400);
        } 
    }   

    public function create(Request $request): JsonResponse {

        try {          
            Analysis::create([
                'analysis' => $request->analysis
            ]);
    
        } catch (\Throwable $th) {
    
            return response()->json([
                'message' => 'Failed to created analysis',
                'error' => $th->getMessage()
            ], 400);
        }  
    }

    public function show(int $analysisId): JsonResponse {

        try {
            $analysis = Analysis::find($analysisId);

            if($analysis){
                return response()->json([
                    'analysis' => $analysis
                ], 200);
            }

            return response()->json([
                'message' => 'Analysis not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show analysis',
            ], 400);
        }       
    }

    public function countAnalysesToday()
    {
        try {    
            $count = Analysis::whereDate('created_at', Carbon::today())->count();

            return response()->json([
                'count' => $count,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to count analysis',
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
