<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

$path = __DIR__ . '/public/vuelos';
if (!is_dir($path)) {
    die("Directory not found: $path\n");
}

$files = scandir($path);

echo "Debugging Vuelos Date Parsing\n";
echo "=============================\n";

foreach ($files as $filename) {
    if ($filename === '.' || $filename === '..') continue;
    
    echo "Filename: '$filename'\n";
    echo "Hex: " . bin2hex($filename) . "\n";
    
    // Test Regex
    $regex = '/vuelo_(\d{8})_(\d{6})/i';
    echo "Regex: $regex\n";
    
    if (preg_match($regex, $filename, $matches)) {
        echo "  [MATCH] Regex matched!\n";
        print_r($matches);
        
        $fechaStr = $matches[1] . $matches[2];
        echo "  Combined String: '$fechaStr'\n";
        
        try {
            $fechaObj = Carbon::createFromFormat('YmdHis', $fechaStr);
            echo "  Carbon Parse: " . $fechaObj->format('d/m/Y H:i') . "\n";
        } catch (\Exception $e) {
            echo "  [ERROR] Carbon failed: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  [FAIL] Regex did NOT match.\n";
    }
    echo "--------------------------------------------------\n";
}
