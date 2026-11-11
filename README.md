# hectolitre-space

Source code archive of a website of Hectolitre, an art space in Brussels: <http://www.hectolitre.space/>

The site is a static HTML site with one PHP script (the contact form). It is hosted on an OVH PHP server and is
updated by uploading the content of `www/` by FTP (FileZilla). This repository keeps its history and makes maintenance
possible; **the structure of `www/` must stay as it is**, because it is copied one-to-one onto the server.

- `v0-archive` (git tag): the first public snapshot, before any maintenance. Everything done since can be compared with it
  (`git diff v0-archive`).
- License: Mozilla Public License 2.0 (see `LICENSE`).

## Repository layout

```
www/                    everything that goes on the server (see below)
.github/workflows/      pages-preview.yml: optional test copy on GitHub Pages (manual)
.github/ISSUE_TEMPLATE/ issue templates
README.md, LICENSE, .gitignore, .gitattributes
```

## Site structure (`www/`)

### Main pages

| File | Role |
|---|---|
| `index.html` | Home page: menu, "events of the month" panel, news block |
| `project.html` | "À propos [About]" |
| `espaces.html` | The venues: Le Trait d'Union, Le Réacteur, Studio Hectolitre, residency programme |
| `community.html`, `members.html` | Community archive; list of members |
| `web-publications.html`, `video-vitrine.html` | Web publications; video showcase |
| `memoire.html` | **Archive of all events**: a filterable grid (Événements, Résidences, Recherches, Rencontres) linking to each event page. Reached from the menu (ARCHIVES) and from the "Archives / Mémoire" card on the home page |
| `participer.html`, `post-0.html` … `post-6.html` | Calls for projects / residencies and their detailed announcements |
| `contact.html`, `sendcontact.php`, `thanks.html` | Contact form, its mail handler, and the confirmation page |
| `error404.html` | Custom "not found" page (declared in `.htaccess`) |

### Event pages

167 pages named **`echange_YYMMDD.html`** (year, month, day of the event), from `echange_180420.html` to `echange_260911.html`.
Some have a suffix for several events on one day (`echange_190924-1024.html`).
Special pages: `echange_template.html` (model for a new event page), `echange_playful.html`, `echange_atelier_archive.html`.
Every event page is listed in `memoire.html` except two, left out on purpose: `echange_template.html` (a model, not an event) and
`echange_2000926.html` (a mistyped copy of `echange_200108.html`; it redirects there, see `.htaccess` and the `<meta refresh>` in its head).
`echange_playful.html` ("Artists in shared studio", May to September 2019) is listed as a residency.

Each event page's `<title>` and description are taken from its own heading and text.

### Assets

| Folder | Content |
|---|---|
| `images/works/` | Thumbnails of the archive grid in `memoire.html`, named `YYMMDD.jpg` (the thumbnails added in phase 8 are 640 px wide and under 150 KB; some older ones are much heavier) |
| `images/news/` | Photos used inside event pages (`YYMMDD.jpg`, `YYMMDD-1.jpg`…) |
| `images/project/`, `images/memoir/`, `images/members/`, `images/clients/`, `images/bg/` | Images of the matching pages |
| `images/` (root) | Logos (`logo.png`, `hectolitre-blue-logo*.png/jpg`), favicon, icons |
| `stylesheets/` | `main.css` (the site's own styles), `main-responsive.css`, `main-bg.css`, plus trimmed Bootstrap 3, Ionicons, Sinister, slimmenu |
| `javascripts/custom/` | `main.js` (site behaviour, preloader), `form-validation.js` (contact form) |
| `javascripts/libs/` | `common.js` (jQuery 2.1.1 with Isotope, Venobox, Waypoints, Slimmenu, Stellar and other plugins in one file), `bootstrap.min.js` |
| `fonts/` | Glyphicons and Ionicons fonts (the text font Barlow is loaded from Google Fonts) |
| root | `Residence-Studio-Hectolitre-2021.pdf`, `robots.txt`, `sitemap.xml`, `.htaccess` |

Three old backup pages (`index_bcp.html`, `echanges - backup.html`, `participer- backup.html`) were removed in phase 8. The
originals are in the `v0-archive` tag (`git show v0-archive:"www/index_bcp.html"`).

### Things to know

- The navigation menu is **copied into every page** (193 files). A change to the menu means the same edit in every file;
  a search-and-replace across `www/*.html` does it. The same goes for the footer (address, Facebook and Instagram icons,
  "code source (github)" link), which is in every page.
- Pages are in French and English at once, with `[English]` labels in the menu.
- Internal links are relative (`memoire.html`), so the site works from any folder, including a test copy.
- The blue logo `images/hectolitre-blue-logo-2x1.png` (1260 × 640) is the preview image used by the Open Graph tags.

## Adding an event

1. Copy `www/echange_template.html` to `www/echange_YYMMDD.html` and edit the text, title and description in the `<head>`.
2. Add the photos to `images/news/` and a thumbnail `images/works/YYMMDD.jpg` (keep images under about 300 KB).
3. In `memoire.html`, copy an existing `works-item` block (cards run from newest to oldest), point it to the new page and
   thumbnail, and keep the right category class (`evenement`, `residence`, `recherch` or `rencontre`; a card may have several).
4. If it is a current event, update the "events of the month" panel in `index.html`.
5. Add the page to `sitemap.xml`.
6. Preview locally, then upload.

## Previewing

**Locally** (tests exactly what will be uploaded). From the repository root, with Python 3:

```sh
python3 -m http.server 8000
```

then open <http://localhost:8000/www/>. To serve the site from its own root instead:
`python3 -m http.server 8000 --directory www` and open <http://localhost:8000/>.
PHP does not run with this command, so the contact form can only be tested on the server.

**On GitHub Pages** (a test copy for sharing). The workflow `.github/workflows/pages-preview.yml` publishes `www/` to
<https://daqhris.com/hectolitre-space/>. It runs only when started by hand (Actions > "Pages preview" > Run workflow),
with Settings > Pages > Source set to "GitHub Actions". `sendcontact.php` and `.htaccess` are left out of that copy,
and the `.htaccess` rules do not apply there. Its `sitemap.xml`, `robots.txt` and Open Graph tags still point to `www.hectolitre.space`.

## Deploying to the live site (FTP)

1. Pull the latest `main` and preview it locally.
2. In FileZilla, connect to the OVH server and open the site's root folder.
3. Upload the **contents** of `www/` into that folder (not the `www` folder itself), replacing the files that changed.
   Uploading only the changed files is enough; `git diff --name-only <last-deployed-commit> HEAD -- www` lists them.
4. Enable **Server > Force showing hidden files** so that `.htaccess` is visible and uploaded.
5. Check the live site in a private window: home page, a recent event page, `memoire.html` and a missing address (404 page).
6. Note the deployed commit (for example with a tag such as `deployed-YYYY-MM-DD`) so that the next upload knows where to start.

After uploading `sendcontact.php`, send one real test message from the live contact page and check that it arrives
(also look in the spam folder). The settings are at the top of that file; `SENDER_EMAIL` must be a mailbox of the hosting
domain, otherwise OVH may refuse or flag the mail.

`.htaccess` sets UTF-8, the 404 page, compression, browser caching (HTML 1 hour, CSS/JS 1 week, images 1 month) and basic
security headers. Every block is guarded, so a module missing on the host is skipped instead of breaking the site.
The redirect to HTTPS is present but commented out: turn it on only after `https://www.hectolitre.space/` is confirmed
to work with a valid certificate. Because of the caching, a changed CSS/JS file may need a hard refresh to be seen.

## Maintenance history

- **Phase 1, archive**: `v0-archive` tag; `.gitignore` replaced by a project-specific one.
- **Phase 2, bugs** (no file moved or renamed): empty meta tags removed, own title and description on event pages, links made
  relative, `rel="noopener"` on external links, mangled external addresses repaired, links to missing images and pages
  removed, contact form validation script fixed.
- **Phase 4, front end**: the loading screen no longer waits for every image; unused rules removed from Bootstrap, Ionicons
  and Sinister (CSS per page from 372 KB to 71 KB); unused video plugin removed; script-order error fixed; font swap.
- **Phase 6, standards**: `.htaccess`, `robots.txt`, `sitemap.xml`, Open Graph tags, skip link and main landmark.
- **Phase 5, contact form**: `sendcontact.php` rewritten (server-side validation, accents kept in names, plain-text UTF-8 mail
  sent from a fixed address of the domain with the visitor in Reply-To, hidden anti-spam field, link limit, no header injection);
  the form scrolls to its error message; double clicks are blocked.
- **Phase 7, documentation**: this file; manual GitHub Pages preview.
- **Phase 8, cleanup and archive**: `post01.html`, the `less/` folder (only referenced from commented-out blocks, now removed
  from every page), the loose `isotope.js`, `venobox.min.js`, `bgvideo.js`, `isotope.css`, `owl.carousel.css`, unused carousel and
  skills code in `main.js`, `.DS_Store` files and an Office lock file deleted. `memoire.html` now lists every event page (35
  added, with new thumbnails in `images/works/`) and is linked from the home page. Instagram icon and "code source (github)"
  link added to the footers. Footer copyright now runs from each page's first year to 2026. `echange_2000926.html` redirects to
  `echange_200108.html` and is out of `sitemap.xml`. Three backup pages were compared with their live pages and removed. The
  removed placeholder `echanges.html` redirects (301, in `.htaccess`) to `memoire.html`.

## Known issues and next steps

- **Temporary banner**: `index.html` (home page only) shows a blue, non-dismissible bar linking to <https://www.hectolitre.be/> in a new window. It is the `<aside class="site-banner">` block after the skip link, plus the `.site-banner` rules at the end of `stylesheets/main.css`. To remove it, delete both.
- **Image weight (phase 3, not done)**: images total about 339 MB. 59 are over 1 MB and 8 pages load more than 5 MB
  (the heaviest, `echange_200107.html`, about 33 MB). Resizing and re-encoding them is the largest remaining speed gain.
- **Heavy third-party code**: `common.js` (223 KB) still bundles the 2014 jQuery 2.1.1 and several plugins. Only the menu,
  preloader and (on a few pages) the archive filter, lightbox and parallax use them. Options: move to jQuery 3.x with only
  the plugins in use (`$(window).load()` in `main.js` must become `.on('load')`), or rewrite those few behaviours without jQuery.
- **Oddly named event pages**: `echange_1901010.html` and `echange_2311116.html` have a mistyped date in their name. They are
  left as they are because their addresses may be linked from outside. (`echange_2000926.html` also has a typo; it now redirects
  to `echange_200108.html`.)
