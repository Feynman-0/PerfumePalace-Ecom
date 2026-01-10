<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Syncing Prices from product_attribute_values to product_flat ===\n\n";

// Get all products with price attribute values
$productsWithPrices = DB::table('product_attribute_values')
    ->where('attribute_id', 11) // price attribute
    ->whereNotNull('float_value')
    ->where('float_value', '>', 0)
    ->get();

echo "Found " . $productsWithPrices->count() . " products with price attributes\n\n";

$updated = 0;
foreach ($productsWithPrices as $pav) {
    $affected = DB::table('product_flat')
        ->where('product_id', $pav->product_id)
        ->update([
            'price' => $pav->float_value,
            'special_price' => null
        ]);
    
    if ($affected > 0) {
        $product = DB::table('product_flat')->where('product_id', $pav->product_id)->first();
        echo "✓ Updated {$product->sku}: \${$pav->float_value}\n";
        $updated++;
    }
}

echo "\n✅ Updated {$updated} products in product_flat table\n";

// Verify
echo "\n=== Verification ===\n";
echo "Products in product_flat with price > 0: " . DB::table('product_flat')->where('price', '>', 0)->count() . "\n";
echo "Products in product_flat with price = 0: " . DB::table('product_flat')->where('price', '=', 0)->orWhereNull('price')->count() . "\n";

// Show sample
echo "\nSample of updated products:\n";
$samples = DB::table('product_flat')
    ->select('sku', 'name', 'price')
    ->where('price', '>', 0)
    ->orderBy('price')
    ->limit(10)
    ->get();
foreach ($samples as $s) {
    echo "  {$s->sku}: \${$s->price} - {$s->name}\n";
}
