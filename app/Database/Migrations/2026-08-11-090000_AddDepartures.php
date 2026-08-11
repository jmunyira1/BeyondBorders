<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Scheduled departure dates for a package, each with its own spots, plus a link
 * from an enquiry to the departure it was for (so "mark as booked" can decrement
 * the right departure's spots). All additive / nullable — safe on a live table.
 */
class AddDepartures extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'package_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'depart_date' => ['type' => 'DATE'],
            'return_date' => ['type' => 'DATE', 'null' => true],
            'spots'       => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'null' => true],
            'note'        => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'sort_order'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['package_id', 'depart_date']);
        $this->forge->addForeignKey('package_id', 'packages', 'id', '', 'CASCADE');
        $this->forge->createTable('package_departures');

        $this->forge->addColumn('enquiries', [
            'departure_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'package_id'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('enquiries', 'departure_id');
        $this->forge->dropTable('package_departures', true);
    }
}
