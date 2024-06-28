<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(){

        try {         
            return Analysis::all();

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
}
