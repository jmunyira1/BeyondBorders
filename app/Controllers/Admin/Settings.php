<?php

namespace App\Controllers\Admin;

/**
 * Site-wide settings. Values are written to the `settings` table by the
 * codeigniter4/settings library, overriding the defaults in Config\Site.
 */
class Settings extends AdminController
{
    /**
     * The editable fields, grouped for the UI.
     * type: text | textarea | url | email | tel | bool
     */
    private const FIELDS = [
        'Identity' => [
            'companyName' => ['Company name', 'text'],
            'tagline'     => ['Tagline', 'text', 'Shown under the logo in the footer.'],
            'strapline'   => ['Strapline / motto', 'textarea', 'The quoted line on the homepage and about page.'],
        ],
        'Contact details' => [
            'phone'       => ['Phone (display)', 'text', 'How the number is written on the page.'],
            'phoneLink'   => ['Phone (dial)', 'tel', 'The number tel: links use — no spaces.'],
            'email'       => ['Email address', 'email'],
            'address'     => ['Location', 'text'],
            'addressNote' => ['Location note', 'text'],
            'officeHours' => ['Office hours', 'text'],
        ],
        'WhatsApp widget' => [
            'whatsappEnabled'  => ['Show the WhatsApp button', 'bool'],
            'whatsappNumber'   => ['WhatsApp number', 'text', 'International format, digits only — e.g. 254712345678.'],
            'whatsappName'     => ['Name in the chat header', 'text'],
            'whatsappRole'     => ['Role in the chat header', 'text', 'e.g. Travel Consultant.'],
            'whatsappGreeting' => ['Greeting message', 'textarea', 'The bubble shown when the card opens.'],
            'whatsappPrefill'  => ['Pre-filled message', 'textarea', "Added ahead of whatever the visitor types."],
        ],
        'Social links' => [
            'instagram' => ['Instagram URL', 'url', 'Leave as # to hide the icon.'],
            'facebook'  => ['Facebook URL', 'url'],
            'tiktok'    => ['TikTok URL', 'url'],
            'twitter'   => ['X / Twitter URL', 'url'],
        ],
        'Homepage hero' => [
            'heroEyebrow' => ['Eyebrow line', 'text'],
            'heroHeading' => ['Heading', 'textarea', 'A <br> tag is allowed here to control the line break.'],
            'heroLead'    => ['Lead paragraph', 'textarea'],
        ],
        'About copy' => [
            'introHeading' => ['Intro heading', 'text'],
            'introBody'    => ['Intro body', 'textarea', 'Leave a blank line between paragraphs.'],
            'vision'       => ['Our vision', 'textarea'],
            'mission'      => ['Our mission', 'textarea'],
        ],
        'Legal pages' => [
            'termsBody'   => ['Terms & conditions', 'textarea', 'Shown at /terms. Leave blank for the placeholder.'],
            'privacyBody' => ['Privacy policy', 'textarea', 'Shown at /privacy. Leave blank for the placeholder.'],
        ],
    ];

    public function index(): string
    {
        $values = [];

        foreach (self::FIELDS as $fields) {
            foreach ($fields as $key => $spec) {
                $values[$key] = setting('Site.' . $key);
            }
        }

        return view('admin/settings/index', $this->layout([
            'title'       => 'Settings',
            'heading'     => 'Site settings',
            'subheading'  => 'Contact details, copy and the WhatsApp widget.',
            'activeAdmin' => 'settings',
            'groups'      => self::FIELDS,
            'values'      => $values,
        ]));
    }

    public function save()
    {
        foreach (self::FIELDS as $fields) {
            foreach ($fields as $key => $spec) {
                $type = $spec[1];

                if ($type === 'bool') {
                    setting('Site.' . $key, (bool) $this->request->getPost($key));

                    continue;
                }

                // A field absent from the POST is one the form did not render;
                // leave it alone rather than blanking it.
                $value = $this->request->getPost($key);
                if ($value === null) {
                    continue;
                }

                setting('Site.' . $key, trim((string) $value));
            }
        }

        return redirect()->to(site_url('admin/settings'))->with('message', 'Settings saved.');
    }
}
