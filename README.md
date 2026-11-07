# hectolitre-space

Source code archive of the official website of Hectolitre, an art space in Brussels: <http://www.hectolitre.space/>

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
| `memoire.html` | **Archive of all events**: a filterable grid (Événements, Résidences, Recherches, Rencontres) linking to each event page |
| `participer.html`, `post-0.html` … `post-6.html` | Calls for projects / residencies and their detailed announcements |
| `contact.html`, `sendcontact.php`, `thanks.html` | Contact form, its mail handler, and the confirmation page |
| `error404.html` | Custom "not found" page (declared in `.htaccess`) |
| `echanges.html` | Placeholder page ("En construction") |

### Event pages

167 pages named **`echange_YYMMDD.html`** (year, month, day of the event), from `echange_180420.html` to `echange_260911.html`.
Some have a suffix for several events on one day (`echange_190924-1024.html`).
Special pages: `echange_template.html` (model for a new event page), `echange_playful.html`, `echange_atelier_archive.html`.

Each event page's `<title>` and description are taken from its own heading and text.

### Assets

| Folder | Content |
|---|---|
| `images/works/` | Thumbnails of the archive grid in `memoire.html`, named `YYMMDD.jpg` |
| `images/news/` | Photos used inside event pages (`YYMMDD.jpg`, `YYMMDD-1.jpg`…) |
| `images/project/`, `images/memoir/`, `images/members/`, `images/clients/`, `images/bg/` | Images of the matching pages |
| `images/` (root) | Logos (`logo.png`, `hectolitre-blue-logo*.png/jpg`), favicon, icons |
| `stylesheets/` | `main.css` (the site's own styles), `main-responsive.css`, `main-bg.css`, plus trimmed Bootstrap 3, Ionicons, Sinister, slimmenu |
| `javascripts/custom/` | `main.js` (site behaviour, preloader), `form-validation.js` (contact form) |
| `javascripts/libs/` | `common.js` (jQuery 2.1.1 and plugins in one file), `bootstrap.min.js` (`isotope.js` and `venobox.min.js` are present but not loaded) |
| `fonts/` | Glyphicons and Ionicons fonts (the text font Barlow is loaded from Google Fonts) |
| `less/` | LESS sources of colours and fonts; no page loads them |
| root | `Residence-Studio-Hectolitre-2021.pdf`, `templete.psd`, `robots.txt`, `sitemap.xml`, `.htaccess` |

Backups kept on purpose, not linked from the menu: `index_bcp.html`, `echanges - backup.html`, `participer- backup.html`.

### Things to know

- The navigation menu is **copied into every page** (194 files). A change to the menu means the same edit in every file;
  a search-and-replace across `www/*.html` does it.
- Pages are in French and English at once, with `[English]` labels in the menu.
- Internal links are relative (`memoire.html`), so the site works from any folder, including a test copy.
- The blue logo `images/hectolitre-blue-logo-2x1.png` (1260 × 640) is the preview image used by the Open Graph tags.

## Adding an event

1. Copy `www/echange_template.html` to `www/echange_YYMMDD.html` and edit the text, title and description in the `<head>`.
2. Add the photos to `images/news/` and a thumbnail `images/works/YYMMDD.jpg` (keep images under about 300 KB; see "Image weight").
3. In `memoire.html`, copy an existing `works-item` block, point it to the new page and thumbnail, and keep the right
   category class (`evenement`, `residence`, `recherch` or `rencontre`).
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
- **Phase 7, documentation**: this file; manual GitHub Pages preview.

## Known issues and next steps

- **Image weight (phase 3, not done)**: images total about 339 MB. 59 are over 1 MB and 8 pages load more than 5 MB
  (the heaviest, `echange_200107.html`, about 33 MB). Resizing and re-encoding them is the largest remaining speed gain.
- **Contact form (phase 5, not done)**: `sendcontact.php` builds its sender header from visitor input, sends in ISO-8859-1
  and has no spam protection.
- **`post01.html`**: an old template page linking about 47 pages that never existed. Nothing links to it; it is marked `noindex`.
- **Event pages not linked from any page**: 27 (reachable by their address only), for example `echange_190115.html`.
  They are in `sitemap.xml`.
- **Unused files**: `javascripts/custom/bgvideo.js`, `javascripts/libs/isotope.js`, `javascripts/libs/venobox.min.js`, `stylesheets/isotope.css` and the `less/` folder
  are not loaded by any page. They are kept for now and can be removed once confirmed.
- **Heavy third-party code**: `common.js` still bundles the 2014 jQuery 2.1.1 and several plugins.
