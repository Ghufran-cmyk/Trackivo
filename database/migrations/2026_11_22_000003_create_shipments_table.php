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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('carrier_id')->constrained('carriers');
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');
            $table->enum('transport_mode', ['OCEAN', 'AIR', 'LAND']);
            $table->string('cargo_type');
            $table->decimal('weight_kg', 10, 2);
            $table->decimal('volume_cbm', 10, 2)->nullable();
            $table->enum('status', ['CREATED', 'BOOKED', 'PICKED_UP', 'IN_TRANSIT', 'DELIVERED', 'CANCELLED'])->default('CREATED');
            $table->timestamp('expected_departure')->nullable();
            $table->timestamp('expected_arrival')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->integer('delay_hours')->default(0);
            $table->enum('delay_severity', ['NONE', 'LOW', 'MEDIUM', 'HIGH'])->default('NONE');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['status', 'transport_mode']);
            $table->index('tracking_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
