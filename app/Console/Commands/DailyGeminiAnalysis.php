<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Analysis;
use Carbon\Carbon;
use Gemini;
use Illuminate\Console\Command;

class DailyGeminiAnalysis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-gemini-analysis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {       
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();      
           
        $analyses = Analysis::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        $alerts = Alert::whereDate('created_at', Carbon::today())->get();

        if($analyses->isEmpty())
        {   
            if($alerts->isNotEmpty())
            {
                $formattedAlertsToday = [];

                foreach ($alerts as $alert) {
                    $formattedAlertsToday[] = "Alerta':\n" . $alert->detection;
                }

                $alertsString = implode("\n\n" . str_repeat("=", 40) . "\n\n", $formattedAlertsToday);

                $prompt = 'Tenho duas gatas, Tinha e Lua, os nomes delas, e realizo o monitoramento delas. Meu sistema tem alertas, esses alertas geram análises todos os dias, fora que também posso gerar análises avulsas durante o dia, a partir dos alertas do dia, mas a análise diária que o meu sistema faz, é gerada a partir das análises da semana, gerando sempre uma análise mais completa, porem hoje inicia uma nova semana e ainda não temos analises, então estarei te passando os alertas de hoje, para voce gerar uma nova analise, e gostaria que você fizesse a mais atualizada, e mais detalhada, considere sempre a saúde e bem-estar dos pets, lembre-se de se dedicar ao máximo para uma análise bem fundamentada e detalhada, se achar necessário fale sobre as datas, e também se achar que deve dar algum conselho, fique à vontade, caso ele esteja dentro do tema. Por favor, na sua resposta não quero que me cumprimente, não inicie com título, apenas gere as informações, aqui voce pode iniciar falando Com base no incio da semana, e nos alertas de hoje, alertas: ' . $alertsString;
                $geminiApiKey = env('API_KEY_GEMINI');
                $client = Gemini::client($geminiApiKey);
                $result = $client->geminiPro()->generateContent($prompt);                
                $textResult = $result->text();
                $cleanedResult = str_replace('*', '', strval($textResult));

                Analysis::create([
                        'type' => 'dailys',
                        'analysis' => $cleanedResult
                ]);
            }            
        }
        else
        {
            $formattedAnalyses = [];
            $formattedAlerts = [];
        
            foreach ($analyses as $analysis) {
                $formattedAnalyses[] = "Análise:\n" . $analysis->analysis . "\nData: " . Carbon::parse($analysis->created_at)->format('d/m/Y');
            }

            foreach ($alerts as $alert) {
                $formattedAlerts[] = "Alerta:\n" . $alert->detection;
            }

            $analysesString = implode("\n\n" . str_repeat("=", 40) . "\n\n", $formattedAnalyses);
            $alertsString = implode("\n\n" . str_repeat("=", 40) . "\n\n", $formattedAlerts);

            $prompt = 'Tenho duas gatas, Tinha e Lua, os nomes delas, e realizo o monitoramento delas. Meu sistema tem alertas, esses alertas geram análises todos os dias, fora que também posso gerar análises avulsas durante o dia, a partir dos alertas do dia, mas a análise diária que o meu sistema faz, é gerada a partir das análises da semana, gerando sempre uma análise mais completa, sendo assim irei te passar elas, e gostaria que você fizesse a mais atualizada, e mais detalhada, considere sempre a saúde e bem-estar dos pets, lembre-se de se dedicar ao máximo para uma análise bem fundamentada e detalhada, se achar necessário fale sobre as datas, e também se achar que deve dar algum conselho, fique à vontade, caso ele esteja dentro do tema. Por favor, na sua resposta não quero que me cumprimente, não inicie com título, apenas gere as informações, enfatize que está é a analise diaria, lembre-se de seguir esse pipeline, voce inicia falando sobre a analise da semana, com o conteudo da semana que te passarei, sempre inicie falando Com base na analise da semana, e fale sobre a tinha e lua, em seguida, fale sobre os alertas do dia, caso haja, depois fale a avaliação, e por ultimo recomendações, não precisa colocar datas, análises: ' . $analysesString . "\n\nAlertas de Hoje:\n" . $alertsString;
            $geminiApiKey = env('API_KEY_GEMINI');
            $client = Gemini::client($geminiApiKey);
            $result = $client->geminiPro()->generateContent($prompt);
            $textResult = $result->text();
            $cleanedResult = str_replace('*', '', strval($textResult));

            Analysis::create([
                    'type' => 'daily',
                    'analysis' => $cleanedResult
            ]);
        }
    }      
}