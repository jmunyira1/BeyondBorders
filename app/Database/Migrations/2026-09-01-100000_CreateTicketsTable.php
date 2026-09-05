<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Paid tickets for scheduled adventures/events (e.g. "Morop Hill Adventure").
 *
 * A ticket snapshots its display data (name, date, location, pickup, inclusions,
 * amount) at purchase time so it stays correct even if the package changes, and
 * carries the payment state (KCB) plus a secure token for its public URL.
 */
class CreateTicketsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'ticket_ref'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'token'            => ['type' => 'VARCHAR', 'constraint' => 64],
            'package_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'departure_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],

            // Snapshot of what the ticket shows
            'adventure_name'   => ['type' => 'VARCHAR', 'constraint' => 180],
            'event_date'       => ['type' => 'DATE', 'null' => true],
            'event_location'   => ['type' => 'VARCHAR', 'constraint' => 180, 'null' => true],
            'ticket_type'      => ['type' => 'VARCHAR', 'constraint' => 80, 'default' => 'General Admission'],
            'pickup_point'     => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'pickup_time'      => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'includes'         => ['type' => 'TEXT', 'null' => true], // JSON: [{"icon":"bi-...","label":"..."}]
            'image'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            // Buyer
            'guest_name'       => ['type' => 'VARCHAR', 'constraint' => 120],
            'guest_email'      => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true],
            'guest_phone'      => ['type' => 'VARCHAR', 'constraint' => 40, 'null' => true],
            'quantity'         => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'default' => 1],

            // Money + payment
            'amount'           => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'currency'         => ['type' => 'VARCHAR', 'constraint' => 8, 'default' => 'KES'],
            'payment_status'   => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'], // pending|paid|failed|cancelled
            'payment_provider' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'payment_ref'      => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'paid_at'          => ['type' => 'DATETIME', 'null' => true],

            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('ticket_ref');
        $this->forge->addUniqueKey('token');
        $this->forge->addKey('payment_status');
        $this->forge->createTable('tickets');
    }

    public function down(): void
    {
        $this->forge->dropTable('tickets');
    }
}
