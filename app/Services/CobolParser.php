<?php

namespace App\Services;

class CobolParser
{
    public function parse($path)
    {
        $content = file_get_contents($path);
        $lines = explode("\n", $content);

        $operations = [];

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) {
                continue;
            }

            // Use md5 hash to prevent duplicates
            $key = md5($line);

            if (preg_match('/ADD (\w+) TO (\w+)/i', $line, $m)) {
                $operations[$key] = [
                    'type' => 'add',
                    'from' => $m[1],
                    'to' => $m[2],
                ];
            }

            if (preg_match('/SUBTRACT (\w+) FROM (\w+)/i', $line, $m)) {
                $operations[$key] = [
                    'type' => 'subtract',
                    'from' => $m[1],
                    'to' => $m[2],
                ];
            }
        }

        return array_values($operations);
    }
}

// Made with Bob
