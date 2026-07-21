<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * One table for all three inbound forms — the contact page, the custom-trip
 * planner and the per-package booking enquiry. `type` says which form it came
 * from; the trip fields are only populated by the latter two.
 */
class CreateEnquiriesTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'type'         => ['type' => 'ENUM', 'constraint' => ['contact', 'custom_trip', 'booking'], 'default' => 'contact'],
            'package_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 150],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'phone'        => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'subject'      => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'message'      => ['type' => 'TEXT', 'null' => true],
            'trip_type'    => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'people'       => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'null' => true],
            'travel_dates' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'budget'       => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'status'       => ['type' => 'ENUM', 'constraint' => ['new', 'read', 'replied', 'closed'], 'default' => 'new'],
            'admin_notes'  => ['type' => 'TEXT', 'null' => true],
            'ip_address'   => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['type', 'status']);
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('package_id', 'packages', 'id', '', 'SET NULL');
        $this->forge->createTable('enquiries');
    }

    public function down(): void
    {
        $this->forge->dropTable('enquiries', true);
    }
}
