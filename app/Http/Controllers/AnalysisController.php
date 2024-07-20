<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalysisController extends Controller
{
    public function index(): JsonResponse
    {
        try {         
            $analyses = Analysis::orderBy('id', 'desc')->get();

            $formattedAnalyses = $analyses->map(function ($analysis) {
                return [
                    'id' => $analysis->id,
                    'type' => $analysis->type,
                    'analysis' => $analysis->analysis,
                    'created_at' => Carbon::parse($analysis->created_at)->format('d/m/Y'),
                ];
            });

            return response()->json($formattedAnalyses, 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show analyses',
                'error' => $th->getMessage()
            ], 400);
        } 
    }   

    public function create(Request $request): JsonResponse {

        try {          
            $analysis = Analysis::create([
                'type' => $request->type,
                'analysis' => $request->analysis
            ]);

            if($analysis)
            {
                return response()->json(
                    ['message' => 'Analysis created successfully'
                ], 200);
            }
    
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

    public function allAnalysesToday(): JsonResponse
    {
        try {         
            $analyses = Analysis::orderBy('id', 'desc')->whereDate('created_at', Carbon::today())->get();

            $formattedAnalyses = $analyses->map(function ($analysis) {
                return [
                    'id' => $analysis->id,
                    'type' => $analysis->type,
                    'analysis' => $analysis->analysis,
                    'created_at' => Carbon::parse($analysis->created_at)->format('d/m/Y'),
                ];
            });

            return response()->json($formattedAnalyses, 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show analyses'
            ], 400);
        } 
    }   

    public function countAnalysesToday()
    {
        try {    
            $analysesCount = Analysis::whereDate('created_at', Carbon::today())->count();

            return response()->json([
                'count' => $analysesCount,
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to count analyses',
                'error' => $th->getMessage()
            ], 400);
        }
    }
}
