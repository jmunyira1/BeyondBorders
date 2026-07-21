<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * The seven packages from the static design, plus five more so every price and
 * duration band in the filter has something to return. Images keep the original
 * picsum placeholders until real photos are uploaded through the admin.
 */
class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $ids = static function (Seeder $seeder, string $table): array {
            $rows = $seeder->db->table($table)->select('id, slug')->get()->getResultArray();

            return array_column($rows, 'id', 'slug');
        };

        $cat  = $ids($this, 'categories');
        $dest = $ids($this, 'destinations');
        $type = $ids($this, 'tour_types');

        $packages = [
            [
                'title'   => 'Maasai Mara Safari',
                'slug'    => 'maasai-mara-safari',
                'cat'     => 'safari',
                'dest'    => 'maasai-mara',
                'type'    => 'multi-day',
                'summary' => 'Game drives across the Mara plains in search of the Big Five, with nights at a tented camp.',
                'description' => "Game drives across the Mara plains in search of the Big Five, with sundowners over the savannah and nights at a tented camp. Transport, park fees, meals and guide included.\n\nThe Mara needs no introduction — rolling grassland, big skies and the densest concentration of predators in Kenya. We time drives for the early morning and late afternoon when the light is best and the cats are moving, and keep the group small so everyone gets a window seat.",
                'image'   => 'https://picsum.photos/seed/grove-18/900/600',
                'alt'     => 'Golden savannah grasslands of the Maasai Mara',
                'days'    => 3, 'nights' => 2, 'price' => 25000, 'featured' => 1,
                'group'   => '2 – 7 travelers',
                'inclusions' => [
                    ['Return transport from Nairobi', 1],
                    ['Park entry fees', 1],
                    ['Full board accommodation at a tented camp', 1],
                    ['Professional driver-guide', 1],
                    ['Game drives as per itinerary', 1],
                    ['Bottled water in the vehicle', 1],
                    ['International flights', 0],
                    ['Travel insurance', 0],
                    ['Personal spending and tips', 0],
                ],
            ],
            [
                'title'   => 'Diani Beach Getaway',
                'slug'    => 'diani-beach-getaway',
                'cat'     => 'beach',
                'dest'    => 'diani-beach',
                'type'    => 'multi-day',
                'summary' => "Four days on Kenya's award-winning south coast — white sand, warm ocean and dhow sunsets.",
                'description' => "Four days on Kenya's award-winning south coast — white sand, warm ocean, dhow sunsets and fresh seafood. Flights or SGR transfers arranged on request.\n\nDiani is the easy one: twenty-five kilometres of powder-white sand, a reef that keeps the water calm and warm, and enough to do that nobody gets restless. We build in a dhow sunset cruise and leave the rest of the days open.",
                'image'   => 'https://picsum.photos/seed/dune-5/900/600',
                'alt'     => 'White sand and turquoise water at Diani Beach',
                'days'    => 4, 'nights' => 3, 'price' => 32000, 'featured' => 1,
                'group'   => '2 – 10 travelers',
                'inclusions' => [
                    ['Beachfront accommodation, bed and breakfast', 1],
                    ['Airport or SGR station transfers', 1],
                    ['Sunset dhow cruise', 1],
                    ['Welcome drink on arrival', 1],
                    ['Flights or SGR tickets', 0],
                    ['Lunches and dinners', 0],
                    ['Water sports and excursions', 0],
                ],
            ],
            [
                'title'   => 'Amboseli Elephant Experience',
                'slug'    => 'amboseli-elephant-experience',
                'cat'     => 'safari',
                'dest'    => 'amboseli',
                'type'    => 'multi-day',
                'summary' => "Amboseli's famous elephant herds with Mount Kilimanjaro as the backdrop.",
                'description' => "Amboseli's famous elephant herds with Mount Kilimanjaro as the backdrop — a short safari with unforgettable views. Transport, park fees and lodge stay included.\n\nOn a clear morning Kilimanjaro fills the southern horizon and the elephants walk right across it. Amboseli's herds are among the most studied in Africa and unusually relaxed around vehicles, which makes for remarkable photography.",
                'image'   => 'https://picsum.photos/seed/cliff-12/900/600',
                'alt'     => 'Elephant herds grazing in Amboseli National Park',
                'days'    => 2, 'nights' => 1, 'price' => 18500, 'featured' => 1,
                'group'   => '2 – 7 travelers',
                'inclusions' => [
                    ['Return transport from Nairobi', 1],
                    ['Park entry fees', 1],
                    ['Full board lodge accommodation', 1],
                    ['Driver-guide and game drives', 1],
                    ['Travel insurance', 0],
                    ['Personal spending and tips', 0],
                ],
            ],
            [
                'title'   => 'Mara + Lake Nakuru Combo',
                'slug'    => 'mara-lake-nakuru-combo',
                'cat'     => 'safari',
                'dest'    => 'lake-nakuru',
                'type'    => 'multi-day',
                'summary' => "Nakuru's flamingos and rhino sanctuary, then the open plains of the Mara.",
                'description' => "Two celebrated parks in one journey — Nakuru's flamingos and rhino sanctuary, then the open plains of the Mara. Full board with all transfers included.\n\nNakuru first, for the rhino and the birdlife along the shoreline, then west into the Mara for three nights of proper game viewing. It's the classic Kenyan circuit and still the best introduction to the country's parks.",
                'image'   => 'https://picsum.photos/seed/dusk-ridge/900/600',
                'alt'     => 'Lake Nakuru shoreline at dusk',
                'days'    => 5, 'nights' => 4, 'price' => 55000, 'featured' => 1,
                'group'   => '2 – 7 travelers',
                'inclusions' => [
                    ['All ground transport in a safari vehicle', 1],
                    ['Park entry fees for both parks', 1],
                    ['Full board accommodation', 1],
                    ['Professional driver-guide throughout', 1],
                    ['Bottled water in the vehicle', 1],
                    ['Balloon safari (bookable separately)', 0],
                    ['Travel insurance', 0],
                ],
            ],
            [
                'title'   => 'Mt. Longonot Day Hike',
                'slug'    => 'mt-longonot-day-hike',
                'cat'     => 'adventure',
                'dest'    => 'mt-longonot',
                'type'    => 'day-trip',
                'summary' => 'A guided crater-rim hike above the Great Rift Valley — back in Nairobi by dusk.',
                'description' => "A guided crater-rim hike above the Great Rift Valley — transport from Nairobi, park fees and a guide included. Perfect for groups and first-time hikers.\n\nAbout six hours on foot, with the option to stop at the crater rim rather than complete the full loop. The views over Naivasha and the valley floor are worth every step.",
                'image'   => 'https://picsum.photos/seed/trail-8/900/600',
                'alt'     => 'Hiking trail on the rim of Mt. Longonot crater',
                'days'    => 1, 'nights' => 0, 'price' => 4500, 'featured' => 1,
                'group'   => '4 – 20 hikers',
                'inclusions' => [
                    ['Return transport from Nairobi', 1],
                    ['Park entry fees', 1],
                    ['Experienced hiking guide', 1],
                    ['Bottled water', 1],
                    ['Meals', 0],
                    ['Hiking gear', 0],
                ],
            ],
            [
                'title'   => 'Bomas & Giraffe Centre Tour',
                'slug'    => 'bomas-giraffe-centre-tour',
                'cat'     => 'culture',
                'dest'    => 'nairobi',
                'type'    => 'day-trip',
                'summary' => 'Traditional dance at Bomas of Kenya, then eye-to-eye with Rothschild giraffes.',
                'description' => "Traditional dance and homesteads at Bomas of Kenya, then eye-to-eye with Rothschild giraffes — Nairobi's heritage in a day, entry fees and transport included.\n\nA good first day in Kenya, or a good last one. Bomas runs its dance programme in the afternoon; we go to the Giraffe Centre first thing while the animals are most active.",
                'image'   => 'https://picsum.photos/seed/ember-25/900/600',
                'alt'     => 'Walkway at the Bomas of Kenya cultural centre',
                'days'    => 1, 'nights' => 0, 'price' => 6000, 'featured' => 1,
                'group'   => '2 – 15 travelers',
                'inclusions' => [
                    ['Transport within Nairobi', 1],
                    ['Entry fees to both sites', 1],
                    ['Guide', 1],
                    ['Lunch', 0],
                    ['Personal spending', 0],
                ],
            ],
            [
                'title'   => 'Naivasha Team Retreat',
                'slug'    => 'naivasha-team-retreat',
                'cat'     => 'corporate',
                'dest'    => 'lake-naivasha',
                'type'    => 'private-tour',
                'summary' => 'Team-building activities, a Lake Naivasha boat ride and an evening bonfire.',
                'description' => "Team-building activities, a Lake Naivasha boat ride and an evening bonfire — a reset your whole office will talk about. Facilitation available on request.\n\nWe handle the logistics end to end so nobody from your team has to project-manage their own retreat. Tell us the headcount and the outcome you want from the day and we'll shape the programme around it.",
                'image'   => 'https://picsum.photos/seed/meadow-13/900/600',
                'alt'     => 'Green countryside around Lake Naivasha',
                'days'    => 2, 'nights' => 1, 'price' => 15000, 'featured' => 0,
                'group'   => '10 – 60 staff',
                'inclusions' => [
                    ['Return transport from Nairobi', 1],
                    ['Full board accommodation', 1],
                    ['Boat ride on Lake Naivasha', 1],
                    ['Team-building activities', 1],
                    ['Evening bonfire', 1],
                    ['Professional facilitation', 0],
                    ['Conference room hire', 0],
                ],
            ],
            [
                'title'   => 'Tsavo East & West Explorer',
                'slug'    => 'tsavo-east-west-explorer',
                'cat'     => 'safari',
                'dest'    => 'tsavo',
                'type'    => 'multi-day',
                'summary' => "Kenya's largest wilderness — red elephants, Mzima Springs and vast open country.",
                'description' => "Six days across both halves of Tsavo, Kenya's largest protected area. Red-dusted elephants in the east, the crystal pools of Mzima Springs and the Shetani lava flows in the west.\n\nTsavo is where you go when you want space. It's less trafficked than the Mara and feels genuinely wild — long drives between sightings, but the sightings are yours alone.",
                'image'   => 'https://picsum.photos/seed/bush-15/900/600',
                'alt'     => 'Red-dusted elephants in Tsavo',
                'days'    => 6, 'nights' => 5, 'price' => 78000, 'featured' => 0,
                'group'   => '2 – 7 travelers',
                'inclusions' => [
                    ['All ground transport', 1],
                    ['Park entry fees for both parks', 1],
                    ['Full board lodge accommodation', 1],
                    ['Driver-guide throughout', 1],
                    ['Flights', 0],
                    ['Travel insurance', 0],
                ],
            ],
            [
                'title'   => 'Watamu Marine Escape',
                'slug'    => 'watamu-marine-escape',
                'cat'     => 'beach',
                'dest'    => 'watamu',
                'type'    => 'luxury',
                'summary' => 'Snorkelling the marine park, Mida Creek at sunset and three nights on the north coast.',
                'description' => "Watamu's marine national park is the best snorkelling in Kenya — coral gardens in shallow, clear water a short boat ride from the beach. Add a sunset paddle through the mangroves at Mida Creek and three nights somewhere quiet.\n\nSuited to couples and small groups who want the coast without the crowds of the south.",
                'image'   => 'https://picsum.photos/seed/canopy-2/900/600',
                'alt'     => 'Coral coastline and clear water at Watamu',
                'days'    => 4, 'nights' => 3, 'price' => 48000, 'featured' => 0,
                'group'   => '2 – 8 travelers',
                'inclusions' => [
                    ['Beachfront accommodation, half board', 1],
                    ['Marine park snorkelling trip and fees', 1],
                    ['Mida Creek sunset excursion', 1],
                    ['Airport transfers', 1],
                    ['Flights', 0],
                    ['Diving', 0],
                ],
            ],
            [
                'title'   => 'Mount Kenya Sirimon Trek',
                'slug'    => 'mount-kenya-sirimon-trek',
                'cat'     => 'adventure',
                'dest'    => 'mount-kenya',
                'type'    => 'group-tour',
                'summary' => "Four days to Point Lenana on Africa's second-highest mountain, via the Sirimon route.",
                'description' => "The Sirimon route is the driest and gentlest approach to Point Lenana at 4,985m — four days up through bamboo forest, moorland and the giant lobelia zone, with a pre-dawn summit push on day three.\n\nNo technical climbing required, but you need to be fit and willing to walk at altitude. We keep the ascent slow to give everyone the best chance of making the top.",
                'image'   => 'https://picsum.photos/seed/horizon-7/900/600',
                'alt'     => 'Alpine moorland on the slopes of Mount Kenya',
                'days'    => 4, 'nights' => 3, 'price' => 62000, 'featured' => 0,
                'group'   => '2 – 12 trekkers',
                'inclusions' => [
                    ['Park fees and camping fees', 1],
                    ['Mountain guide, cook and porters', 1],
                    ['All meals on the mountain', 1],
                    ['Return transport from Nairobi', 1],
                    ['Sleeping bag and personal gear', 0],
                    ['Travel insurance with altitude cover', 0],
                ],
            ],
            [
                'title'   => 'Nairobi National Park Morning Drive',
                'slug'    => 'nairobi-national-park-morning-drive',
                'cat'     => 'safari',
                'dest'    => 'nairobi',
                'type'    => 'day-trip',
                'summary' => 'A half-day game drive with the city skyline behind the rhinos — back by lunchtime.',
                'description' => "The only national park in the world bordering a capital city. Black rhino, lion, giraffe and buffalo, all within sight of the Nairobi skyline, on a drive that has you back in town by early afternoon.\n\nIdeal for a long layover or a first morning in Kenya.",
                'image'   => 'https://picsum.photos/seed/sunset-22/900/600',
                'alt'     => 'Rhino grazing with the Nairobi skyline behind',
                'days'    => 1, 'nights' => 0, 'price' => 8500, 'featured' => 0,
                'group'   => '2 – 6 travelers',
                'inclusions' => [
                    ['Park entry fees', 1],
                    ['Safari vehicle and driver-guide', 1],
                    ['Hotel pick-up and drop-off in Nairobi', 1],
                    ['Meals', 0],
                ],
            ],
            [
                'title'   => 'Lake Nakuru Day Safari',
                'slug'    => 'lake-nakuru-day-safari',
                'cat'     => 'safari',
                'dest'    => 'lake-nakuru',
                'type'    => 'budget',
                'summary' => 'Flamingos, rhino and Baboon Cliff in a single long day out of Nairobi.',
                'description' => "A full day at Lake Nakuru — the rhino sanctuary, the flamingo flocks along the shoreline and the view from Baboon Cliff. An early start from Nairobi and back the same evening.\n\nOur most affordable way to see rhino in the wild.",
                'image'   => 'https://picsum.photos/seed/rift-valley/900/600',
                'alt'     => 'Flamingos along the shore of Lake Nakuru',
                'days'    => 1, 'nights' => 0, 'price' => 9500, 'featured' => 0,
                'group'   => '4 – 12 travelers',
                'inclusions' => [
                    ['Return transport from Nairobi', 1],
                    ['Park entry fees', 1],
                    ['Driver-guide', 1],
                    ['Packed lunch', 1],
                    ['Accommodation', 0],
                ],
            ],
        ];

        foreach ($packages as $i => $p) {
            $this->db->table('packages')->insert([
                'title'           => $p['title'],
                'slug'            => $p['slug'],
                'category_id'     => $cat[$p['cat']] ?? null,
                'destination_id'  => $dest[$p['dest']] ?? null,
                'tour_type_id'    => $type[$p['type']] ?? null,
                'summary'         => $p['summary'],
                'description'     => $p['description'],
                'image'           => $p['image'],
                'image_alt'       => $p['alt'],
                'duration_days'   => $p['days'],
                'duration_nights' => $p['nights'],
                'duration_label'  => $this->durationLabel($p['days'], $p['nights']),
                'price'           => $p['price'],
                'currency'        => 'KES',
                'group_size'      => $p['group'],
                'is_featured'     => $p['featured'],
                'is_active'       => 1,
                'sort_order'      => $i + 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            $packageId = $this->db->insertID();

            $rows = [];
            foreach ($p['inclusions'] as $j => [$item, $included]) {
                $rows[] = [
                    'package_id'  => $packageId,
                    'item'        => $item,
                    'is_included' => $included,
                    'sort_order'  => $j + 1,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
            $this->db->table('package_inclusions')->insertBatch($rows);
        }
    }

    /** "3 Days · 2 Nights" — or just "1 Day" for day trips. */
    private function durationLabel(int $days, int $nights): string
    {
        $label = $days . ' ' . ($days === 1 ? 'Day' : 'Days');

        if ($nights > 0) {
            $label .= ' · ' . $nights . ' ' . ($nights === 1 ? 'Night' : 'Nights');
        }

        return $label;
    }
}
