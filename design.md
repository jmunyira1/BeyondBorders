# Design — Beyond Borders Adventures

A locked design system for this app, produced by `hallmark redesign` (2026-07-21).
Every page redesign reads this file before emitting code. Do not regenerate per
page — extend or amend this file when the system needs to grow.

The public site reads `public/assets/css/tokens.css` + `public/assets/css/hallmark.css`.
The original static design is preserved unmodified at `public/assets/css/theme.css`
(unlinked) and `_design/` for reference. The admin area is out of scope and keeps
its own look.

## Genre

editorial — a Kenyan travel journal, not a SaaS template. Stillness is the brand:
no scroll reveals, no card lifts, no decorative motion.

## Theme — "Sunset Savanna" (custom)

Vibe: golden-hour grassland, warm dust, big sky.

- `--color-paper`      `oklch(96% 0.015 75)` — warm ivory
- `--color-paper-2`    `oklch(93.5% 0.017 75)` — panels, image mats
- `--color-paper-3`    `oklch(90% 0.018 75)` — deeper panel step
- `--color-ink`        `oklch(21% 0.012 60)` — deep umber, headings + body
- `--color-ink-2`      `oklch(38% 0.012 60)` — secondary text
- `--color-muted`      `oklch(44% 0.012 65)` — meta, captions
- `--color-rule`       `oklch(84% 0.015 75)` — hairlines
- `--color-rule-2`     `oklch(88% 0.015 75)` — secondary hairlines
- `--color-accent`     `oklch(52% 0.15 40)` — terracotta sunset
- `--color-accent-hover` `oklch(45% 0.14 40)`
- `--color-accent-ink` `oklch(96.5% 0.012 75)` — text on accent fills
- `--color-focus`      `oklch(54% 0.20 40)` — focus rings only
- `--color-basalt`     `oklch(20% 0.015 50)` — dark warm band: footer + CTA
- `--color-basalt-ink` `oklch(93% 0.012 75)`
- `--color-basalt-muted` `oklch(72% 0.012 70)`
- `--color-basalt-rule`  `oklch(32% 0.014 55)`
- `--color-good`       `oklch(45% 0.09 150)` — inclusions, success
- `--color-bad`        `oklch(45% 0.16 30)` — validation errors

WhatsApp widget only: `--color-whatsapp: #25d366`, `--color-whatsapp-ink: #ffffff`.

Axes: **light / roman-serif / warm (40°)**. Accent footprint ≤ 5% per viewport —
one filled primary CTA per view, active states, link underlines. WhatsApp brand
green (`#25d366`) is exempt inside the WhatsApp widget only.

## Macrostructure family

- Marketing pages (home, custom trips, about): **Marquee Hero** — H6 photographic
  fold on home; the below-fold becomes catalogue grids and prose. Sub-pages open
  with a quiet masthead-style page head (title + one line), not a photo banner.
- Listing pages (packages, gallery, blog): **Catalogue** — uniform grids, hairline
  rules, no hero. The inventory is the design.
- Content pages (package detail, post detail, contact, legal): **Long Document** —
  65ch measure, inline heads, generous line-height, spec-sheet meta tables.

Nav: **N6 newspaper-masthead adapted** — sticky bar, Fraunces wordmark, small-caps
link row, double-rule underline; Bootstrap collapse retained for mobile.
Footer: **Ft5 Statement** on basalt — one Fraunces closing sentence, slim link row,
contact line, copyright.

## Typography

- Display: **Fraunces** (Google, variable opsz/wght), weight 500–600, style normal.
  Italic headers are banned; the strapline pull-quote is roman too.
- Body: **Switzer** (Fontshare), 400; UI/labels 500.
- No third family. Prices and figures use `font-variant-numeric: tabular-nums`.
- Display tracking: −0.02em. Small-caps labels: 0.1em tracking, uppercase.
- Scale (1.25): 0.8 / 1 / 1.25 / 1.5625 / 1.9531 / 2.4414 rem.
  `--text-display: clamp(2.75rem, 5vw + 1rem, 5rem)`,
  `--text-display-s: clamp(2.1rem, 3vw + 1rem, 3.4rem)`.
- Section eyebrows are OFF. Small-caps is reserved for genuine meta (dates,
  durations, breadcrumbs, price labels) — never a decorative section label.

## Spacing

4-point named scale in `tokens.css` (`--space-3xs` … `--space-4xl`). Pages use
named tokens, never raw px. Section rhythm is deliberately uneven: catalogue
sections tighter (`--space-2xl`), statement bands generous (`--space-3xl`).

## Responsive — mobile-first (locked 2026-07-22)

- Base styles ARE the phone. Every deviation scales **up** via `min-width`
  queries (36rem CTAs inline · 40rem two-column collapses · 60rem full rhythm).
  `max-width` queries are banned as a primary direction.
- Section rhythm: `--space-xl` base, `--space-2xl`/`--space-3xl` from 60rem.
- Hero fold uses `svh` (never `dvh`/`vh`) so mobile browser chrome can't
  make it jump. Fold and band CTAs are full-width below 36rem.
- Touch: 44px minimum hit targets on coarse pointers (nav links, footer
  links, typographic CTAs); `viewport-fit=cover` + `env(safe-area-inset-*)`
  on the footer and the WhatsApp launcher.
- Sticky behaviour (booking card) exists only from 60rem — phones scroll flat.
- Bootstrap's own grid utilities (`col-lg-*`, `navbar-expand-lg`) stay on
  Bootstrap's px breakpoints; Hallmark rules use rem breakpoints.

## Motion

- Easings: `--ease-out: cubic-bezier(0.16, 1, 0.3, 1)`, `--ease-in: cubic-bezier(0.7, 0, 0.84, 0)`.
- Durations: `--dur-micro: 120ms`, `--dur-short: 220ms`, `--dur-long: 420ms`.
- Reveal pattern: **none**. Editorial genre is motion-off; the page is just there.
- Allowed: link underline thicken, button press (`translateY(1px)`), catalogue
  image scale 1.02 on hover, htmx opacity dim while a swap is in flight.
- Reduced motion: everything collapses to ≤ 150ms opacity.

## Microinteractions stance

- Silent success — the swapped-in success panel is the confirmation; no toasts.
- Focus rings appear instantly: `outline: 2px solid var(--color-focus); offset 3px`.
- Hover affordances all have focus equivalents; hit targets ≥ 44px on touch.

## CTA voice

- Primary: filled terracotta, sharp corners, Switzer 500, sentence case,
  `white-space: nowrap`. One per view.
- Secondary: hairline-outlined ink chip, same geometry.
- Tertiary: typographic link — “View trip →”, 1px underline, thickens on hover.
- On basalt bands: primary stays terracotta, secondary is outlined basalt-ink.
- Category chips (`.bb-index`): outlined pill buttons — hairline border, pill
  radius, 44px height, paper fill; hover = paper-2 fill + darker border;
  active = ink fill with paper text. They must read as pressable — never
  bare text links. Harmonise with the `.bba-chip` active-filter pills.

## Per-page allowances

- Home may use one photographic fold (H6) with real or placeholder photography.
- Listing + content pages: typography and content imagery only, no enrichment.
- Picsum placeholders remain until real photography is uploaded (see README);
  they are content placeholders, not design decisions.

## What pages MUST share

- The wordmark treatment (Fraunces 600 “Beyond Borders” + small-caps “Adventures”).
- The palette above; terracotta placement discipline.
- Fraunces + Switzer, and the small-caps-for-meta-only rule.
- The CTA voice and button geometry (sharp, nowrap, 44px min height).
- Hairline-rule section language; the basalt statement band as the page close.

## What pages MAY differ on

- Macrostructure within the page-type family.
- Grid density on catalogue pages (2-up editorial vs 3-up inventory).
- Whether the page ends with a basalt CTA band (content pages may end quietly).

## Implementation boundaries

- Bootstrap 5.3 stays (grid, collapse, accordion, form controls) — restyled via
  CSS variables, never forked.
- htmx wiring (`hx-*` attributes, `#packages-results`, form swaps), CSRF fields,
  honeypots, and all `url_to()` routes are functional code — do not alter while
  redesigning.
- `app.css` keeps the WhatsApp widget + htmx feedback styles; its legacy
  `--bba-*` variables are aliased to the new tokens in `tokens.css`.

## Exports

### tokens.css

The canonical file lives at `public/assets/css/tokens.css` — it is the export.

### Tailwind v4 `@theme`

```css
@theme {
  --color-paper:  oklch(96% 0.015 75);
  --color-ink:    oklch(21% 0.012 60);
  --color-accent: oklch(55% 0.15 40);
  --color-basalt: oklch(20% 0.015 50);
  --font-display: "Fraunces", ui-serif, Georgia, serif;
  --font-body:    "Switzer", ui-sans-serif, system-ui, sans-serif;
  --spacing-md:   1rem;
  --ease-out:     cubic-bezier(0.16, 1, 0.3, 1);
}
```

### DTCG tokens.json

```json
{
  "color": {
    "paper":  { "$value": "oklch(96% 0.015 75)",  "$type": "color" },
    "ink":    { "$value": "oklch(21% 0.012 60)",  "$type": "color" },
    "accent": { "$value": "oklch(55% 0.15 40)",   "$type": "color" },
    "basalt": { "$value": "oklch(20% 0.015 50)",  "$type": "color" }
  },
  "font": {
    "display": { "$value": "Fraunces", "$type": "fontFamily" },
    "body":    { "$value": "Switzer",  "$type": "fontFamily" }
  },
  "space": { "md": { "$value": "1rem", "$type": "dimension" } }
}
```

### shadcn/ui CSS variables

```css
:root {
  --background: 96% 0.015 75;
  --foreground: 21% 0.012 60;
  --primary: 55% 0.15 40;
  --primary-foreground: 96.5% 0.012 75;
  --muted: 88% 0.015 75;
  --muted-foreground: 48% 0.012 65;
  --border: 84% 0.015 75;
  --input: 84% 0.015 75;
  --ring: 56% 0.20 40;
  --radius: 0px;
}
```
