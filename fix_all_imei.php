<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Finding All Bebshader Businesses ===\n\n";

// Find all Bebshader businesses
$businesses = \App\Business::where('name', 'like', '%bebshader%')
    ->orWhere('name', 'like', '%electronics%')
    ->get();

foreach ($businesses as $business) {
    echo "===================================\n";
    echo "Business: " . $business->name . "\n";
    echo "Business ID: " . $business->id . "\n";
    echo "Current enable_imei_tracking: " . ($business->enable_imei_tracking ? 'YES' : 'NO') . "\n";

    // Enable IMEI tracking for this business
    $business->enable_imei_tracking = 1;
    $business->save();
    echo "IMEI tracking ENABLED for business!\n\n";

    // Find iPhone products in this business
    $products = \App\Product::where('business_id', $business->id)
        ->where(function($q) {
            $q->where('name', 'like', '%iphone%')
              ->orWhere('name', 'like', '%samsung%')
              ->orWhere('name', 'like', '%phone%');
        })
        ->get();

    if ($products->count() > 0) {
        echo "Products found:\n";
        foreach ($products as $product) {
            echo "- " . $product->name . " (ID: " . $product->id . ")\n";
            $product->enable_imei_tracking = 1;
            $product->save();
            echo "  IMEI tracking ENABLED!\n";
        }
    } else {
        echo "No phone products found.\n";
        echo "\nFirst 5 products:\n";
        $allProducts = \App\Product::where('business_id', $business->id)->take(5)->get();
        foreach ($allProducts as $p) {
            echo "- ID: " . $p->id . " | " . $p->name . "\n";
        }
    }
    echo "\n";
}

echo "\n=== Summary ===\n";
echo "All matching businesses have been updated with IMEI tracking enabled.\n";
echo "Please logout and login again, or clear browser cache to see changes.\n";
