<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_serials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('business_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('variation_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->string('serial_number', 100);
            $table->integer('purchase_line_id')->unsigned()->nullable();
            $table->integer('sell_line_id')->unsigned()->nullable();
            $table->enum('status', ['available', 'sold', 'returned', 'damaged'])->default('available');
            $table->datetime('purchase_date')->nullable();
            $table->datetime('sold_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('business_id');
            $table->index('product_id');
            $table->index('variation_id');
            $table->index('location_id');
            $table->index('serial_number');
            $table->index('status');
            $table->index('purchase_line_id');
            $table->index('sell_line_id');

            // Unique constraint: same serial number cannot exist twice in same business
            $table->unique(['business_id', 'serial_number'], 'unique_serial_per_business');

            // Foreign keys
            $table->foreign('business_id')->references('id')->on('business')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('variation_id')->references('id')->on('variations')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('business_locations')->onDelete('cascade');
            $table->foreign('purchase_line_id')->references('id')->on('purchase_lines')->onDelete('set null');
            $table->foreign('sell_line_id')->references('id')->on('transaction_sell_lines')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_serials');
    }
};
