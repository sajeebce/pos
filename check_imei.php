<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== IMEI Tracking Status Check ===\n\n";

// Check Business setting
$business = \App\Business::find(71);
if ($business) {
    echo "Business: " . $business->name . "\n";
    echo "enable_imei_tracking: " . ($business->enable_imei_tracking ? 'YES' : 'NO') . "\n\n";
} else {
    echo "Business 71 not found\n\n";
}

// Check Product
$product = \App\Product::where('name', 'like', '%iphone%')->first();
if ($product) {
    echo "Product: " . $product->name . "\n";
    echo "Product ID: " . $product->id . "\n";
    echo "enable_imei_tracking: " . ($product->enable_imei_tracking ? 'YES' : 'NO') . "\n\n";

    // Enable IMEI tracking for this product
    echo "Enabling IMEI tracking for this product...\n";
    $product->enable_imei_tracking = 1;
    $product->save();
    echo "Done! enable_imei_tracking is now: " . ($product->enable_imei_tracking ? 'YES' : 'NO') . "\n";
} else {
    echo "iPhone product not found\n";

    // List all products
    echo "\nAll products:\n";
    $products = \App\Product::where('business_id', 71)->take(10)->get();
    foreach ($products as $p) {
        echo "- " . $p->id . ": " . $p->name . " (IMEI: " . ($p->enable_imei_tracking ? 'YES' : 'NO') . ")\n";
    }
}
