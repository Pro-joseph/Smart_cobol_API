<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCobolGenerator
{
    private string $apiKey;
    private string $projectId;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.ibm_watsonx.api_key');
        $this->projectId = config('services.ibm_watsonx.project_id');
        $this->apiUrl = config('services.ibm_watsonx.url', 'https://us-south.ml.cloud.ibm.com');
    }

    public function generate($operations)
    {
        if (empty($this->apiKey) || empty($this->projectId)) {
            return $this->generateFallback($operations);
        }

        $prompt = $this->buildPrompt($operations);
        
        try {
            $response = $this->callWatsonx($prompt);
            return $response;
        } catch (\Exception $e) {
            Log::error('IBM watsonx.ai error: ' . $e->getMessage());
            return $this->generateFallback($operations);
        }
    }

    private function buildPrompt($operations)
    {
        return "Generate production-ready Laravel code only. No explanation. No duplication. Use clean architecture.

Convert this COBOL operations JSON into a working Laravel API:

" . json_encode($operations, JSON_PRETTY_PRINT) . "

Output ONLY valid PHP code with:
- Service class with execute() method
- Controller with single endpoint
- Route definition
- No comments
- No markdown
- No explanations";
    }

    private function callWatsonx($prompt)
    {
        // Get IBM Cloud IAM token
        $token = $this->getIAMToken();

        // Call watsonx.ai text generation API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($this->apiUrl . '/ml/v1/text/generation?version=2023-05-29', [
            'model_id' => 'ibm/granite-13b-chat-v2',
            'input' => $prompt,
            'parameters' => [
                'max_new_tokens' => 2000,
                'temperature' => 0.7,
                'top_p' => 1,
                'top_k' => 50
            ],
            'project_id' => $this->projectId
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['results'][0]['generated_text'] ?? 'No response generated';
        }

        throw new \Exception('watsonx.ai API error: ' . $response->body());
    }

    private function getIAMToken()
    {
        $response = Http::asForm()->post('https://iam.cloud.ibm.com/identity/token', [
            'grant_type' => 'urn:ibm:params:oauth:grant-type:apikey',
            'apikey' => $this->apiKey
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        throw new \Exception('Failed to get IAM token');
    }

    private function generateFallback($operations)
    {
        // Fallback: Generate basic Laravel code template
        $serviceName = 'CobolOperationService';
        $controllerName = 'CobolOperationController';

        return "// Service Class
<?php

namespace App\Services;

class {$serviceName}
{
    public function execute(array \$data)
    {
        \$result = [];
        
        // Process operations
        foreach (\$data['operations'] ?? [] as \$op) {
            if (\$op['type'] === 'add') {
                \$result[] = [
                    'operation' => 'add',
                    'from' => \$op['from'],
                    'to' => \$op['to'],
                    'result' => 'Added ' . \$op['from'] . ' to ' . \$op['to']
                ];
            }
            
            if (\$op['type'] === 'subtract') {
                \$result[] = [
                    'operation' => 'subtract',
                    'from' => \$op['from'],
                    'to' => \$op['to'],
                    'result' => 'Subtracted ' . \$op['from'] . ' from ' . \$op['to']
                ];
            }
        }
        
        return \$result;
    }
}

// Controller Class
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\\{$serviceName};
use Illuminate\Http\Request;

class {$controllerName} extends Controller
{
    public function __construct(
        private {$serviceName} \$service
    ) {}
    
    public function execute(Request \$request)
    {
        \$validated = \$request->validate([
            'operations' => 'required|array'
        ]);
        
        \$result = \$this->service->execute(\$validated);
        
        return response()->json([
            'success' => true,
            'data' => \$result
        ]);
    }
}

// API Routes (add to routes/api.php)
Route::post('/cobol/execute', [App\Http\Controllers\Api\\{$controllerName}::class, 'execute']);
";
    }
}