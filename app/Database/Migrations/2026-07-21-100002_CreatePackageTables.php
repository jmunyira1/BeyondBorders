<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Packages and their child rows. `price` is the "From" figure shown on the card
 * and is what the price-range filter sorts on; `duration_days` backs the
 * duration filter.
 */
class CreatePackageTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 180],
            'slug'            => ['type' => 'VARCHAR', 'constraint' => 200],
            'category_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'destination_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tour_type_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'summary'         => ['type' => 'TEXT', 'null' => true],
            'description'     => ['type' => 'TEXT', 'null' => true],
            'image'           => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'image_alt'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'duration_days'   => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'default' => 1],
            'duration_nights' => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'default' => 0],
            'duration_label'  => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'price'           => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'currency'        => ['type' => 'VARCHAR', 'constraint' => 8, 'default' => 'KES'],
            'group_size'      => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => true],
            'is_featured'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'is_active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['is_active', 'is_featured']);
        $this->forge->addKey('price');
        $this->forge->addForeignKey('category_id', 'categories', 'id', '', 'SET NULL');
        $this->forge->addForeignKey('destination_id', 'destinations', 'id', '', 'SET NULL');
        $this->forge->addForeignKey('tour_type_id', 'tour_types', 'id', '', 'SET NULL');
        $this->forge->createTable('packages');

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'package_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'path'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'alt'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('package_id', 'packages', 'id', '', 'CASCADE');
        $this->forge->createTable('package_images');

        // `is_included` false renders the item under "Not included" on the detail page.
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'package_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'item'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_included' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'sort_order'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('package_id', 'packages', 'id', '', 'CASCADE');
        $this->forge->createTable('package_inclusions');
    }

    public function down(): void
    {
        $this->forge->dropTable('package_inclusions', true);
        $this->forge->dropTable('package_images', true);
        $this->forge->dropTable('packages', true);
    }
}
