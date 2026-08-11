<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Availability + booking deposit for a package.
 *
 * - availability: manual status the admin sets (bookings are enquiries, so
 *   spots don't auto-decrement — the admin manages this as bookings confirm).
 * - spots_available: optional "X spots left" figure.
 * - deposit_amount: the minimum payment (KES) that secures a spot. Payment
 *   itself is handled off-site for now (bank transfer; M-Pesa till to come).
 *
 * All nullable / defaulted, so it is safe on a live table with existing rows.
 */
class AddAvailabilityAndDepositToPackages extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('packages', [
            'availability'    => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'available', 'after' => 'cottage_rate'],
            'spots_available' => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'null' => true, 'after' => 'availability'],
            'deposit_amount'  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true, 'after' => 'spots_available'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('packages', ['availability', 'spots_available', 'deposit_amount']);
    }
}
