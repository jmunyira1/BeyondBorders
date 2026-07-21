<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * The five categories from the design's tiles, plus the destinations and tour
 * types the filter needs (these were implicit in the static copy).
 */
class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $categories = [
            ['Safaris', 'safari', 'bi-binoculars', 'Game drives and wildlife viewing across Kenya’s great parks.'],
            ['Beach Holidays', 'beach', 'bi-umbrella', 'White sand, warm ocean and dhow sunsets on the Kenyan coast.'],
            ['Adventures', 'adventure', 'bi-signpost-2', 'Hikes, treks and active days out in the Rift Valley and highlands.'],
            ['Cultural Experiences', 'culture', 'bi-calendar-event', 'Heritage, food and community visits around Nairobi and beyond.'],
            ['Corporate Retreats', 'corporate', 'bi-people', 'Team-building getaways and staff retreats, fully facilitated.'],
        ];

        $rows = [];
        foreach ($categories as $i => [$name, $slug, $icon, $description]) {
            $rows[] = compact('name', 'slug', 'icon', 'description') + [
                'sort_order' => $i + 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ];
        }
        $this->db->table('categories')->insertBatch($rows);

        $destinations = [
            ['Maasai Mara', 'maasai-mara', 'Rift Valley'],
            ['Diani Beach', 'diani-beach', 'South Coast'],
            ['Amboseli', 'amboseli', 'Southern Kenya'],
            ['Lake Nakuru', 'lake-nakuru', 'Rift Valley'],
            ['Mt. Longonot', 'mt-longonot', 'Rift Valley'],
            ['Nairobi', 'nairobi', 'Central'],
            ['Lake Naivasha', 'lake-naivasha', 'Rift Valley'],
            ['Tsavo', 'tsavo', 'Southern Kenya'],
            ['Watamu', 'watamu', 'North Coast'],
            ['Mount Kenya', 'mount-kenya', 'Central Highlands'],
        ];

        $rows = [];
        foreach ($destinations as $i => [$name, $slug, $region]) {
            $rows[] = compact('name', 'slug', 'region') + [
                'sort_order' => $i + 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ];
        }
        $this->db->table('destinations')->insertBatch($rows);

        $tourTypes = [
            ['Group Tour', 'group-tour', 'Join a scheduled departure and share the cost with other travelers.'],
            ['Private Tour', 'private-tour', 'Your own vehicle, guide and pace — ideal for families and couples.'],
            ['Day Trip', 'day-trip', 'Out and back in a single day, departing from Nairobi.'],
            ['Multi-Day Package', 'multi-day', 'Two nights or more, with accommodation and meals included.'],
            ['Luxury', 'luxury', 'Premium lodges, private transfers and elevated service throughout.'],
            ['Budget', 'budget', 'Great value trips with comfortable, no-frills accommodation.'],
        ];

        $rows = [];
        foreach ($tourTypes as $i => [$name, $slug, $description]) {
            $rows[] = compact('name', 'slug', 'description') + [
                'sort_order' => $i + 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ];
        }
        $this->db->table('tour_types')->insertBatch($rows);

        $postCategories = [
            ['Safari Tips', 'safari-tips'],
            ['Packing', 'packing'],
            ['Planning', 'planning'],
            ['Coast', 'coast'],
            ['Culture', 'culture-journal'],
        ];

        $rows = [];
        foreach ($postCategories as $i => [$name, $slug]) {
            $rows[] = compact('name', 'slug') + [
                'sort_order' => $i + 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now,
            ];
        }
        $this->db->table('post_categories')->insertBatch($rows);
    }
}
