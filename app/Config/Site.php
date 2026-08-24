<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Editable site-wide content and contact details.
 *
 * These are the defaults. Anything saved through Admin → Settings is written to
 * the `settings` table by the codeigniter4/settings library and transparently
 * overrides the value here, so read them with setting('Site.phone') rather than
 * touching this class directly.
 */
class Site extends BaseConfig
{
    // --- Identity -----------------------------------------------------------
    public string $companyName = 'MOROP GAA Tours and Travel';
    /**
     * Nav logo. Either a path relative to public/ (what Admin → Settings
     * uploads produce) or an absolute URL. Blank falls back to the bundled
     * assets/img/logo-nav.png.
     */
    public string $logo        = '';
    /** The small line under the name in the header logo, e.g. "Adventures". */
    public string $wordmarkSubtitle = '';
    public string $tagline     = 'Explore and Enjoy';
    public string $strapline   = "We don't just do trips — we make memories that translate to stories worth telling.";

    // --- Contact ------------------------------------------------------------
    public string $phone          = '+254 700 000 000';
    public string $phoneLink      = '+254700000000';
    public string $email          = 'info@moropgaa.com';
    public string $address        = 'Nairobi, Kenya';
    public string $addressNote    = 'Online-first travel company';
    public string $officeHours    = 'Mon – Sat, 8:00am – 6:00pm EAT';

    // --- WhatsApp widget ----------------------------------------------------
    /** Digits only, international format, no + or spaces — this is what wa.me needs. */
    public string $whatsappNumber  = '254700000000';
    public string $whatsappName    = 'MOROP GAA Tours and Travel';
    public string $whatsappRole    = 'Travel Consultant';
    public string $whatsappGreeting = "Hi there! 👋\nAsk us anything about our Kenya safaris, beach holidays and custom trips — we usually reply within a few minutes.";
    public string $whatsappPrefill = 'Hi MOROP GAA, I would like to know more about your tours.';
    public bool $whatsappEnabled   = true;

    // --- Social -------------------------------------------------------------
    // Blank means "hide this icon". The nav/footer skip empty values.
    public string $instagram = '';
    public string $facebook  = '';
    public string $tiktok    = '';
    public string $twitter   = '';

    // --- Homepage hero ------------------------------------------------------
    public string $heroEyebrow  = "Kenya's Finest Adventures Await";
    public string $heroHeading  = 'Explore Kenya.<br>Experience the Extraordinary.';
    public string $heroLead     = 'Safaris, beach holidays, mountain treks and cultural experiences — organized end to end, clearly priced, and booked in minutes.';

    // --- About / vision / mission ------------------------------------------
    public string $introHeading = 'Jambo! Karibu Kenya.';
    public string $introBody    = "Discover the magic of Kenya with MOROP GAA Tours and Travel — your trusted partner for luxury, budget-friendly and tailor-made travel experiences. We specialize in unforgettable Kenya safaris, wildlife tours, mountain trekking, beach holidays, cultural experiences, weekend getaways and nature adventures for both local and international travelers.\n\nBorn in Kenya and inspired by its breathtaking beauty, we create authentic, safe and seamless journeys that turn every trip into a lifetime memory. Your next great adventure starts with us.";
    public string $vision       = "To become Kenya's most trusted and preferred travel and adventure company, showcasing Kenya as a destination of endless beauty, unforgettable experiences, and world-class adventures.";
    public string $mission      = 'To deliver world-class travel experiences through exceptional service, unforgettable adventures, and sustainable tourism while creating lasting memories for every traveler.';

    // --- Announcement bar ---------------------------------------------------
    /** Slim promo strip above the nav. Hidden when disabled or the text is blank. */
    public bool $promoEnabled     = true;
    public string $promoText      = 'Ready-to-book Kenyan safaris, beaches and treks — clearly priced.';
    public string $promoLinkText  = 'Explore packages';
    /** Full URL. Blank links to the packages page. */
    public string $promoLink      = '';

    // --- Payment ------------------------------------------------------------
    /**
     * Shown on the booking receipt and package page. Placeholder for now — a
     * real gateway comes later. e.g. "M-Pesa Paybill 123456, account: your name".
     */
    public string $paymentInstructions = '';

    // --- Notifications ------------------------------------------------------
    /** Where new enquiry notifications are sent. Blank disables the email. */
    public string $notifyEmail = 'info@beyondbordersadventures.co.ke';
    public bool $notifyOnEnquiry = false;

    // --- Homepage: section visibility --------------------------------------
    // Each toggles one homepage section on/off. Defaults keep every section on.
    public bool $homeShowContactStrip = true;
    public bool $homeShowHighlights   = true;
    public bool $homeShowCtaBand      = true;
    public bool $homeShowThreeSteps   = true;
    public bool $homeShowStats        = true;
    public bool $homeShowSearch       = true;
    public bool $homeShowFeatured     = true;
    public bool $homeShowExplore      = true;
    public bool $homeShowWhyBook      = true;
    public bool $homeShowTestimonials = true;
    public bool $homeShowGallery      = true;
    public bool $homeShowJournal      = true;
    public bool $homeShowAbout        = true;

    // --- Homepage: "What you can look forward to" --------------------------
    // Five feature chips (icons are fixed in the template). Blank a label to hide it.
    public string $homeHighlightsEyebrow = 'Every trip, done well';
    public string $homeHighlightsHeading = 'What you can look forward to';
    public string $homeHighlight1 = 'Beautiful photos';
    public string $homeHighlight2 = 'Scenic views';
    public string $homeHighlight3 = 'Short nature hikes';
    public string $homeHighlight4 = 'Amazing food';
    public string $homeHighlight5 = 'Unforgettable hotels';

    // --- Homepage: "Three steps to your trip" ------------------------------
    public string $homeStepsEyebrow = 'Custom trips';
    public string $homeStepsHeading = 'Your journey, designed around you';
    public string $homeStepsLede    = 'Tell us the occasion, the group and the budget — we plan the whole thing end to end.';
    public string $homeStepsLabel   = 'Three steps to your trip';
    public string $homeStep1Title = 'Tell us your plan';
    public string $homeStep1Body  = 'Where, when, how many people and roughly what budget — through the search below, WhatsApp or a call.';
    public string $homeStep2Title = 'Get your itinerary & quote';
    public string $homeStep2Body  = 'Within 24 hours we send a day-by-day itinerary with a clear, all-inclusive price. Adjust it until it fits.';
    public string $homeStep3Title = 'Confirm and travel';
    public string $homeStep3Body  = 'Pay securely by M-Pesa or bank transfer. We handle transport, stays and activities — you just show up.';

    // --- Homepage: "Why book with us" --------------------------------------
    public string $homeWhyEyebrow = 'Why book with us';
    public string $homeWhyHeading = 'Travel, handled properly.';
    public string $homeWhy1Title = 'Transparent pricing';
    public string $homeWhy1Body  = 'Inclusions spelled out on every package — the price you see is the price you pay.';
    public string $homeWhy2Title = 'Secure payments';
    public string $homeWhy2Body  = 'Pay securely with M-Pesa or bank transfer, with written confirmation every time.';
    public string $homeWhy3Title = 'Local experts';
    public string $homeWhy3Body  = 'Journeys planned by Kenyans who know the parks, coast and seasons first-hand.';
    public string $homeWhy4Title = 'Accountable';
    public string $homeWhy4Body  = 'One point of contact from enquiry to your journey home. Licensed & registered.';

    // --- Homepage: closing call-to-action band -----------------------------
    public string $homeCtaHeading = 'Allow us to make your stories worth telling.';
    public string $homeCtaBody    = 'Buy a ticket, sit back and relax, and let us plan your whole experience — beautiful photos, scenic views, short nature hikes, amazing food and unforgettable hotels.';

    // --- About page --------------------------------------------------------
    public string $aboutHeroHeading = 'Born in Kenya. Inspired by its beauty.';
    public string $aboutHeroLede    = 'A Kenyan travel company creating authentic, safe and seamless journeys.';
    public string $aboutStoryEyebrow = 'Our story';
    public string $aboutStoryHeading = 'A Kenyan company, for travelers everywhere';
    public string $aboutStoryBody   = "We are a Kenyan travel and adventure company dedicated to creating unforgettable experiences for both local and international travelers. We help you discover Kenya's breathtaking landscapes, incredible wildlife, vibrant cultures and pristine beaches through authentic, safe and memorable journeys.\n\nWhether you're looking for an exciting safari, a relaxing weekend getaway, a budget-friendly escape, a nature adventure, mountain trekking or an immersive cultural experience, we have the expertise to make your dream adventure a reality.";

    // --- Contact page ------------------------------------------------------
    public string $contactHeroHeading = 'Talk to us';
    public string $contactHeroLede    = "Questions about a package, a custom trip or a group booking — we're here to help.";
    public string $contactWhatsAppText = 'The fastest way to reach us — usually within minutes.';
    public string $contactCallText     = "We're happy to talk through your options during office hours.";
    public string $contactEmailText    = 'For itineraries, quotes and anything in writing.';

    // --- Custom Trips page -------------------------------------------------
    // The three-step process on this page reuses the homepage's homeStep* copy.
    public string $customHeroHeading = 'Your journey, designed around you';
    public string $customHeroLede    = 'Tell us the occasion, the group and the budget — we plan the whole thing end to end.';
    public string $customPlanEyebrow = 'What we plan';
    public string $customPlanHeading = 'Any occasion, any group size';
    public string $customPlan1 = 'Private and family safaris, at your own pace';
    public string $customPlan2 = 'Honeymoons and anniversary escapes';
    public string $customPlan3 = 'Corporate staff retreats and team-building';
    public string $customPlan4 = 'Cultural events, weddings and celebrations';
    public string $customPlan5 = 'Group getaways, chamas and student trips';
    public string $customPlan6 = 'Mountain treks and multi-day adventures';
}
