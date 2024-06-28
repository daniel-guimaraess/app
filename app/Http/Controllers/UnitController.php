<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function create(Request $request): JsonResponse {

        try {
            Unit::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);

            return response()->json([
                'message' => 'Unit created successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to created unit',
                'error' => $th->getMessage()
            ], 400);
        }       
    }

    public function update(Request $request, $unitId): JsonResponse {

        try {
            $unit = Unit::find($unitId);

            if($unit)
            {
                $unit->update([
                    'name' => $request->name
                ]);
            }

            return response()->json([
                'message' => 'Unit updated successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to updated unit',
                'error' => $th->getMessage()
            ], 400);
        }       
    }

    public function show(int $unitId): JsonResponse {

        try {
            $unit = Unit::find($unitId);

            if($unit){
                return response()->json([
                    'unit' => $unit
                ], 200);
            }

            return response()->json([
                'message' => 'Unit not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show unit',
            ], 400);
        }       
    }

    public function destroy(int $unitId): JsonResponse {

        try {
            $unit = Unit::find($unitId);

            if($unit){
                $unit->delete();

                return response()->json([
                    'user' => 'Unit deleted successfully'
                ], 200);
            }

            return response()->json([
                'message' => 'Unit not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to delete unit',
            ], 400);
        }       
    }
}
