<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Business ID: 71, Location ID: 86, Invoice Layout ID: 76

// Update the invoice layout to use pacific design
$layout = \App\InvoiceLayout::find(76);

if ($layout) {
    echo "Current Layout Details:\n";
    echo "ID: " . $layout->id . "\n";
    echo "Name: " . $layout->name . "\n";
    echo "Business ID: " . $layout->business_id . "\n";
    echo "Current Design: " . $layout->design . "\n";

    // Update to pacific design
    $layout->design = 'pacific';
    $layout->save();

    echo "\n--- UPDATED ---\n";
    echo "New Design: " . $layout->design . "\n";
    echo "Invoice layout updated to Pacific style successfully!\n";
} else {
    echo "Invoice Layout with ID 76 not found.\n";

    // Create new layout for this business
    echo "\nCreating new Pacific layout for Business ID 71...\n";

    $newLayout = new \App\InvoiceLayout();
    $newLayout->name = 'Pacific Professional';
    $newLayout->business_id = 71;
    $newLayout->design = 'pacific';
    $newLayout->show_business_name = 1;
    $newLayout->show_location_name = 1;
    $newLayout->show_mobile_number = 1;
    $newLayout->show_email = 1;
    $newLayout->show_logo = 1;
    $newLayout->show_customer = 1;
    $newLayout->show_payments = 1;
    $newLayout->invoice_heading = 'BILL';
    $newLayout->invoice_no_prefix = 'Bill No.: ';
    $newLayout->date_label = 'DATE: ';
    $newLayout->table_product_label = 'Description';
    $newLayout->table_qty_label = 'Quantity';
    $newLayout->table_unit_price_label = 'Unit Price Taka';
    $newLayout->table_subtotal_label = 'Amount Taka';
    $newLayout->sub_total_label = 'Sub Total TK.';
    $newLayout->discount_label = 'Special Discount TK.';
    $newLayout->total_label = 'Grand Total TK.';
    $newLayout->is_default = 1;
    $newLayout->save();

    echo "New Layout ID: " . $newLayout->id . "\n";

    // Update business location to use this layout
    \App\BusinessLocation::where('id', 86)->update(['invoice_layout_id' => $newLayout->id]);
    echo "Business Location 86 updated to use new layout.\n";
}
