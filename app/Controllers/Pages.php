<?php

namespace App\Controllers;

use App\Models\EnquiryModel;
use App\Models\FaqModel;
use App\Models\GalleryImageModel;
use App\Models\TestimonialModel;

class Pages extends BaseController
{
    public function about(): string
    {
        return view('pages/about', [
            'title'           => 'About — ' . site('companyName'),
            'metaDescription' => 'Beyond Borders Adventures is a Kenyan travel company creating authentic, safe and seamless journeys for local and international travelers.',
            'activeNav'       => 'about',
            'testimonials'    => (new TestimonialModel())->active(3),
        ]);
    }

    public function gallery(): string
    {
        return view('pages/gallery', [
            'title'           => 'Gallery — ' . site('companyName'),
            'metaDescription' => 'Photographs from our safaris, beach holidays, treks and cultural trips across Kenya.',
            'activeNav'       => 'gallery',
            'images'          => (new GalleryImageModel())->active(),
        ]);
    }

    public function contact(): string
    {
        return view('pages/contact', [
            'title'           => 'Contact — ' . site('companyName'),
            'metaDescription' => 'Talk to Beyond Borders Adventures — phone, email, WhatsApp, and answers to the questions we are asked most.',
            'activeNav'       => 'contact',
            'faqs'            => (new FaqModel())->active(),
        ]);
    }

    public function customTrips(): string
    {
        return view('pages/custom_trips', [
            'title'           => 'Custom Trips — ' . site('companyName'),
            'metaDescription' => 'Private safaris, honeymoons, corporate retreats and cultural events — tell us the occasion and we plan the whole journey.',
            'activeNav'       => 'custom-trips',
        ]);
    }

    public function terms(): string
    {
        return view('pages/legal', [
            'title'     => 'Terms — ' . site('companyName'),
            'activeNav' => '',
            'heading'   => 'Terms & Conditions',
            'eyebrow'   => 'Legal',
            'body'      => setting('Site.termsBody') ?: $this->placeholderLegal('terms'),
        ]);
    }

    public function privacy(): string
    {
        return view('pages/legal', [
            'title'     => 'Privacy — ' . site('companyName'),
            'activeNav' => '',
            'heading'   => 'Privacy Policy',
            'eyebrow'   => 'Legal',
            'body'      => setting('Site.privacyBody') ?: $this->placeholderLegal('privacy'),
        ]);
    }

    /** Shown until real copy is pasted into Admin → Settings. */
    private function placeholderLegal(string $which): string
    {
        return $which === 'terms'
            ? "Our full terms and conditions — covering booking, payment, cancellation and liability — are supplied in writing with every quote before any payment is taken.\n\nTo publish them here, paste the text into Admin → Settings."
            : "We collect only the details you give us through our enquiry forms — your name, contact details and what you tell us about the trip you want — and we use them solely to answer your enquiry and arrange your travel. We do not sell or share your details with third parties for marketing.\n\nTo publish your full privacy policy here, paste the text into Admin → Settings.";
    }

    // -----------------------------------------------------------------------
    // Form handlers
    // -----------------------------------------------------------------------

    public function submitContact(): string
    {
        if ($this->isBot()) {
            return $this->thanks();
        }

        if (! $this->validate(EnquiryModel::RULES['contact'])) {
            return view('pages/_contact_form', [
                'errors' => $this->validator->getErrors(),
                'old'    => $this->request->getPost(),
            ]);
        }

        (new EnquiryModel())->insert([
            'type'       => 'contact',
            'name'       => $this->request->getPost('name'),
            'email'      => $this->request->getPost('email'),
            'phone'      => $this->request->getPost('phone'),
            'subject'    => $this->request->getPost('subject') ?: 'General enquiry',
            'message'    => $this->request->getPost('message'),
            'status'     => 'new',
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => substr((string) $this->request->getUserAgent(), 0, 255),
        ]);

        return view('partials/form_success', [
            'heading' => 'Message sent',
            'message' => 'Thank you for getting in touch — we reply to every message within 24 hours.',
        ]);
    }

    public function submitCustomTrip(): string
    {
        if ($this->isBot()) {
            return $this->thanks();
        }

        if (! $this->validate(EnquiryModel::RULES['custom_trip'])) {
            return view('pages/_custom_trip_form', [
                'errors' => $this->validator->getErrors(),
                'old'    => $this->request->getPost(),
            ]);
        }

        (new EnquiryModel())->insert([
            'type'         => 'custom_trip',
            'name'         => $this->request->getPost('name'),
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'subject'      => 'Custom trip enquiry',
            'message'      => $this->request->getPost('message'),
            'trip_type'    => $this->request->getPost('trip_type'),
            'people'       => $this->request->getPost('people') ?: null,
            'travel_dates' => $this->request->getPost('travel_dates'),
            'budget'       => $this->request->getPost('budget'),
            'status'       => 'new',
            'ip_address'   => $this->request->getIPAddress(),
            'user_agent'   => substr((string) $this->request->getUserAgent(), 0, 255),
        ]);

        return view('partials/form_success', [
            'heading' => 'Enquiry received',
            'message' => 'Thank you — one of our consultants will send you a draft itinerary and a quote within 24 hours.',
        ]);
    }

    /** Off-screen field that only an automated submitter would fill in. */
    private function isBot(): bool
    {
        return trim((string) $this->request->getPost('website')) !== '';
    }

    /** Bots get the same success screen a person would, so they learn nothing. */
    private function thanks(): string
    {
        return view('partials/form_success', [
            'heading' => 'Message sent',
            'message' => 'Thank you for getting in touch.',
        ]);
    }
}
