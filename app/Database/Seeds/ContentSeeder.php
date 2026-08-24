<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/** Blog posts, gallery, testimonials and FAQs, ported from the static pages. */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $now  = date('Y-m-d H:i:s');
        $cats = array_column(
            $this->db->table('post_categories')->select('id, slug')->get()->getResultArray(),
            'id',
            'slug'
        );

        $posts = [
            [
                'When to see the Great Migration', 'when-to-see-the-great-migration', 'safari-tips',
                'The river crossings get the headlines, but the quieter months have their own rewards — here’s how to time it.',
                "The river crossings get the headlines, but the quieter months have their own rewards — here's how to time it.\n\nThe herds are in the Mara from roughly July to October, and the Mara River crossings — the part everyone has seen on television — cluster in August and September. That is also when the Mara is at its busiest and its most expensive.\n\nIf you want the crossings, come in late August. If you want the Mara mostly to yourself, come in June or November: the wildlife is still there, the grass is greener, the light is better for photography and the camps are half the price.\n\nOne thing worth saying plainly: the migration is a natural event, not a scheduled one. The herds move with the rain. Anyone who promises you a river crossing on a specific date is guessing.",
                'https://picsum.photos/seed/sunset-22/1200/675', 'Golden light over the savannah at dusk', 5, '2026-07-04 09:00:00',
            ],
            [
                'What to pack for your first safari', 'what-to-pack-for-your-first-safari', 'packing',
                'Less than you think, layered better than you expect — a packing list that earns its place in your bag.',
                "Less than you think, layered better than you expect — a packing list that earns its place in your bag.\n\nMornings on the plains are genuinely cold; by eleven it is hot. Layers solve this and nothing else does. Bring a fleece or light down jacket, long sleeves in neutral colours, and a hat that will not blow off in an open vehicle.\n\nLeave the bright white and the bright red at home — not because animals care much, but because everything turns the colour of the road within a day. Soft-sided bags travel better than hard cases if any part of your trip involves a light aircraft.\n\nThe one thing people consistently under-pack: binoculars. A single decent pair between two people changes the whole experience.",
                'https://picsum.photos/seed/meadow-13/1200/675', 'Green highland forest in central Kenya', 4, '2026-06-18 09:00:00',
            ],
            [
                'Coast or safari first? Planning both', 'coast-or-safari-first-planning-both', 'planning',
                'Kenya lets you do both in one trip. The order matters more than most people expect.',
                "Kenya lets you do both in one trip — plains and reef in the same fortnight. The order matters more than most people expect.\n\nDo the safari first. Game drives start before dawn and involve a lot of time in a vehicle on rough roads; you want to arrive at that fresh rather than sun-tired. The coast then works as the decompression at the end, which is what a beach is actually good for.\n\nThe practical route is Nairobi, out to the parks, back to Nairobi, then either the SGR train or a short flight down to Mombasa or Malindi. Allow a buffer day between the two halves — safari itineraries run long more often than they run short.",
                'https://picsum.photos/seed/rift-valley/1200/675', 'Palm trees along the Kenyan coast', 6, '2026-06-02 09:00:00',
            ],
            [
                'Is a Kenyan safari safe for families?', 'is-a-kenyan-safari-safe-for-families', 'planning',
                'The honest answer, plus the ages and park choices that work best with children in tow.',
                "The honest answer is yes, with sensible planning — and Kenya is one of the easier places in Africa to do it.\n\nMost camps take children from around six, and some from any age with a private vehicle. Below about five, the long drives are the real obstacle rather than any danger. Amboseli and Nairobi National Park work well with young families because the drives are short and the wildlife is close to the road.\n\nBook a private vehicle if you can. It costs more, but it means you leave when your children need to leave rather than when the group does — and that single change is what turns a hard trip into an easy one.",
                'https://picsum.photos/seed/grove-18/1200/675', 'A safari vehicle on a park track at midday', 4, '2026-05-20 09:00:00',
            ],
            [
                'Eating well on the Kenyan coast', 'eating-well-on-the-kenyan-coast', 'coast',
                'Swahili food is the coast’s quiet headline act. Here is what to order and where.',
                "Swahili food is the coast's quiet headline act, and most visitors miss it entirely by eating at their hotel every night.\n\nStart with biryani and pilau — rice dishes carrying four hundred years of Indian Ocean trade in the spicing. Then the seafood: whole fish grilled with tamarind, crab in coconut, prawns that were in the water that morning. Mahamri for breakfast, which is a cardamom doughnut by any other name.\n\nAsk your guide where they eat rather than where they are supposed to send you. In Mombasa Old Town and in Lamu that question reliably produces the best meal of the trip.",
                'https://picsum.photos/seed/ember-25/1200/675', 'Fresh seafood being grilled on the coast', 5, '2026-05-06 09:00:00',
            ],
            [
                'A first-timer’s guide to park fees', 'a-first-timers-guide-to-park-fees', 'planning',
                'Why the price of a safari moves so much between parks, and what you are actually paying for.',
                "Park fees are the single largest fixed cost in most safari quotes, and they explain most of the price difference between itineraries.\n\nKenya Wildlife Service charges per person per twenty-four hours, and the rate varies by park and by residency. The Maasai Mara is a conservancy rather than a KWS park, and sets its own — higher — rate. This is why a Mara night costs more than an Amboseli night before accommodation is even counted.\n\nThe fees are not a markup. They fund the rangers and the anti-poaching work that are the reason there is anything to see. When we quote you a package, the park fees line is passed through at cost.",
                'https://picsum.photos/seed/bush-15/1200/675', 'A park entry gate in the early morning', 3, '2026-04-22 09:00:00',
            ],
        ];

        $rows = [];
        foreach ($posts as [$title, $slug, $catSlug, $excerpt, $body, $image, $alt, $minutes, $publishedAt]) {
            $rows[] = [
                'title'            => $title,
                'slug'             => $slug,
                'post_category_id' => $cats[$catSlug] ?? null,
                'excerpt'          => $excerpt,
                'body'             => $body,
                'image'            => $image,
                'image_alt'        => $alt,
                'author'           => 'MOROP GAA Tours and Travel',
                'read_minutes'     => $minutes,
                'is_published'     => 1,
                'published_at'     => $publishedAt,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }
        $this->db->table('posts')->insertBatch($rows);

        $gallery = [
            ['grove-18', 'Maasai Mara at golden hour', 'Savannah at golden hour with grazing wildlife'],
            ['dune-5', 'The south coast, Diani', 'Turquoise surf on a white-sand beach'],
            ['cliff-12', 'Grazing herds, Amboseli', 'Herds grazing on open grassland'],
            ['dusk-ridge', 'Lake Nakuru at dusk', 'Lake shoreline at dusk'],
            ['trail-8', 'The Longonot trail', 'Trail winding through a mountain gorge'],
            ['ember-25', 'Bomas of Kenya', 'Wooden walkway through greenery'],
            ['bush-15', 'Rift Valley ridges', 'Layered mountain ridges at dusk'],
            ['meadow-13', 'Naivasha countryside', 'Green highland forest'],
            ['canopy-2', 'Aberdare mists', 'Misty highland forest'],
            ['sunset-22', 'Evening game walk', 'Golden light on a forest path'],
            ['rift-valley', 'Coastal palms', 'Palm trees along the coastline'],
            ['horizon-7', 'Misty morning hills', 'Misty morning over rolling hills'],
        ];

        $rows = [];
        foreach ($gallery as $i => [$seed, $caption, $alt]) {
            $rows[] = [
                'path'       => "https://picsum.photos/seed/{$seed}/800/600",
                'caption'    => $caption,
                'alt'        => $alt,
                'sort_order' => $i + 1,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        $this->db->table('gallery_images')->insertBatch($rows);

        $testimonials = [
            ['Every detail was arranged before we even thought to ask. The Mara felt like it was ours alone.', 'Happy Traveler', 'Nairobi'],
            ['Seamless from first message to final transfer. Our staff retreat ran itself — we just showed up.', 'Corporate Client', 'Mombasa'],
            ['Paying by M-Pesa and having everything confirmed the same day made it completely effortless.', 'Happy Traveler', 'Kisumu'],
            ['We had two small children and were nervous about the drives. They planned around the naps and it just worked.', 'Family of Four', 'Nakuru'],
            ['The guide knew every bird by call. That is the difference between a drive and an education.', 'Repeat Guest', 'London'],
        ];

        $rows = [];
        foreach ($testimonials as $i => [$quote, $name, $location]) {
            $rows[] = [
                'quote'           => $quote,
                'author_name'     => $name,
                'author_location' => $location,
                'rating'          => 5,
                'sort_order'      => $i + 1,
                'is_active'       => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }
        $this->db->table('testimonials')->insertBatch($rows);

        $faqs = [
            ['How do I book a trip?', 'Pick a package and send us an enquiry through the booking form, or message us on WhatsApp. We confirm availability, send you a written itinerary and a quote, and hold your dates while you decide. Nothing is charged until you have accepted the quote in writing.'],
            ['How do I pay, and when?', 'We accept M-Pesa and bank transfer, and card payments on request. A deposit secures your dates and the balance is due before departure — the exact split is set out on your quote. You will always receive a receipt.'],
            ['What is your cancellation policy?', 'Cancellations more than 30 days before departure are refunded in full less any non-recoverable park or lodge deposits. Inside 30 days the refundable portion reduces, and inside 7 days deposits are generally not recoverable. The specific terms for your trip are stated on your quote before you pay anything.'],
            ['Are park fees and meals included?', 'Every package lists exactly what is and is not included — you will find it on the package page under "What\'s included". Park fees are passed through at cost with no markup.'],
            ['Can you customise a package?', 'Yes, and most of our trips are adjusted in some way. Dates, group size, comfort level, the length of the trip — all of it can move. If nothing on the site fits, tell us what you have in mind through the Custom Trips page and we will design it from scratch.'],
            ['Do you arrange transport from the airport?', 'Yes. Airport transfers are included on most multi-day packages and can be added to any trip. Let us know your flight number and we will meet you in arrivals.'],
            ['Is travel insurance included?', 'No — travel insurance is not included in any package and we strongly recommend you arrange your own before travelling. For mountain treks, make sure your policy covers activity at altitude.'],
        ];

        $rows = [];
        foreach ($faqs as $i => [$question, $answer]) {
            $rows[] = [
                'question'   => $question,
                'answer'     => $answer,
                'sort_order' => $i + 1,
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        $this->db->table('faqs')->insertBatch($rows);
    }
}
