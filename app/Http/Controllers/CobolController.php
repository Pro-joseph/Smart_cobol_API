<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CobolParser;
use App\Services\AiCobolGenerator;

class CobolController extends Controller
{
    // page ديال upload
    public function index()
    {
        return view('main');
    }

    // process ديال الملف
    public function generate(Request $request, CobolParser $parser, AiCobolGenerator $aiGenerator)
    {
        $request->validate([
            'cobol_file' => 'required|file'
        ]);

        // نحفظو الملف
        $file = $request->file('cobol_file');
        
        // Check if file was uploaded
        if (!$file) {
            return back()->withErrors(['cobol_file' => 'No file was uploaded']);
        }

        // Store the file using public disk (storage/app/public/cobol)
        $path = $file->store('cobol', 'public');
        
        // Build full path with proper separators
        $fullPath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path));
        
        // Check if file exists before parsing
        if (!file_exists($fullPath)) {
            return back()->withErrors(['cobol_file' => 'File was not saved correctly. Path: ' . $fullPath]);
        }

        // نستعمل parser
        $result = $parser->parse($fullPath);

        // Generate Laravel API code using AI
        $generatedCode = $aiGenerator->generate($result);

        // نرجعو النتيجة للواجهة
        return view('main', [
            'result' => $result,
            'ai_code' => $generatedCode
        ]);
    }

    // Test API endpoint
    public function test(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'nullable|numeric',
            'tax' => 'nullable|numeric',
            'income' => 'nullable|numeric',
            'rent' => 'nullable|numeric',
            'penalty' => 'nullable|numeric',
        ]);

        // simulation ديال execution (نفس logic ديال generated service)
        $balance = 0;

        if (isset($validated['amount'])) {
            $balance += $validated['amount'];
        }

        if (isset($validated['income'])) {
            $balance += $validated['income'];
        }

        if (isset($validated['tax'])) {
            $balance -= $validated['tax'];
        }

        if (isset($validated['rent'])) {
            $balance -= $validated['rent'];
        }

        if (isset($validated['penalty'])) {
            $balance -= $validated['penalty'];
        }

        return response()->json([
            'input' => $validated,
            'final_balance' => $balance
        ]);
    }
}
