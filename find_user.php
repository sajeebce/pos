<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\User::where('username', 'nomanlmu2030')
    ->orWhere('email', 'nomanlmu@gmail.com')
    ->first();

if ($user) {
    echo "User Found!\n";
    echo "User ID: " . $user->id . "\n";
    echo "Business ID: " . $user->business_id . "\n";
    echo "Username: " . $user->username . "\n";
    echo "Email: " . $user->email . "\n";

    // Get business details
    $business = \App\Business::find($user->business_id);
    if ($business) {
        echo "\nBusiness Details:\n";
        echo "Business Name: " . $business->name . "\n";
        echo "Business ID: " . $business->id . "\n";
    }

    // Get business locations
    $locations = \App\BusinessLocation::where('business_id', $user->business_id)->get();
    echo "\nLocations:\n";
    foreach ($locations as $loc) {
        echo "- Location ID: " . $loc->id . ", Name: " . $loc->name . ", Invoice Layout ID: " . $loc->invoice_layout_id . "\n";
    }
} else {
    echo "User not found\n";
}
