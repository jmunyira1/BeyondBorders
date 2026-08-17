<?php

namespace App\Controllers\Admin;

/**
 * Homepage editor. Groups everything that drives the homepage — hero copy,
 * the content blocks, and per-section visibility toggles — in one place.
 *
 * Values are stored as Site.* settings (codeigniter4/settings), overriding the
 * defaults in Config\Site. Reuses the generic admin/settings/index form view.
 */
class Homepage extends AdminController
{
    /** type: text | textarea | bool | note */
    private function fields(): array
    {
        return [
        'Show / hide sections' => [
            'homeShowContactStrip' => ['Contact strip (email · WhatsApp · phone)', 'bool'],
            'homeShowHighlights'   => ['"What you can look forward to"', 'bool'],
            'homeShowCtaBand'      => ['"Stories worth telling" band', 'bool'],
            'homeShowThreeSteps'   => ['"Three steps to your trip"', 'bool'],
            'homeShowStats'        => ['Stat counters', 'bool'],
            'homeShowSearch'       => ['Trip search bar', 'bool'],
            'homeShowFeatured'     => ['Featured packages', 'bool'],
            'homeShowExplore'      => ['"Ways to explore Kenya" tiles', 'bool'],
            'homeShowWhyBook'      => ['"Why book with us"', 'bool'],
            'homeShowTestimonials' => ['Testimonials', 'bool'],
            'homeShowGallery'      => ['Gallery teaser', 'bool'],
            'homeShowJournal'      => ['Journal / blog teaser', 'bool'],
            'homeShowAbout'        => ['About (vision & mission)', 'bool'],
        ],
        'Hero' => [
            'heroEyebrow' => ['Eyebrow line', 'text', 'The small line above the big headline.'],
            'heroHeading' => ['Heading', 'textarea', 'The large headline. A <br> tag is allowed to control the line break.'],
            'heroLead'    => ['Lead paragraph', 'textarea', 'The sentence under the headline.'],
            'heroImages'  => ['Hero images', 'note', 'The rotating background photos come from the Gallery — tick <strong>"Hero"</strong> on the images you want. <a href="' . site_url('admin/gallery') . '">Open the Gallery →</a>'],
        ],
        'What you can look forward to' => [
            'homeHighlightsEyebrow' => ['Eyebrow', 'text'],
            'homeHighlightsHeading' => ['Heading', 'text'],
            'homeHighlight1' => ['Item 1', 'text', 'Blank to hide. (Icon: camera)'],
            'homeHighlight2' => ['Item 2', 'text', 'Blank to hide. (Icon: binoculars)'],
            'homeHighlight3' => ['Item 3', 'text', 'Blank to hide. (Icon: tree)'],
            'homeHighlight4' => ['Item 4', 'text', 'Blank to hide. (Icon: food)'],
            'homeHighlight5' => ['Item 5', 'text', 'Blank to hide. (Icon: building)'],
        ],
        'Three steps to your trip' => [
            'homeStepsEyebrow' => ['Eyebrow', 'text'],
            'homeStepsHeading' => ['Heading', 'text'],
            'homeStepsLede'    => ['Intro sentence', 'textarea'],
            'homeStepsLabel'   => ['Steps label', 'text', 'The small label above the three steps.'],
            'homeStep1Title' => ['Step 1 title', 'text'],
            'homeStep1Body'  => ['Step 1 text', 'textarea'],
            'homeStep2Title' => ['Step 2 title', 'text'],
            'homeStep2Body'  => ['Step 2 text', 'textarea'],
            'homeStep3Title' => ['Step 3 title', 'text'],
            'homeStep3Body'  => ['Step 3 text', 'textarea'],
        ],
        'Why book with us' => [
            'homeWhyEyebrow' => ['Eyebrow', 'text'],
            'homeWhyHeading' => ['Heading', 'text'],
            'homeWhy1Title' => ['Reason 1 title', 'text'],
            'homeWhy1Body'  => ['Reason 1 text', 'textarea'],
            'homeWhy2Title' => ['Reason 2 title', 'text'],
            'homeWhy2Body'  => ['Reason 2 text', 'textarea'],
            'homeWhy3Title' => ['Reason 3 title', 'text'],
            'homeWhy3Body'  => ['Reason 3 text', 'textarea'],
            'homeWhy4Title' => ['Reason 4 title', 'text'],
            'homeWhy4Body'  => ['Reason 4 text', 'textarea'],
        ],
        'Closing call-to-action band' => [
            'homeCtaHeading' => ['Heading', 'text'],
            'homeCtaBody'    => ['Text', 'textarea'],
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
            'title'       => 'Homepage',
            'heading'     => 'Homepage',
            'subheading'  => 'Hero, content blocks and which sections are shown — top to bottom.',
            'activeAdmin' => 'homepage',
            'groups'      => $this->fields(),
            'values'      => $values,
            'formAction'  => 'admin/homepage',
        ]));
    }

    public function save()
    {
        foreach ($this->fields() as $fields) {
            foreach ($fields as $key => $spec) {
                $type = $spec[1];

                if ($type === 'note') {
                    continue;
                }

                if ($type === 'bool') {
                    setting('Site.' . $key, (bool) $this->request->getPost($key));

                    continue;
                }

                $value = $this->request->getPost($key);
                if ($value === null) {
                    continue;
                }

                setting('Site.' . $key, trim((string) $value));
            }
        }

        return redirect()->to(site_url('admin/homepage'))->with('message', 'Homepage saved.');
    }
}
