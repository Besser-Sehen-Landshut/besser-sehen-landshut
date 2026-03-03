# Phase 1: Analytics - Research

**Researched:** 2026-02-25
**Domain:** Umami self-hosted analytics — Docker, Apache reverse proxy, HTML tracking script insertion
**Confidence:** HIGH

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| ANAL-01 | Umami Analytics wird auf Pollux-Server via Docker installiert und ist erreichbar | Docker Compose setup documented; KeyHelp subdomain reverse proxy pattern confirmed; analytics.bessersehen.la DNS already resolves to 195.201.220.6 |
| ANAL-02 | Umami-Tracking-Script ist auf allen 8 HTML-Seiten eingebunden | Tracking script `<script defer>` tag pattern documented; all 10 HTML files identified (8 core + impressum/datenschutz); old dead UA code present — needs cleanup |
| ANAL-03 | Besuche auf bessersehen.la werden korrekt in Umami erfasst | data-domains attribute restricts tracking to bessersehen.la only; heartbeat/realtime verification method confirmed |
</phase_requirements>

---

## Summary

Umami is a mature, actively maintained open-source analytics platform (GitHub: umami-software/umami) specifically designed for privacy-first, cookie-free web analytics. It is unambiguously DSGVO-compliant out of the box: no cookies are set, no personal data is collected, no cross-site tracking occurs, and the data stays on the self-hosted EU server. No cookie banner is required.

The standard deployment is Docker Compose with PostgreSQL (MySQL support was dropped in v2). The official `docker-compose.yml` provisions both services with health checks and is production-ready after two changes: setting `APP_SECRET` to a random string and binding port 3000 to `127.0.0.1` only (to prevent Docker from bypassing the firewall). The Umami app runs on `localhost:3000` and is exposed to the internet through an Apache reverse proxy subdomain created in KeyHelp.

The KeyHelp control panel supports adding proxy directives directly to subdomain Apache settings via its UI. The confirmed working pattern is `ProxyPass / http://127.0.0.1:3000/` plus the `/.well-known/acme-challenge` exclusion for Let's Encrypt renewal. The `analytics.bessersehen.la` DNS entry already resolves to the Pollux server (195.201.220.6), so no DNS changes are needed. There are 10 HTML files total (not 8), all containing a dead Google Analytics stub that should be removed and replaced with the Umami tracking script.

**Primary recommendation:** Deploy Umami with official Docker Compose on Pollux; expose via KeyHelp subdomain reverse proxy at analytics.bessersehen.la; insert the `<script defer>` tag in all 10 HTML files before `</head>` replacing the dead GA stub.

---

## Standard Stack

### Core

| Component | Version | Purpose | Why Standard |
|-----------|---------|---------|--------------|
| Umami | latest (v2.x) | Privacy-first web analytics | Official Docker image; no MySQL required; actively maintained; cookie-free by design |
| PostgreSQL | 15-alpine | Umami's database backend | Required for Umami v2+ (MySQL dropped); alpine keeps image small |
| Docker Compose | v2 syntax | Container orchestration | Already installed on Pollux (v5.1.0); official umami/docker-compose.yml uses it |

### Supporting

| Component | Version | Purpose | When to Use |
|-----------|---------|---------|-------------|
| Apache mod_proxy | bundled with Apache | Reverse proxy from subdomain to port 3000 | Already running via KeyHelp; no Nginx needed |
| Let's Encrypt / certbot | via KeyHelp | SSL for analytics.bessersehen.la | KeyHelp manages cert renewal; must exclude /.well-known from ProxyPass |

### Alternatives Considered

| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| Apache mod_proxy | Nginx or Caddy | Nginx has simpler config and better Umami guides, but Apache is already running via KeyHelp — don't add a second web server |
| PostgreSQL | MySQL/MariaDB | Umami v2+ dropped MySQL support; PostgreSQL is the only option |
| Docker Compose | Source install (pnpm) | Source install requires Node.js 18.18+, PM2 process management — more complex; Docker is cleaner on a VPS |

**Installation on server:**
```bash
# On Pollux (195.201.220.6) as root:
cd /opt
git clone https://github.com/umami-software/umami.git umami
cd umami

# Generate APP_SECRET:
openssl rand -base64 32

# Edit docker-compose.yml (see Architecture Patterns below)
docker compose up -d
```

---

## Architecture Patterns

### Deployment Structure

```
Pollux VPS (195.201.220.6)
├── KeyHelp (Apache on :80/:443)
│   └── analytics.bessersehen.la (VirtualHost)
│       └── ProxyPass / http://127.0.0.1:3000/
├── Docker
│   └── /opt/umami/
│       ├── docker-compose.yml   # Umami + PostgreSQL
│       └── .env                 # APP_SECRET, DB password
│           └── umami container  → localhost:3000
│           └── postgres:15-alpine → Docker internal network
└── bessersehen.la (Apache on :443)
    └── /home/users/bessersehen/www/  ← static HTML with tracking script
```

### Pattern 1: Docker Compose with localhost-only binding

**What:** Bind Umami to 127.0.0.1:3000 instead of 0.0.0.0:3000 to prevent Docker from bypassing the firewall via iptables.
**When to use:** Always in production on a VPS — Docker can expose containers publicly even when UFW/firewall blocks the port.

```yaml
# /opt/umami/docker-compose.yml
# Source: https://github.com/umami-software/umami/blob/master/docker-compose.yml (modified)
services:
  umami:
    image: ghcr.io/umami-software/umami:latest
    ports:
      - "127.0.0.1:3000:3000"      # CRITICAL: 127.0.0.1 prefix prevents public exposure
    environment:
      DATABASE_URL: postgresql://umami:${POSTGRES_PASSWORD}@db:5432/umami
      APP_SECRET: ${APP_SECRET}
    depends_on:
      db:
        condition: service_healthy
    init: true
    restart: always
    healthcheck:
      test: ["CMD-SHELL", "curl http://localhost:3000/api/heartbeat"]
      interval: 5s
      timeout: 5s
      retries: 5

  db:
    image: postgres:15-alpine
    environment:
      POSTGRES_DB: umami
      POSTGRES_USER: umami
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
    volumes:
      - umami-db-data:/var/lib/postgresql/data
    restart: always
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U umami -d umami"]
      interval: 5s
      timeout: 5s
      retries: 5

volumes:
  umami-db-data:
```

```bash
# /opt/umami/.env
APP_SECRET=<output of: openssl rand -base64 32>
POSTGRES_PASSWORD=<strong random password — avoid !, @, #, & characters>
```

**WARNING on password:** PostgreSQL connection string parsing breaks with certain special characters (`!`, `@`, `#`, `&`) in passwords — use alphanumeric + dashes/underscores.

### Pattern 2: KeyHelp Apache Reverse Proxy for Subdomain

**What:** Add proxy directives to the `analytics.bessersehen.la` subdomain via KeyHelp's Apache Settings UI.
**When to use:** When running Docker on a server managed by KeyHelp — do NOT edit vhost config files directly as KeyHelp overwrites them.

Steps:
1. In KeyHelp: Create subdomain `analytics.bessersehen.la` under the bessersehen account
2. Open the subdomain's Apache Settings
3. Add to both HTTP and HTTPS sections:

```apache
# Source: https://community.keyhelp.de/viewtopic.php?t=11230 (confirmed working pattern)
<IfModule mod_proxy.c>
    ProxyPass /.well-known/acme-challenge !
</IfModule>

Alias /.well-known/acme-challenge /home/keyhelp/www/.well-known/acme-challenge

ProxyPass / http://127.0.0.1:3000/
ProxyPassReverse / http://127.0.0.1:3000/
```

4. Let KeyHelp issue an SSL certificate for `analytics.bessersehen.la` (Let's Encrypt via KeyHelp)
5. Verify: `curl https://analytics.bessersehen.la/api/heartbeat`

**CRITICAL:** The `ProxyPass /.well-known/acme-challenge !` exclusion MUST come before the root ProxyPass rule. Order matters — once ProxyPass matches, subsequent rules are ignored.

### Pattern 3: Umami Tracking Script in HTML

**What:** Insert a single `<script defer>` tag just before `</head>` in each HTML file.
**When to use:** Static HTML sites — no framework, no build process, direct file edit.

```html
<!-- Source: https://umami.is/docs/tracker-configuration -->
<!-- Umami Analytics — cookiefrei, DSGVO-konform -->
<script
  defer
  src="https://analytics.bessersehen.la/script.js"
  data-website-id="WEBSITE-ID-FROM-UMAMI-DASHBOARD"
  data-domains="bessersehen.la"
></script>
```

The `data-domains="bessersehen.la"` attribute restricts tracking to production only — the script does nothing if loaded from any other domain (e.g., local development, staging).

The `data-website-id` is obtained from the Umami dashboard after adding `bessersehen.la` as a website. It is a UUID like `94db1cb1-74f4-4a40-ad6c-962362670409`.

### Anti-Patterns to Avoid

- **Using `0.0.0.0:3000:3000` in ports:** Bypasses the firewall; Umami dashboard becomes publicly accessible without authentication on port 3000
- **Editing KeyHelp-managed vhost files directly:** KeyHelp regenerates vhost files on domain changes, overwriting manual edits
- **Using base path / subfolder for Umami (e.g. bessersehen.la/analytics):** Next.js BASE_PATH must be set at Docker image BUILD time, not runtime — the pre-built `ghcr.io/umami-software/umami:latest` image does not support subfolder installation without rebuilding. Use a dedicated subdomain instead.
- **Using MySQL/MariaDB:** Umami v2 dropped MySQL support. PostgreSQL only.
- **Omitting the /.well-known/acme-challenge exclusion:** Will break Let's Encrypt certificate renewal on the subdomain

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Cookie-free visitor counting | Custom server-side log parser | Umami | Handles bot filtering, unique visitor fingerprinting, referrer parsing, geo lookup — all edge cases |
| DSGVO-compliant data anonymization | Manual IP hashing | Umami (built-in) | Umami never stores IP addresses — anonymized at collection time |
| Database migrations | Manual SQL | Umami Docker image | Runs `pnpm build` on first start which creates all tables automatically |
| SSL for analytics subdomain | Manual certbot | KeyHelp Let's Encrypt UI | KeyHelp manages certificate issuance and renewal for subdomains it controls |
| Tracking script deployment | Custom analytics.js | Umami's `/script.js` endpoint | Served directly by Umami; auto-updated with Umami version |

**Key insight:** Umami's Docker image is self-contained — it runs DB migrations on start, serves the dashboard, AND serves the tracker script from the same process. There is nothing to build or manually configure beyond environment variables.

---

## Common Pitfalls

### Pitfall 1: Docker bypasses the firewall

**What goes wrong:** Docker adds iptables rules that allow container ports to be reached from the internet, bypassing UFW/iptables DROP rules. If `ports: "3000:3000"` (without `127.0.0.1:` prefix) is used, the Umami dashboard is publicly accessible on port 3000 without any authentication protection.
**Why it happens:** Docker's iptables integration is intentional for Docker networking but bypasses the system firewall.
**How to avoid:** Always use `"127.0.0.1:3000:3000"` in the ports section of docker-compose.yml.
**Warning signs:** `curl http://195.201.220.6:3000` returns the Umami login page from outside the server.

### Pitfall 2: KeyHelp overwrites manually edited vhost files

**What goes wrong:** If you edit Apache vhost files in `/etc/apache2/sites-available/` or similar, KeyHelp will overwrite them when you make any change in the control panel (add subdomain, renew certificate, etc.).
**Why it happens:** KeyHelp manages vhost files as part of its control plane.
**How to avoid:** Only add proxy directives through KeyHelp's "Apache Settings" UI for the subdomain. Those settings are preserved by KeyHelp.
**Warning signs:** Proxy stops working after any KeyHelp operation.

### Pitfall 3: Tracking script URL uses wrong path

**What goes wrong:** Umami serves its tracker at `/script.js` (not `/umami.js` as shown in older docs). Using the wrong path results in 404 and no data collected.
**Why it happens:** Script name changed between Umami v1 and v2. Old tutorials may reference `umami.js`.
**How to avoid:** Use `src="https://analytics.bessersehen.la/script.js"`. Verify by opening the URL in a browser — it should return JavaScript.
**Warning signs:** Browser console shows 404 for the analytics script.

### Pitfall 4: data-website-id not yet known before deployment

**What goes wrong:** The `data-website-id` UUID is only available AFTER adding bessersehen.la as a website in the Umami dashboard. If HTML files are deployed before Umami is running and the website is added, you have to do a second HTML deployment.
**Why it happens:** UUID is generated by Umami on website creation.
**How to avoid:** Complete the Umami server setup AND add the website BEFORE editing and deploying the HTML files. The correct workflow order is: (1) Docker up, (2) First login + change password, (3) Add website in dashboard, (4) Copy UUID, (5) Edit HTML files, (6) Deploy.
**Warning signs:** Tracking script loads but no data appears in Umami.

### Pitfall 5: Special characters in PostgreSQL password break DATABASE_URL

**What goes wrong:** The DATABASE_URL is `postgresql://umami:PASSWORD@db:5432/umami` — if PASSWORD contains `@`, `!`, `#`, or `&`, the URL parser misinterprets the connection string and Umami fails to connect.
**Why it happens:** These characters have special meaning in URLs.
**How to avoid:** Generate password with `openssl rand -hex 32` (hex only, no special chars).
**Warning signs:** `docker compose logs umami` shows database connection errors on startup.

### Pitfall 6: 10 HTML files, not 8

**What goes wrong:** The requirements say "8 HTML-Seiten" but the repository contains 10 HTML files. `datenschutz.html` and `impressum.html` also exist and also contain the dead Google Analytics UA code.
**Why it happens:** The requirements count the main navigational pages; datenschutz/impressum are secondary legal pages.
**How to avoid:** Apply the tracking script to all 10 HTML files. Privacy policy pages (datenschutz.html) especially should have analytics to understand if users read them.
**Warning signs:** Stats in Umami missing page views for legal pages.

### Pitfall 7: Old Google Analytics UA code still present

**What goes wrong:** All 10 HTML files contain a dead Google Analytics Universal Analytics (`UA-185735048-1`) stub. UA was shut down in July 2023 — the stub does nothing but could cause CSP issues in Phase 2 (Security) if not removed.
**Why it happens:** Legacy code was never cleaned up.
**How to avoid:** When inserting the Umami script, simultaneously remove the dead GA comment block from each file.
**Warning signs:** Phase 2 CSP work flags `www.google-analytics.com` as an external domain needing allowlisting.

---

## Code Examples

Verified patterns from official sources:

### Complete docker-compose.yml (production)

```yaml
# Source: https://github.com/umami-software/umami/blob/master/docker-compose.yml (with security hardening)
services:
  umami:
    image: ghcr.io/umami-software/umami:latest
    ports:
      - "127.0.0.1:3000:3000"
    environment:
      DATABASE_URL: postgresql://umami:${POSTGRES_PASSWORD}@db:5432/umami
      APP_SECRET: ${APP_SECRET}
      DISABLE_TELEMETRY: 1
    depends_on:
      db:
        condition: service_healthy
    init: true
    restart: always
    healthcheck:
      test: ["CMD-SHELL", "curl http://localhost:3000/api/heartbeat"]
      interval: 5s
      timeout: 5s
      retries: 5

  db:
    image: postgres:15-alpine
    environment:
      POSTGRES_DB: umami
      POSTGRES_USER: umami
      POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
    volumes:
      - umami-db-data:/var/lib/postgresql/data
    restart: always
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U umami -d umami"]
      interval: 5s
      timeout: 5s
      retries: 5

volumes:
  umami-db-data:
```

### .env file for Docker Compose

```bash
# /opt/umami/.env
APP_SECRET=<openssl rand -base64 32>
POSTGRES_PASSWORD=<openssl rand -hex 16>
```

### KeyHelp Apache Settings for analytics.bessersehen.la

```apache
# Source: https://community.keyhelp.de/viewtopic.php?t=8613 (confirmed working)
<IfModule mod_proxy.c>
    ProxyPass /.well-known/acme-challenge !
</IfModule>

Alias /.well-known/acme-challenge /home/keyhelp/www/.well-known/acme-challenge

ProxyPass / http://127.0.0.1:3000/
ProxyPassReverse / http://127.0.0.1:3000/
```

### Umami tracking script (HTML insertion)

```html
<!-- Source: https://umami.is/docs/tracker-configuration -->
<!-- Umami Analytics — cookiefrei, keine personenbezogenen Daten, DSGVO-konform -->
<script
  defer
  src="https://analytics.bessersehen.la/script.js"
  data-website-id="WEBSITE-ID-HIER-EINTRAGEN"
  data-domains="bessersehen.la"
></script>
```

**Placement:** Just before `</head>` tag, replacing the dead Google Analytics block.

### Verify Umami is healthy

```bash
# On server: check container status
docker compose -f /opt/umami/docker-compose.yml ps

# Test health endpoint directly
curl http://127.0.0.1:3000/api/heartbeat
# Expected: {"ok":true}

# Test via subdomain
curl https://analytics.bessersehen.la/api/heartbeat
# Expected: {"ok":true}

# Test tracker script is accessible
curl -I https://analytics.bessersehen.la/script.js
# Expected: HTTP/2 200, Content-Type: text/javascript
```

### Disable self-tracking during development (browser console)

```javascript
// Source: Umami docs — exclude own visits from stats
localStorage.setItem('umami.disabled', 1);
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Google Analytics UA (UA-XXXXX) | Umami self-hosted | July 2023 (UA shutdown) | No US data transfer, no cookies, no consent required |
| MySQL support in Umami | PostgreSQL only | Umami v2 release | Must use postgres:15-alpine in Docker Compose |
| `umami.js` script name | `script.js` | Umami v2 | Old tutorials wrong — use `/script.js` |
| Docker Hub image | ghcr.io/umami-software/umami:latest | Ongoing | GitHub Container Registry is the official source |

**Deprecated/outdated:**
- `docker.umami.is/umami-software/umami:postgresql-latest`: This registry path appears in some guides but the canonical official image is `ghcr.io/umami-software/umami:latest` per the official docker-compose.yml on GitHub
- UA-185735048-1 Google Analytics code: Present in all 10 HTML files, dead since July 2023, should be removed

---

## Open Questions

1. **KeyHelp account context for the subdomain**
   - What we know: analytics.bessersehen.la DNS already resolves to 195.201.220.6. KeyHelp manages the server.
   - What's unclear: Whether the subdomain should be created under the `bessersehen` user account or under a separate admin-level domain in KeyHelp. If created under `bessersehen`, it may appear in the user's hosting quota.
   - Recommendation: Create under the KeyHelp admin level or as a standalone domain, not under the bessersehen hosting account — to keep analytics infrastructure separate from client site.

2. **Apache mod_proxy enabled status**
   - What we know: KeyHelp uses Apache; the community confirms mod_proxy works for reverse proxies.
   - What's unclear: Whether mod_proxy and mod_proxy_http are already enabled on this specific Pollux instance.
   - Recommendation: First task should verify with `apache2ctl -M | grep proxy`. If not enabled: `a2enmod proxy proxy_http && systemctl restart apache2`.

3. **Exact HTML file scope for tracking script**
   - What we know: Requirements say "8 HTML-Seiten"; the repo has 10 HTML files (includes datenschutz.html and impressum.html).
   - What's unclear: Whether the planner should treat this as 8 or 10.
   - Recommendation: Apply to all 10. Tracking privacy/legal pages is legitimate and useful. The requirements were written before counting all files.

---

## Sources

### Primary (HIGH confidence)

- https://github.com/umami-software/umami/blob/master/docker-compose.yml — Official docker-compose.yml, exact service definitions and image names
- https://umami.is/docs/install — Official installation guide, Node.js requirements, Docker method
- https://umami.is/docs/environment-variables — All env vars including APP_SECRET, DATABASE_URL, DISABLE_TELEMETRY
- https://umami.is/docs/tracker-configuration — Tracking script attributes, data-website-id, data-domains
- https://umami.is/docs/faq — GDPR compliance confirmed: no cookies, no PII, no consent banner needed
- https://umami.is/docs/login — Default credentials: admin/umami, change immediately

### Secondary (MEDIUM confidence)

- https://community.keyhelp.de/viewtopic.php?t=11230 — KeyHelp reverse proxy pattern (ProxyPass / http://127.0.0.1:PORT/), confirmed working by community members
- https://community.keyhelp.de/viewtopic.php?t=8613 — KeyHelp Apache proxy with /.well-known exclusion, Apache 2.4 syntax
- https://community.keyhelp.de/viewtopic.php?t=7839 — KeyHelp overwrites vhost files; confirmed use UI not direct file edits
- https://ivansalloum.com/self-host-private-and-lightweight-analytics-with-umami/ — Production hardening (127.0.0.1 binding, password special char warning)

### Tertiary (LOW confidence)

- https://stfn.pl/blog/40-umami-self-hosted-analytics/ — X-Real-IP / X-Forwarded-Proto headers needed for country data via Nginx; may apply to Apache too (unverified for Apache)
- https://github.com/umami-software/umami/issues/2349 — SSL proxy issues; resolved by HOSTNAME=0.0.0.0 default in recent Docker images (already in ghcr.io image)

---

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — Official docker-compose.yml verified, PostgreSQL-only confirmed, image name verified
- Architecture (Docker): HIGH — Official docs + verified docker-compose.yml
- Architecture (KeyHelp reverse proxy): MEDIUM — Confirmed via KeyHelp community forum; not official KeyHelp docs
- Architecture (HTML script insertion): HIGH — Official tracker-configuration docs
- Pitfalls: MEDIUM-HIGH — Most from official docs or verified community; Docker firewall bypass is well-documented

**Research date:** 2026-02-25
**Valid until:** 2026-03-25 (Umami moves quickly; verify latest image tag before deployment)
