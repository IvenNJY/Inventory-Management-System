<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets_management', function (Blueprint $table) {
            $table->id();
            $table->mediumText('asset_name');
            $table->mediumText('type');
            $table->mediumText('serial_number');
            $table->mediumText('model');
            $table->mediumText('asset_tag');
            $table->date('purchase_date');
            $table->date('warranty_end_date');
            $table->date('expected_lifespan');
            $table->mediumText('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_management');
    }
};
