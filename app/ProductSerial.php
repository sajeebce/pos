<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_serials';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'datetime',
        'sold_date' => 'datetime',
    ];

    /**
     * Get the business that owns the serial.
     */
    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    /**
     * Get the product that owns the serial.
     */
    public function product()
    {
        return $this->belongsTo(\App\Product::class);
    }

    /**
     * Get the variation that owns the serial.
     */
    public function variation()
    {
        return $this->belongsTo(\App\Variation::class);
    }

    /**
     * Get the location where the serial is stored.
     */
    public function location()
    {
        return $this->belongsTo(\App\BusinessLocation::class, 'location_id');
    }

    /**
     * Get the purchase line for this serial.
     */
    public function purchaseLine()
    {
        return $this->belongsTo(\App\PurchaseLine::class, 'purchase_line_id');
    }

    /**
     * Get the sell line for this serial.
     */
    public function sellLine()
    {
        return $this->belongsTo(\App\TransactionSellLine::class, 'sell_line_id');
    }

    /**
     * Scope a query to only include available serials.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include sold serials.
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    /**
     * Scope a query to filter by business.
     */
    public function scopeForBusiness($query, $business_id)
    {
        return $query->where('business_id', $business_id);
    }

    /**
     * Scope a query to filter by product.
     */
    public function scopeForProduct($query, $product_id)
    {
        return $query->where('product_id', $product_id);
    }

    /**
     * Scope a query to filter by variation.
     */
    public function scopeForVariation($query, $variation_id)
    {
        return $query->where('variation_id', $variation_id);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeForLocation($query, $location_id)
    {
        return $query->where('location_id', $location_id);
    }

    /**
     * Check if a serial number exists for a business.
     */
    public static function serialExists($business_id, $serial_number, $exclude_id = null)
    {
        $query = self::where('business_id', $business_id)
                     ->where('serial_number', $serial_number);

        if ($exclude_id) {
            $query->where('id', '!=', $exclude_id);
        }

        return $query->exists();
    }

    /**
     * Get available serials for a product variation at a location.
     */
    public static function getAvailableSerials($business_id, $product_id, $variation_id, $location_id)
    {
        return self::where('business_id', $business_id)
                   ->where('product_id', $product_id)
                   ->where('variation_id', $variation_id)
                   ->where('location_id', $location_id)
                   ->where('status', 'available')
                   ->orderBy('purchase_date', 'asc')
                   ->get();
    }
}
