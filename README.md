# hectolitre-space
source code archive of the official site: hectolitre.space

The site itself lives in `www/` and is uploaded as-is to the OVH server by FTP.
The tag `v0-archive` marks the first public snapshot, before any maintenance.

## Previewing the site

### 1. Locally (quickest, tests exactly what FTP will upload)

From the root of the repository, with Python 3 installed:

```sh
python3 -m http.server 8000
```

then open <http://localhost:8000/www/> in a browser. Stop the server with `Ctrl+C`.
To serve the site from its own root instead, run `python3 -m http.server 8000 --directory www`
and open <http://localhost:8000/>.

PHP does not run with this command, so the contact form (`sendcontact.php`) can only be tested on the OVH server.

### 2. On GitHub Pages (test copy to share with others)

The workflow `.github/workflows/pages-preview.yml` publishes `www/` to
<https://daqhris.com/hectolitre-space/>. It runs only when started by hand
(Actions > "Pages preview" > Run workflow), after setting
Settings > Pages > Source to "GitHub Actions".
`sendcontact.php` and `.htaccess` are left out of that copy, and neither PHP nor `.htaccess` rules (caching, compression, 404 page) apply there.
