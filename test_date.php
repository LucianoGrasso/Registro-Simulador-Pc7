<?php

require 'vendor/autoload.php';

use Carbon\Carbon;

$filenames = [
    "vuelo_20251128_094707.json",
    "vuelo_20251201_084730.json",
    "vuelo_20251201_103111.json",
    "vuelo_20251201_103515.json"
];

foreach ($filenames as $filename) {
    echo "Processing: $filename\n";
    $parts = explode('_', str_replace('.json', '', $filename));
    
    if (count($parts) >= 3) {
        try {
            $fechaStr = $parts[1] . $parts[2];
            echo "  Date String: $fechaStr\n";
            $fechaObj = Carbon::createFromFormat('YmdHis', $fechaStr);
            echo "  Parsed: " . $fechaObj->format('d/m/Y H:i') . "\n";
            echo "  Timestamp: " . $fechaObj->timestamp . "\n";
        } catch (\Exception $e) {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  Parts count < 3\n";
    }
    echo "----------------\n";
}
