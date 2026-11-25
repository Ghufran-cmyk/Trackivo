<?php

use App\Services\ShippingRateService;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('tracking_number');
            $table->unsignedBigInteger('base_cost')->default(0)->after('volume_cbm');
            $table->unsignedBigInteger('weight_volume_cost')->default(0)->after('base_cost');
            $table->unsignedBigInteger('insurance_cost')->default(0)->after('weight_volume_cost');
            $table->unsignedBigInteger('total_amount')->default(0)->after('insurance_cost');
            $table->string('payment_method')->default('BANK_TRANSFER')->after('total_amount');
            $table->string('payment_status')->default('UNPAID')->after('payment_method');
            $table->timestamp('paid_at')->nullable()->after('payment_status');
            $table->foreignId('payment_confirmed_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
        });

        // Backfill aman untuk seluruh baris shipments yang sudah ada
        $existingShipments = DB::table('shipments')
            ->whereNull('invoice_number')
            ->orderBy('id', 'asc')
            ->get();

        $count = $existingShipments->count();
        $adminId = DB::table('users')->where('role', 'admin')->value('id')
            ?? DB::table('users')->orderBy('id')->value('id');

        $methods = ['BANK_TRANSFER', 'CORPORATE_INVOICE', 'QRIS', 'CASH'];

        foreach ($existingShipments as $index => $shipment) {
            $invSequence = str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT);
            $year = $shipment->created_at ? Carbon::parse($shipment->created_at)->format('Y') : date('Y');
            $invoiceNumber = "INV-{$year}-{$invSequence}";

            $weight = (float) ($shipment->weight_kg ?? 0);
            $volume = (float) ($shipment->volume_cbm ?? 0);
            $mode = $shipment->transport_mode ?? 'LAND';

            $insuranceCost = ($index % 2 === 0) ? 150000 : 0;
            $costs = ShippingRateService::calculate($mode, $weight, $volume, $insuranceCost);

            // Sisakan minimal 1 pengiriman aktif berstatus UNPAID agar tombol "Konfirmasi Lunas" bisa didemokan
            // Pengiriman terakhir (atau yang belum DELIVERED) bisa dijadikan UNPAID
            $isLast = ($index === $count - 1);
            $paymentStatus = $isLast ? 'UNPAID' : 'PAID';
            $method = $methods[$index % count($methods)];
            $paidAt = $paymentStatus === 'PAID'
                ? ($shipment->created_at ? Carbon::parse($shipment->created_at)->addMinutes(30) : Carbon::now())
                : null;
            $confirmedBy = $paymentStatus === 'PAID' ? $adminId : null;

            DB::table('shipments')
                ->where('id', $shipment->id)
                ->update([
                    'invoice_number' => $invoiceNumber,
                    'base_cost' => $costs['base_cost'],
                    'weight_volume_cost' => $costs['weight_volume_cost'],
                    'insurance_cost' => $costs['insurance_cost'],
                    'total_amount' => $costs['total_amount'],
                    'payment_method' => $method,
                    'payment_status' => $paymentStatus,
                    'paid_at' => $paidAt,
                    'payment_confirmed_by' => $confirmedBy,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropForeign(['payment_confirmed_by']);
            $table->dropColumn([
                'invoice_number',
                'base_cost',
                'weight_volume_cost',
                'insurance_cost',
                'total_amount',
                'payment_method',
                'payment_status',
                'paid_at',
                'payment_confirmed_by',
            ]);
        });
    }
};
