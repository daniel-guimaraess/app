<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PetController extends Controller
{   
    public function index(){

        try {      
            $pets = Pet::all();
            
            $formattedPets = $pets->map(function ($pet) {
                return [
                    'id' => $pet->id,
                    'type' => $pet->type,
                    'name' => $pet->name,
                    'date_birth' => $pet->date_birth,
                    'race' => $pet->race,
                    'weight' => round($pet->weight, 2),
                    'img_url' => env('APP_URL').'/storage/'.$pet->img_url,                
                ];
            });
            
            return response()->json($formattedPets, 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show pets',
                'error' => $th->getMessage()
            ], 400);
        } 
    }   

    public function create(Request $request): JsonResponse {
        
        try {
            $path = $request->file->store('pets/img', 'public');

            Pet::create([
                'type' => $request->type,
                'name' => $request->name,
                'date_birth' => $request->date_birth,
                'race' => $request->race,
                'weight' => $request->weight,
                'img_url' => $path
            ]);

            return response()->json([
                'message' => 'Pet created successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to created pet',
                'error' => $th->getMessage()
            ], 400);
        }       
    }

    public function update(Request $request, $petId): JsonResponse {

        try {
            $pet = Pet::find($petId);

            if($pet)
            {
                $pet->update([
                    'type' => $request->type,
                    'name' => $request->name,
                    'date_birth' => $request->date_birth,
                    'race' => $request->race,
                    'weight' => $request->weight,
                    'img_url' => $request->img_url
                ]);
            }

            return response()->json([
                'message' => 'Pet updated successfully'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to updated pet'
            ], 400);
        }       
    }

    public function show(int $petId): JsonResponse {

        try {
            $pet = Pet::find($petId);

            if($pet){
                return response()->json([
                    'user' => $pet
                ], 200);
            }

            return response()->json([
                'message' => 'Pet not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to show pet',
            ], 400);
        }       
    }

    public function destroy(int $petId): JsonResponse {

        try {
            $pet = Pet::find($petId);

            if($pet){
                $pet->delete();

                return response()->json([
                    'user' => 'Pet deleted successfully'
                ], 200);
            }

            return response()->json([
                'message' => 'Pet not found',
            ], 404);                

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to delete pet',
            ], 400);
        }       
    }
}
