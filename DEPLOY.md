# Deployment — moropgaa.com

Production setup for the site. Do these in order.

## 1. DNS
Point `moropgaa.com` (and `www`) at the server's IP (A record). Add `www` → `moropgaa.com` redirect at the host if desired.

## 2. Web root = `public/`
The document root **must** point at the project's `public/` folder, so URLs sit at the domain root (`https://moropgaa.com/packages`, `/robots.txt`, `/sitemap.xml`) — not inside a sub-path.
- On cPanel: create the domain, then set its Document Root to `.../moropgaa/public`.
- On a VPS (Apache/Nginx): set the vhost root to `.../public`.

## 3. Upload the project
Upload everything **except**: `.env` (create fresh on the server, see below), `writable/cache/*`, `writable/logs/*`, and any local-only files. Keep `vendor/` or run composer (step 5).

## 4. `.env` (create on the server — never commit real secrets)
```dotenv
CI_ENVIRONMENT = production

app.baseURL = 'https://moropgaa.com/'
app.indexPage = ''
# Turn this ON only AFTER SSL works (step 8), or you'll get a redirect loop:
app.forceGlobalSecureRequests = false

database.default.hostname = localhost
database.default.database  = <production_db_name>
database.default.username  = <production_db_user>
database.default.password  = <production_db_password>
database.default.DBDriver  = MySQLi
database.default.DBPrefix  =

# Generate with: php spark key:generate  (writes it here for you)
encryption.key =

# Optional — only if/when enquiry email alerts are enabled:
# email.fromEmail = info@moropgaa.com
# email.fromName  = 'MOROP GAA Tours and Travel'
# email.protocol  = smtp
# email.SMTPHost  = <smtp host>
# email.SMTPUser  = <smtp user>
# email.SMTPPass  = <smtp pass>
# email.SMTPPort  = 587
# email.SMTPCrypto = tls
```

## 5. Dependencies
```bash
composer install --no-dev --optimize-autoloader
php spark key:generate      # sets encryption.key in .env
```

## 6. Database
Create the production database + user, then:
```bash
php spark migrate            # builds all tables
php spark db:seed DatabaseSeeder   # optional: seed starter content
```
(Or import a SQL dump of your current data instead of seeding.)

## 7. Permissions
`writable/` must be writable by the web server (uploads, cache, logs, sessions):
```bash
chmod -R 775 writable
```

## 8. SSL
Install a certificate (Let's Encrypt / cPanel AutoSSL). Once `https://moropgaa.com` loads with a padlock, set in `.env`:
```dotenv
app.forceGlobalSecureRequests = true
```
This forces all traffic to HTTPS.

## 9. First-run admin tasks (in the admin panel)
- **Change the admin password** (My account) — the seeded password must not go live.
- **Settings**: company name, email (`…@moropgaa.com`), phone, WhatsApp number, address, socials, and upload the real **logo**. Company name + logo cascade everywhere (nav, footer, login, meta, JSON-LD).
- **Gallery**: upload real photos and tick **"Hero"** on the ones for the homepage slideshow.
- **Homepage / Pages** editors: adjust any copy for the real brand.
- Replace the seeded packages/posts with real ones.

## 10. SEO go-live (after the domain resolves)
Everything below already exists and auto-uses `moropgaa.com` once `app.baseURL` is set:
- `https://moropgaa.com/robots.txt`
- `https://moropgaa.com/sitemap.xml`
- `https://moropgaa.com/llms.txt`

Then:
- Submit `sitemap.xml` in **Google Search Console** (verify the domain first) and **Bing Webmaster Tools**.
- Test a package URL in Google's **Rich Results Test** — the Product/Offer/Breadcrumb schema should validate.
- (Optional) Add a dedicated 1200×630 social-share image for nicer link previews.

## Notes
- `CI_ENVIRONMENT = production` hides the debug toolbar and detailed errors, and enables caching — don't leave it as `development` on the live site.
- Keep `.env` out of version control and off public URLs.
