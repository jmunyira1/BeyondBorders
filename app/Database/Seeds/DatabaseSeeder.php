<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Runs every seeder in dependency order.
 *
 *   php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TaxonomySeeder::class);
        $this->call(AdminUserSeeder::class);
    }
}
