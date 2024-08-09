<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Pet;
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
                    'pet_id' => $alert->pet_id,
                    'pet_name' => $alert->pet_name,
                    'detection' => $alert->detection,
                    'confidence' => round($alert->confidence, 2) * 100,
                    'img_url' => env('APP_URL').'/storage/'.$alert->img_url,
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
                    'pet_id' => $alert->pet_id,
                    'pet_name' => $alert->pet_name,
                    'detection' => $alert->detection,
                    'confidence' => round($alert->confidence, 2) * 100,
                    'img_url' => env('APP_URL').'/storage/'.$alert->img_url,
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

    public function allAlertsToday(){

        try {      
            $alerts = Alert::orderBy('id', 'desc')->whereDate('created_at', Carbon::today())->get();

            $formattedAlerts = $alerts->map(function ($alert) {
                return [
                    'id' => $alert->id,
                    'type' => $alert->type,
                    'pet_id' => $alert->pet_id,
                    'pet_name' => $alert->pet_name,
                    'detection' => $alert->detection,
                    'confidence' => round($alert->confidence, 2) * 100,
                    'img_url' => env('APP_URL').'/storage/'.$alert->img_url,
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

    public function allAlertsTodayGemini()
    {
        try {
            $alerts = Alert::whereDate('created_at', Carbon::today())->pluck('detection')->toArray();

            if($alerts == null)
            {
                return response()->json([
                    'prompt' => '',
                ], 200);
            }
            $listFormatted = implode(" | ", $alerts);
            $formattedString = "" . $listFormatted;

            $prompt = 'Tenho duas gatas, Tinha e Lua, os nomes delas, e realizo o monitoramento delas. Abaixo tenho os alertas do dia e gostaria que você fizesse uma análise mais precisa e detalhada sobre ambas as gatas, visando sempre o bem-estar e saúde delas. Caso seja necessário fazer alguma recomendação, fique à vontade. Por favor, na sua resposta não quero que me cumprimente, não inicie com título, e sem formatação também, apenas se achar necessário faça tópicos, os alertas padrões são, comer, beber agua e ir na caixa de areia, caso não tenha alertas sobre algo, considere isso também, sempre visando a sáude e bem estar, lembre-se de sempre se dedicar ao máximo para uma analise bem fudamentada e bem formulada, com informações importantes, por favor. Alertas:' . $formattedString;
            
            return response()->json([
                'prompt' => $prompt,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Failed to get alerts',
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
                    'pet_id' => $request->pet_id,
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
                'pet_id' => $alert->pet_id,
                'detection' => $alert->detection,
                'confidence' => round($alert->confidence, 2) * 100,
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

    public function getChartData(): JsonResponse {

        try {
            $pets = Pet::all();

            $response = [];

            $countAllAlertsToday = Alert::whereDate('created_at', Carbon::today())->count();
            
            foreach($pets as $pet)
            {
                $countAlerts = Alert::where('pet_id', $pet->id)->whereDate('created_at', Carbon::today())->count();
        
                if($countAlerts > 0)
                {   
                    $response[$pet->name] = ['percentage' => ($countAlerts / $countAllAlertsToday) * 100];
                }
                else{
                    $response[$pet->name] = ['percentage' => 0]; 
                }                               
            }

            return response()->json($response, 200);             

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Failed to get data to chart',
                'error' => $th->getMessage()
            ], 400);
        }       
    }
}
