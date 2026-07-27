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
    public string $companyName = 'Beyond Borders Adventures';
    /**
     * Nav logo. Either a path relative to public/ (what Admin → Settings
     * uploads produce) or an absolute URL. Blank falls back to the bundled
     * assets/img/logo-nav.png.
     */
    public string $logo        = '';
    public string $tagline     = 'Explore and Enjoy';
    public string $strapline   = "We don't just do trips — we make memories that translate to stories worth telling.";

    // --- Contact ------------------------------------------------------------
    public string $phone          = '+254 700 000 000';
    public string $phoneLink      = '+254700000000';
    public string $email          = 'info@beyondbordersadventures.co.ke';
    public string $address        = 'Nairobi, Kenya';
    public string $addressNote    = 'Online-first travel company';
    public string $officeHours    = 'Mon – Sat, 8:00am – 6:00pm EAT';

    // --- WhatsApp widget ----------------------------------------------------
    /** Digits only, international format, no + or spaces — this is what wa.me needs. */
    public string $whatsappNumber  = '254700000000';
    public string $whatsappName    = 'Beyond Borders Adventures';
    public string $whatsappRole    = 'Travel Consultant';
    public string $whatsappGreeting = "Hi there! 👋\nAsk us anything about our Kenya safaris, beach holidays and custom trips — we usually reply within a few minutes.";
    public string $whatsappPrefill = 'Hi Beyond Borders, I would like to know more about your tours.';
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
    public string $introBody    = "Discover the magic of Kenya with Beyond Borders Adventures — your trusted partner for luxury, budget-friendly and tailor-made travel experiences. We specialize in unforgettable Kenya safaris, wildlife tours, mountain trekking, beach holidays, cultural experiences, weekend getaways and nature adventures for both local and international travelers.\n\nBorn in Kenya and inspired by its breathtaking beauty, we create authentic, safe and seamless journeys that turn every trip into a lifetime memory. Your next great adventure starts with us.";
    public string $vision       = "To become Kenya's most trusted and preferred travel and adventure company, showcasing Kenya as a destination of endless beauty, unforgettable experiences, and world-class adventures.";
    public string $mission      = 'To deliver world-class travel experiences through exceptional service, unforgettable adventures, and sustainable tourism while creating lasting memories for every traveler.';

    // --- Notifications ------------------------------------------------------
    /** Where new enquiry notifications are sent. Blank disables the email. */
    public string $notifyEmail = 'info@beyondbordersadventures.co.ke';
    public bool $notifyOnEnquiry = false;
}
