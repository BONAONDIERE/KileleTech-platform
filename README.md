# Kilele Tech Website

A multi-page PHP/HTML/CSS/JS website for **Kilele Tech**, a Nairobi-based ICT
consultancy. Built on the same visual system as the earlier Kilele Sacco site
(navy/teal/coral/yellow palette, colourful wordmark, marquee announcement bar,
split-panel hero carousel, numbered feature cards) — but every page's *content
and structure* has been repurposed to fit a technology consultancy instead of
a savings cooperative.

## What changed from the Sacco version

| Sacco page | Became | Why |
|---|---|---|
| Board of Directors + Supervisory Committee + Management | `team.php` — one "Our Team" page (Leadership + Advisory Board) | A private consultancy doesn't need 3 separate cooperative-governance pages; consolidated into one, per your instruction to repurpose rather than force-fit. |
| Products (loans/savings) | `services.php` — your real 8 services | Direct mapping: each service card now uses your actual descriptions, expertise tags, and pricing notes. |
| Punguza Mizigo (loan consolidation) | "One Partner, All Your ICT Needs" bundling section, on `index.php` | Repurposed the "combine multiple things into one" concept from debt consolidation into service bundling. |
| Membership tiers (Inuka/Seed Q/Crown) | Service Packages (Starter/Business/Enterprise), on `index.php` and `quote.php` | Same 3-tier card layout, now representing support package tiers instead of savings tiers. |
| Join Us (savings enrollment) | `quote.php` — "Get a Quote" with a real request form | Repurposed the enrollment flow into a sales/consultation request flow. |
| Tenders | `projects.php` — project showcase | Reused the same list + status-badge styling, now showing Completed/Ongoing projects instead of open/closed tenders. |

## Folder structure

```
kilele-tech/
├── index.php              Homepage
├── about.php                About Us (real company profile content)
├── team.php                  Leadership + Advisory Board
├── services.php                All 8 real services
├── hub.php                      Resources (repurposed for ICT support)
├── blogs.php                     ICT-topic journal
├── downloads.php                  Company profile, SLA, whitepapers
├── projects.php                    Project showcase (was Tenders)
├── contact.php                      Contact form + map
├── quote.php                         Get a Quote (was Join Us)
├── includes/
│   ├── header.php               Shared top bar, nav, announcement bar, side panel
│   └── footer.php                Shared footer + newsletter form + scripts
├── css/
│   └── layout.css                Same design system as the Sacco site (unchanged)
├── js/
│   └── main.js                    Hero carousel, marquee pause, 3 forms' AJAX submit
├── php/
│   ├── contact-handler.php        Contact form → kileletechofficial@gmail.com
│   ├── quote-handler.php          Quote request form → kileletechofficial@gmail.com
│   └── newsletter-handler.php     Newsletter signups → CSV
└── assets/images/                 Empty — drop your real photos/logo here
```

All internal links are **relative** (`services.php`, not `/services.php`), so
the site works correctly whether it's hosted at your domain root or inside a
subfolder like `htdocs/kilele-tech/`.

## Running it

```bash
cd kilele-tech
php -S localhost:8000
```
Then open `http://localhost:8000/index.php`. Or copy the folder into XAMPP's
`htdocs/` and visit `http://localhost/kilele-tech/index.php`.

## Forms (3 total, all working)

- **Contact form** (`contact.php`) → `php/contact-handler.php`
- **Quote request form** (`quote.php`) → `php/quote-handler.php`
- **Newsletter signup** (footer, every page) → `php/newsletter-handler.php`

All three validate input, log submissions to disk (`php/contact-submissions.log`,
`php/quote-requests.log`, `php/newsletter-subscribers.csv`), and attempt to send
email via `mail()`. As before, `mail()` needs a configured mail transport to
actually deliver — see the comments in each handler file for production options
(PHPMailer, SendGrid, etc.).

## Content still to personalize

- Team names/roles on `team.php` are placeholders — swap in your real leadership
  and advisors.
- Project entries on `projects.php` are illustrative — replace with real case studies.
- Blog posts on `blogs.php` are sample topics — replace with real articles.
- Service pricing beyond what you gave me (e.g. exact figures for hardware,
  database, security monitoring) is left as "Custom quote" / "SLA-based" per
  your original profile — fill in real numbers if you want them public.
- Google Maps embed still points at a generic Westlands, Nairobi pin — replace
  with your exact building coordinates.

## Verified before packaging

- Every internal link resolves to a real file.
- Every form action points to a real, existing handler.
- Every CSS class used has either a matching rule in `layout.css` or is
  intentionally inline-styled (the "What We Offer" cards).
- No leftover Sacco/loan/membership language anywhere in the content.
- All PHP files have balanced `<?php` / `?>` tags.
