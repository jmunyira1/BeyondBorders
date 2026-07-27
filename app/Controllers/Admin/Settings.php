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
     * type: text | textarea | url | email | tel | bool | image
     */
    private const FIELDS = [
        'Identity' => [
            'companyName'      => ['Company name', 'text'],
            'logo'             => ['Logo', 'image', 'Shown in the site header. PNG or WebP with a transparent background works best — around 200×80px. Leave empty to use the bundled logo.'],
            'wordmarkSubtitle' => ['Logo subtitle', 'text', 'The small line under the name in the header, e.g. "Tours and Travel". Leave blank to use the last word of the company name.'],
            'tagline'          => ['Tagline', 'text', 'Shown under the logo in the footer.'],
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
            'instagram' => ['Instagram URL', 'url', 'Optional — leave blank to hide the icon.'],
            'facebook'  => ['Facebook URL', 'url', 'Optional — leave blank to hide the icon.'],
            'tiktok'    => ['TikTok URL', 'url', 'Optional — leave blank to hide the icon.'],
            'twitter'   => ['X / Twitter URL', 'url', 'Optional — leave blank to hide the icon.'],
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

                if ($type === 'image') {
                    if (! $this->saveImageSetting($key)) {
                        return redirect()->to(site_url('admin/settings'))
                            ->with('error', $this->uploadError ?? 'That image could not be saved.');
                    }

                    continue;
                }

                // A field absent from the POST is one the form did not render;
                // leave it alone rather than blanking it.
                $value = $this->request->getPost($key);
                if ($value === null) {
                    continue;
                }

                // "#" was the old "hidden" sentinel for social links; treat it
                // as blank so nothing tries to validate or link to it.
                $value = trim((string) $value);
                setting('Site.' . $key, $value === '#' ? '' : $value);
            }
        }

        return redirect()->to(site_url('admin/settings'))->with('message', 'Settings saved.');
    }

    private ?string $uploadError = null;

    /**
     * Image settings carry three controls: a file input, a "remove" checkbox,
     * and a URL field for pointing at an externally-hosted image. A new upload
     * or an explicit removal replaces whatever was there, deleting the old
     * file so uploads don't pile up.
     *
     * @return bool false when the upload failed and the redirect should report it.
     */
    private function saveImageSetting(string $key): bool
    {
        $current = (string) setting('Site.' . $key);

        if ((bool) $this->request->getPost($key . '_remove')) {
            $this->deleteUpload($current);
            setting('Site.' . $key, '');

            return true;
        }

        try {
            $path = $this->handleUpload($key . '_file', 'branding');
        } catch (\RuntimeException $e) {
            $this->uploadError = $e->getMessage();

            return false;
        }

        if ($path !== null) {
            $this->deleteUpload($current);
            setting('Site.' . $key, $path);

            return true;
        }

        // No file chosen — fall back to the URL field.
        //
        // An *empty* URL field never clears the setting: it renders blank
        // whenever the current logo is an upload rather than a link, so
        // treating empty as "remove" would wipe the logo every time an
        // unrelated setting was saved. Clearing is the checkbox's job.
        $url = trim((string) ($this->request->getPost($key . '_url') ?? ''));

        if ($url === '' || $url === $current) {
            return true;
        }

        if (! preg_match('~^(https?:)?//~', $url)) {
            $this->uploadError = 'The logo URL must start with http:// or https://.';

            return false;
        }

        $this->deleteUpload($current);
        setting('Site.' . $key, $url);

        return true;
    }
}
