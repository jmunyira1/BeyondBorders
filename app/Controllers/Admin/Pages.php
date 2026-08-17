<?php

namespace App\Controllers\Admin;

/**
 * Editor for the other public pages — About, Contact and Custom Trips.
 * Same mechanism as the Homepage editor: Site.* settings (defaults in
 * Config\Site), rendered through the generic admin/settings/index form.
 *
 * Some blocks are intentionally shared: the About "Why book with us" facts and
 * the Custom Trips three steps reuse the homepage copy, so they're edited once
 * under Admin → Homepage.
 */
class Pages extends AdminController
{
    /** type: text | textarea | note */
    private function fields(): array
    {
        return [
            'About — header' => [
                'aboutHeroHeading' => ['Heading', 'text'],
                'aboutHeroLede'    => ['Sub-heading', 'textarea'],
            ],
            'About — story' => [
                'aboutStoryEyebrow' => ['Eyebrow', 'text'],
                'aboutStoryHeading' => ['Heading', 'text'],
                'aboutStoryBody'    => ['Body', 'textarea', 'Leave a blank line between paragraphs.'],
                'aboutShared'       => ['Vision, mission & "Why book with us"', 'note', 'The vision & mission come from <a href="' . site_url('admin/settings') . '">Settings → About copy</a>; the "Why book with us" facts from <a href="' . site_url('admin/homepage') . '">Homepage</a> (shared with the homepage).'],
            ],
            'Contact — header' => [
                'contactHeroHeading' => ['Heading', 'text'],
                'contactHeroLede'    => ['Sub-heading', 'textarea'],
            ],
            'Contact — channels' => [
                'contactWhatsAppText' => ['WhatsApp blurb', 'textarea'],
                'contactCallText'     => ['Call blurb', 'textarea'],
                'contactEmailText'    => ['Email blurb', 'textarea'],
                'contactShared'       => ['Phone, email & FAQs', 'note', 'The actual phone/email come from <a href="' . site_url('admin/settings') . '">Settings → Contact details</a>; the FAQ list from <a href="' . site_url('admin/faqs') . '">FAQs</a>.'],
            ],
            'Custom Trips — header' => [
                'customHeroHeading' => ['Heading', 'text'],
                'customHeroLede'    => ['Sub-heading', 'textarea'],
                'customSteps'       => ['The three steps', 'note', 'The "Three steps to your trip" block is shared with the homepage — edit it under <a href="' . site_url('admin/homepage') . '">Homepage</a>.'],
            ],
            'Custom Trips — "what we plan"' => [
                'customPlanEyebrow' => ['Eyebrow', 'text'],
                'customPlanHeading' => ['Heading', 'text'],
                'customPlan1' => ['Item 1', 'text', 'Blank to hide.'],
                'customPlan2' => ['Item 2', 'text', 'Blank to hide.'],
                'customPlan3' => ['Item 3', 'text', 'Blank to hide.'],
                'customPlan4' => ['Item 4', 'text', 'Blank to hide.'],
                'customPlan5' => ['Item 5', 'text', 'Blank to hide.'],
                'customPlan6' => ['Item 6', 'text', 'Blank to hide.'],
            ],
        ];
    }

    public function index(): string
    {
        $values = [];
        foreach ($this->fields() as $fields) {
            foreach ($fields as $key => $spec) {
                if ($spec[1] === 'note') {
                    continue;
                }
                $values[$key] = setting('Site.' . $key);
            }
        }

        return view('admin/settings/index', $this->layout([
            'title'       => 'Pages',
            'heading'     => 'Pages',
            'subheading'  => 'Copy for the About, Contact and Custom Trips pages.',
            'activeAdmin' => 'pages',
            'groups'      => $this->fields(),
            'values'      => $values,
            'formAction'  => 'admin/pages',
        ]));
    }

    public function save()
    {
        foreach ($this->fields() as $fields) {
            foreach ($fields as $key => $spec) {
                if ($spec[1] === 'note') {
                    continue;
                }

                $value = $this->request->getPost($key);
                if ($value === null) {
                    continue;
                }

                setting('Site.' . $key, trim((string) $value));
            }
        }

        return redirect()->to(site_url('admin/pages'))->with('message', 'Pages saved.');
    }
}
