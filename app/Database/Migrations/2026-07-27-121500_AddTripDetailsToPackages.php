<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Extra location + lodging detail for a trip. All nullable and additive, so it
 * is safe to run against a live packages table with existing rows.
 *
 * Destination and Category already exist as taxonomies (destination_id /
 * category_id); Region and County are free-text here.
 */
class AddTripDetailsToPackages extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('packages', [
            'region'         => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true, 'after' => 'group_size'],
            'county'         => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true, 'after' => 'region'],
            'entrance_fee'   => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true, 'after' => 'county'],
            'nearby_hotel'   => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true, 'after' => 'entrance_fee'],
            'hotel_rate'     => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true, 'after' => 'nearby_hotel'],
            'nearby_cottage' => ['type' => 'VARCHAR', 'constraint' => 160, 'null' => true, 'after' => 'hotel_rate'],
            'cottage_rate'   => ['type' => 'DECIMAL', 'constraint' => '12,2', 'null' => true, 'after' => 'nearby_cottage'],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('packages', [
            'region', 'county', 'entrance_fee',
            'nearby_hotel', 'hotel_rate', 'nearby_cottage', 'cottage_rate',
        ]);
    }
}
