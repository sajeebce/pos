<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Finding Bebshader Electronics Business ===\n\n";

// Find Bebshader Electronics business
$business = \App\Business::where('name', 'like', '%bebshader%')->first();
if ($business) {
    echo "Business Found: " . $business->name . "\n";
    echo "Business ID: " . $business->id . "\n";
    echo "Current enable_imei_tracking: " . ($business->enable_imei_tracking ? 'YES' : 'NO') . "\n\n";

    // Enable IMEI tracking for this business
    echo "Enabling IMEI tracking for this business...\n";
    $business->enable_imei_tracking = 1;
    $business->save();
    echo "Done! Business enable_imei_tracking is now: YES\n\n";

    // Find iPhone product in this business
    $product = \App\Product::where('business_id', $business->id)
        ->where('name', 'like', '%iphone%')
        ->first();

    if ($product) {
        echo "Product Found: " . $product->name . "\n";
        echo "Product ID: " . $product->id . "\n";
        echo "Current enable_imei_tracking: " . ($product->enable_imei_tracking ? 'YES' : 'NO') . "\n\n";

        // Enable IMEI tracking for this product
        echo "Enabling IMEI tracking for this product...\n";
        $product->enable_imei_tracking = 1;
        $product->save();
        echo "Done! Product enable_imei_tracking is now: YES\n";
    } else {
        echo "iPhone product not found in this business.\n";
        echo "\nProducts in this business:\n";
        $products = \App\Product::where('business_id', $business->id)->take(10)->get();
        foreach ($products as $p) {
            echo "- ID: " . $p->id . " | " . $p->name . " | IMEI: " . ($p->enable_imei_tracking ? 'YES' : 'NO') . "\n";
        }
    }
} else {
    echo "Bebshader Electronics business not found!\n";

    // List all businesses
    echo "\nAll businesses:\n";
    $businesses = \App\Business::take(10)->get();
    foreach ($businesses as $b) {
        echo "- ID: " . $b->id . " | " . $b->name . " | IMEI: " . ($b->enable_imei_tracking ? 'YES' : 'NO') . "\n";
    }
}
