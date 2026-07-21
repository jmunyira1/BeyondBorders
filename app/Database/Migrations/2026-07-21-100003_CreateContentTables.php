<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * The editorial content that was hard-coded in the static design: blog posts,
 * the gallery grid, homepage testimonials and the contact-page FAQ accordion.
 */
class CreateContentTables extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'            => ['type' => 'VARCHAR', 'constraint' => 200],
            'slug'             => ['type' => 'VARCHAR', 'constraint' => 220],
            'post_category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'excerpt'          => ['type' => 'TEXT', 'null' => true],
            'body'             => ['type' => 'LONGTEXT', 'null' => true],
            'image'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'image_alt'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'author'           => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'read_minutes'     => ['type' => 'SMALLINT', 'constraint' => 5, 'unsigned' => true, 'default' => 3],
            'is_published'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'published_at'     => ['type' => 'DATETIME', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey(['is_published', 'published_at']);
        $this->forge->addForeignKey('post_category_id', 'post_categories', 'id', '', 'SET NULL');
        $this->forge->createTable('posts');

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'path'       => ['type' => 'VARCHAR', 'constraint' => 255],
            'caption'    => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => true],
            'alt'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('gallery_images');

        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'quote'           => ['type' => 'TEXT'],
            'author_name'     => ['type' => 'VARCHAR', 'constraint' => 120],
            'author_location' => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'rating'          => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 5],
            'sort_order'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('testimonials');

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'question'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'answer'     => ['type' => 'TEXT'],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'is_active'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('faqs');
    }

    public function down(): void
    {
        $this->forge->dropTable('faqs', true);
        $this->forge->dropTable('testimonials', true);
        $this->forge->dropTable('gallery_images', true);
        $this->forge->dropTable('posts', true);
    }
}
