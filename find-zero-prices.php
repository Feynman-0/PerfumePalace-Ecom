<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Finding products with \$0.00 prices...\n\n";

$zeroProducts = DB::table('product_flat')
    ->where('price', '=', 0)
    ->orWhereNull('price')
    ->get();

echo "Found {$zeroProducts->count()} products with \$0.00:\n\n";

foreach ($zeroProducts as $product) {
    echo "- {$product->name} (ID: {$product->product_id})\n";
}
