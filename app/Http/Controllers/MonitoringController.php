<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{   
    private $authController;
    public function __construct(AuthController $authController)
    {
        $this->authController = $authController;
    }

    public function startMonitoring()
    {   
        $token = $this->authController->autenticationVisionVortex();
        
        $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->get(env('VISIONVORTEX_URL').'/start');
   
        $jsonData = $response->json();
         
        if(!$response->ok()){

            return response()->json(['message' => $jsonData['message']], 400);
        }

        return response()->json(['message' => $jsonData['message']], 200);
    }

    public function stopMonitoring()
    {
        $token = $this->authController->autenticationVisionVortex();
        
        $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->get(env('VISIONVORTEX_URL').'/stop');
        
        $jsonData = $response->json();

        if(!$response->ok()){
            return response()->json(['message' => $jsonData['message']], 400);
        }

        return response()->json(['message' => $jsonData['message']], 200);
    }

    public function statusMonitoring()
    {
        $token = $this->authController->autenticationVisionVortex();
        
        $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->get(env('VISIONVORTEX_URL').'/status');

        $jsonData = $response->json();

        if(!$response->ok()){
            return response()->json(['message' => $jsonData['message']], 400);
        }


        return response()->json(['status' => $jsonData['message']], 200);
    }
}
