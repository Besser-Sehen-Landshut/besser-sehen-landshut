# Phase 2: Security - Research

**Researched:** 2026-02-25
**Domain:** Apache .htaccess HTTP Security Headers
**Confidence:** HIGH

## Summary

This phase adds five HTTP security headers to the Apache server via `.htaccess`. The site is already
HTTPS-only (SSL active, confirmed in STATE.md), served by Apache on a KeyHelp VPS, and deployed via
rsync from GitHub Actions. The `.htaccess` lives in `./public/` — everything rsync-copied to the
server — so adding a `.htaccess` file there is the natural, lowest-risk approach.

The four simple headers (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy) are
straightforward and carry no breakage risk. Content-Security-Policy (CSP) is the critical complexity:
the site loads Google Fonts, embeds Google Maps iframes, runs the Umami analytics script from a
subdomain, and contains inline `<script>` blocks with `document.write()` calls and at least one
inline `<style>` block. The practical solution for a legacy jQuery-stack static site is
`'unsafe-inline'` in `script-src` and `style-src`, which is documented and accepted by
securityheaders.com for grade B. Refactoring 69+ inline JS occurrences across all pages is
explicitly out of scope for this phase.

**Primary recommendation:** Write one `.htaccess` file with `<IfModule mod_headers.c>` containing
all five headers. Use `Header always set` for all security headers. Use `'unsafe-inline'` in CSP
`script-src` and `style-src` as a pragmatic baseline compatible with the existing codebase. Deploy
via normal git push to trigger GitHub Actions rsync.

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| SEC-01 | .htaccess setzt HSTS-Header (Strict-Transport-Security, max-age=31536000) | Direct: `Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"` |
| SEC-02 | .htaccess setzt X-Frame-Options: SAMEORIGIN | Direct: `Header always set X-Frame-Options "SAMEORIGIN"` — NOTE: DENY would break the Google Maps iframe on the site itself if loaded in a frame, but since this controls how *our* pages are framed, SAMEORIGIN is correct |
| SEC-03 | .htaccess setzt X-Content-Type-Options: nosniff | Direct: `Header always set X-Content-Type-Options "nosniff"` |
| SEC-04 | .htaccess setzt Referrer-Policy: strict-origin-when-cross-origin | Direct: `Header always set Referrer-Policy "strict-origin-when-cross-origin"` |
| SEC-05 | .htaccess setzt Content-Security-Policy (erlaubt eigene Assets + Google Fonts) | Requires careful CSP: self + fonts.googleapis.com/gstatic.com + analytics.bessersehen.la + www.google.com (Maps iframe frame-src) + unsafe-inline for script/style |
</phase_requirements>

## Standard Stack

### Core
| Library/Tool | Version | Purpose | Why Standard |
|-------------|---------|---------|--------------|
| Apache mod_headers | Built-in (Apache 2.4+) | Set/modify HTTP response headers | Native to Apache, no install needed; enabled via .htaccess |
| `.htaccess` | Apache standard | Per-directory config override | Only option on shared/managed hosting without server-level access |

### Supporting
| Tool | Purpose | When to Use |
|------|---------|-------------|
| securityheaders.com | Verify headers after deploy | Post-deploy validation — paste URL, checks all 5 headers |
| curl -I | Local/CI verification | `curl -I https://bessersehen.la` — shows response headers instantly |

**No installation required.** mod_headers is a standard Apache module; on Debian/Ubuntu-based systems
it can be enabled with `a2enmod headers` if not already active. On KeyHelp VPS with existing SSL and
.htaccess support (confirmed in STATE.md), mod_headers is enabled by default.

## Architecture Patterns

### Recommended Project Structure
```
public/
├── .htaccess          # NEW — security headers (this phase)
├── index.html
├── ...other HTML files
```

The `.htaccess` is placed in `public/` — the rsync source root. It deploys alongside HTML automatically.

### Pattern 1: mod_headers in .htaccess
**What:** All security headers inside a single `<IfModule mod_headers.c>` block using `Header always set`
**When to use:** Always on Apache. The `IfModule` guard prevents 500 errors if mod_headers is somehow absent.
**Example:**
```apache
# Source: OWASP HTTP Headers Cheat Sheet + Apache docs 2.4
<IfModule mod_headers.c>
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Content-Security-Policy "..."
</IfModule>
```

### Pattern 2: `Header always set` (not `Header set`)
**What:** The `always` condition applies the header to ALL responses, including 4xx/5xx error responses.
**When to use:** Security headers must appear on error pages too. Without `always`, headers only appear on 2xx success responses.
**Key difference confirmed:** `Header set` = onsuccess (2xx only). `Header always set` = all responses including errors.

### Pattern 3: CSP for this specific site
**What:** CSP must allow all actual resource origins without breaking functionality.
**Confirmed external origins on bessersehen.la:**
- `fonts.googleapis.com` — Google Fonts CSS (style-src)
- `fonts.gstatic.com` — Google Fonts files (font-src)
- `analytics.bessersehen.la` — Umami tracking script (script-src)
- `www.google.com` — Google Maps embed via `<iframe>` (frame-src)
- `'self'` — own CSS, JS, images, favicon

**Inline code present (requires `unsafe-inline`):**
- Inline `<script>` blocks with `document.write()` for email obfuscation (69+ occurrences across all HTML)
- Inline `<style>` block for WhatsApp floating button
- jQuery plugins produce inline styles dynamically at runtime

**CSP directive:**
```apache
Header always set Content-Security-Policy "default-src 'self'; \
    script-src 'self' 'unsafe-inline' https://analytics.bessersehen.la; \
    style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; \
    font-src 'self' https://fonts.gstatic.com; \
    img-src 'self' data: https://*.googleapis.com https://*.gstatic.com https://*.ggpht.com; \
    frame-src https://www.google.com; \
    connect-src 'self' https://analytics.bessersehen.la; \
    object-src 'none'"
```

### Anti-Patterns to Avoid
- **`Header set` without `always`:** Security headers won't appear on error pages — use `Header always set`.
- **Omitting `<IfModule mod_headers.c>`:** If mod_headers is somehow disabled, bare `Header` directives cause a 500 error.
- **HSTS with `preload` immediately:** Preload is a HSTS Preload List commitment; once submitted it's hard to undo. Start without `preload` flag for first deployment. Add `preload` later if desired.
- **`default-src *` as a shortcut:** Defeats the purpose of CSP entirely.
- **Forgetting `analytics.bessersehen.la` in script-src:** The Umami script would be blocked. This is a subdomain — `'self'` does NOT cover it.

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Security header validation | Custom curl script | securityheaders.com | Checks all headers, scores grade, lists missing items |
| CSP testing before commit | Manual browser check | Browser DevTools console (CSP violations appear there) | Instant feedback on blocked resources |
| Header syntax verification | Guess | Official Apache docs + MDN | Header values have exact required syntax |

**Key insight:** The only "code" in this phase is 10-15 lines of `.htaccess`. The complexity is
getting the CSP value exactly right for this site's specific resources.

## Common Pitfalls

### Pitfall 1: CSP Blocks Umami Analytics
**What goes wrong:** Umami script at `analytics.bessersehen.la` is blocked; no tracking data flows.
**Why it happens:** `default-src 'self'` does not cover subdomains — `analytics.bessersehen.la` is
treated as a different origin.
**How to avoid:** Explicitly include `https://analytics.bessersehen.la` in both `script-src` (for the
script load) and `connect-src` (for the beacon/fetch calls Umami makes back to itself).
**Warning signs:** Umami dashboard shows 0 visits after deploy.

### Pitfall 2: CSP Blocks Google Maps iframe
**What goes wrong:** The Google Maps embed (used on index.html and kontakt.html) shows as a blank box.
**Why it happens:** `<iframe src="https://www.google.com/maps/...">` requires `frame-src` to permit
`www.google.com`.
**How to avoid:** Add `frame-src https://www.google.com` to CSP.
**Warning signs:** Blank iframe, browser console shows CSP violation for `frame-src`.

### Pitfall 3: CSP Blocks Inline Scripts (document.write)
**What goes wrong:** Email obfuscation scripts (`document.write(...)`) don't run; email addresses don't appear.
**Why it happens:** Inline `<script>` blocks are blocked by default in CSP without `'unsafe-inline'`.
**How to avoid:** Include `'unsafe-inline'` in `script-src`. Nonce-based alternative would require
modifying every HTML file — out of scope.
**Warning signs:** Email links missing in page, browser console shows inline script CSP violation.

### Pitfall 4: Google Fonts Blocked
**What goes wrong:** Site renders in fallback font (sans-serif), not Poppins/Rajdhani.
**Why it happens:** `fonts.googleapis.com` not in `style-src`, or `fonts.gstatic.com` not in `font-src`.
**How to avoid:** Both domains required. The CSS loads from googleapis.com; the actual font files from gstatic.com.
**Warning signs:** Wrong fonts visible, browser console shows CSP violation for style or font.

### Pitfall 5: HSTS Locks Out Non-HTTPS Subdomains
**What goes wrong:** `includeSubDomains` in HSTS causes HSTS to apply to ALL subdomains. If any
subdomain lacks valid SSL, browsers refuse to connect.
**Why it happens:** `includeSubDomains` extends HSTS to *.bessersehen.la including analytics.bessersehen.la.
**How to avoid:** `analytics.bessersehen.la` has a working SSL cert (Umami is live and HTTPS, confirmed
in Phase 1). Safe to use `includeSubDomains`.
**Warning signs:** Browser security error on analytics.bessersehen.la.

### Pitfall 6: mod_headers Not Enabled
**What goes wrong:** Apache ignores all `Header` directives or returns 500 Internal Server Error.
**Why it happens:** mod_headers must be loaded. KeyHelp setups typically enable it, but not guaranteed.
**How to avoid:** Wrap all directives in `<IfModule mod_headers.c>`. Test with `curl -I` immediately after deploy.
**Warning signs:** `curl -I` shows none of the expected headers.

### Pitfall 7: X-Frame-Options DENY vs SAMEORIGIN
**What goes wrong:** If using DENY, the site's own Google Maps iframe could be confused (it's embedded IN the page, not the page being embedded in a frame).
**Why it happens:** X-Frame-Options controls whether *this site's pages* can be framed by others. DENY = no one can frame our pages. SAMEORIGIN = only pages from bessersehen.la can frame our pages.
**How to avoid:** Use SAMEORIGIN (as specified in SEC-02). Note: Google Maps iframes ON our page are
not affected by our X-Frame-Options — that header controls the response from www.google.com when
the browser fetches the iframe content, not our site's own header.
**Warning signs:** N/A — both values are valid, SAMEORIGIN is the correct choice here.

## Code Examples

### Complete .htaccess for bessersehen.la

```apache
# Source: OWASP HTTP Headers Cheat Sheet, Apache 2.4 docs, MDN
# Verified against site's actual external resources (2026-02-25)

<IfModule mod_headers.c>

    # SEC-01: HSTS — enforce HTTPS for 1 year, including subdomains
    # analytics.bessersehen.la has valid SSL (Phase 1 confirmed)
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"

    # SEC-02: Clickjacking protection — prevent framing by other sites
    Header always set X-Frame-Options "SAMEORIGIN"

    # SEC-03: MIME type sniffing protection
    Header always set X-Content-Type-Options "nosniff"

    # SEC-04: Referrer privacy — send origin on cross-site, full URL on same-site
    Header always set Referrer-Policy "strict-origin-when-cross-origin"

    # SEC-05: Content Security Policy
    # External resources confirmed on this site:
    #   - Google Fonts: fonts.googleapis.com (CSS) + fonts.gstatic.com (fonts)
    #   - Umami analytics: analytics.bessersehen.la (script + XHR beacons)
    #   - Google Maps iframe: www.google.com
    #   - Inline scripts: document.write() email obfuscation + jQuery dynamic styles
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://analytics.bessersehen.la; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https://*.googleapis.com https://*.gstatic.com https://*.ggpht.com; frame-src https://www.google.com; connect-src 'self' https://analytics.bessersehen.la; object-src 'none'"

</IfModule>
```

### Verification Commands

```bash
# After deploy: check all headers are present
curl -I https://bessersehen.la

# Expected output includes:
# strict-transport-security: max-age=31536000; includeSubDomains
# x-frame-options: SAMEORIGIN
# x-content-type-options: nosniff
# referrer-policy: strict-origin-when-cross-origin
# content-security-policy: default-src 'self'; ...

# Check specific header only
curl -s -I https://bessersehen.la | grep -i "content-security-policy"
```

### Checking mod_headers on Server (if needed via SSH)

```bash
# On the KeyHelp VPS via SSH
apache2ctl -M 2>/dev/null | grep headers
# Should output: headers_module (shared)

# If not enabled:
a2enmod headers && systemctl reload apache2
```

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| X-XSS-Protection header | Deprecated | ~2020 | Do NOT add — deprecated, removed from browsers, securityheaders.com ignores it |
| `Header set` (onsuccess) | `Header always set` | Apache 2.4 best practice | Ensures headers on error pages too |
| CSP `unsafe-inline` everywhere | Nonce/hash for inline | Ongoing | Nonce requires dynamic HTML generation (server-side) — not applicable to this static site; `unsafe-inline` is the accepted baseline |
| HSTS without includeSubDomains | HSTS with includeSubDomains | Now standard | analytics.bessersehen.la has SSL, safe to include |
| Feature-Policy | Permissions-Policy | 2021 | Feature-Policy is deprecated; Permissions-Policy is its successor — optional, not required for grade B |

**Deprecated/outdated:**
- `X-XSS-Protection`: Do not add. Deprecated across all major browsers. Creates false sense of security. securityheaders.com does not require it.
- `Feature-Policy`: Replaced by `Permissions-Policy`. Optional for grade B.
- `Expect-CT`: Deprecated (Certificate Transparency is now enforced by browsers natively).

## securityheaders.com Grading

**Grade B or better** requires the five headers specified in SEC-01 through SEC-05. The grading system
deducts points per missing header. Having all five headers active achieves at minimum grade B. Grade
A requires Permissions-Policy additionally. Grade A+ previously required no `unsafe-inline` in CSP,
but as of November 2023 securityheaders.com relaxed this — `unsafe-inline` in `style-src` no longer
prevents A+. `unsafe-inline` in `script-src` still reduces the score but does not block grade B.

**Confirmed required headers for grade B** (from securityheaders.com documentation):
1. Strict-Transport-Security
2. X-Frame-Options (or CSP frame-ancestors — equivalent)
3. X-Content-Type-Options
4. Referrer-Policy
5. Content-Security-Policy

This matches exactly SEC-01 through SEC-05.

## Open Questions

1. **mod_headers enabled on KeyHelp VPS?**
   - What we know: STATE.md confirms `.htaccess` is supported and SSL is active. Standard KeyHelp Apache setups enable mod_headers.
   - What's unclear: Not explicitly confirmed for this server.
   - Recommendation: First plan step should verify with `curl -I` after deploy. If missing headers, SSH to run `a2enmod headers`.

2. **img-src for Google Maps tiles**
   - What we know: Google Maps embed loads tile images from multiple subdomains (`*.googleapis.com`, `*.gstatic.com`, `*.ggpht.com`)
   - What's unclear: Whether the current embed URL (`maps/embed?pb=...`) versus JavaScript Maps API uses different origins.
   - Recommendation: The embed URL (static iframe) is simpler than the JS API. The `img-src` policy of `https://*.googleapis.com https://*.gstatic.com https://*.ggpht.com` covers the known tile sources. Verify by opening browser DevTools after deploy and checking for any remaining CSP violations.

3. **Existing .htaccess on server**
   - What we know: No `.htaccess` exists in `public/` (checked). No `.htaccess` was found in the repo.
   - What's unclear: Whether the KeyHelp panel has server-level `.htaccess` or VHost directives that might conflict (e.g., a pre-existing `Header set X-Frame-Options DENY` from KeyHelp defaults).
   - Recommendation: After first deploy, run `curl -I` and check for duplicate headers. If duplicated, investigate VHost config.

## Sources

### Primary (HIGH confidence)
- OWASP HTTP Headers Cheat Sheet — https://cheatsheetseries.owasp.org/cheatsheets/HTTP_Headers_Cheat_Sheet.html — exact recommended values for all 5 headers
- Apache 2.4 mod_headers docs — https://httpd.apache.org/docs/current/mod/mod_headers.html — `Header always set` syntax
- Google Maps CSP Guide (official) — https://developers.google.com/maps/documentation/javascript/content-security-policy — official CSP for Google services
- content-security-policy.com Google Fonts example — https://content-security-policy.com/examples/google-fonts/ — verified `style-src fonts.googleapis.com; font-src fonts.gstatic.com`
- content-security-policy.com Google Maps example — https://content-security-policy.com/examples/google-maps/ — verified `frame-src *.google.com; img-src data: maps.gstatic.com *.googleapis.com *.ggpht.com`

### Secondary (MEDIUM confidence)
- htaccessbook.com security headers — https://htaccessbook.com/important-security-headers/ — `<IfModule mod_headers.c>` pattern, syntax examples
- webdock.io Apache security headers guide — https://webdock.io/en/docs/how-guides/security-guides/how-to-configure-security-headers-in-nginx-and-apache — `Header always set` pattern
- scotthelme.co.uk securityheaders.com grading update (Nov 2023) — https://scotthelme.co.uk/a-balanced-approach-new-security-headers-grading-criteria/ — `unsafe-inline` in style-src allowed for A+

### Tertiary (LOW confidence)
- Site resource audit performed manually (2026-02-25) — grep over all HTML files in `./public/` — HIGH confidence since this is our own codebase

## Metadata

**Confidence breakdown:**
- Standard stack: HIGH — Apache mod_headers is the only option; syntax verified against official docs
- Architecture: HIGH — single .htaccess file, well-understood pattern
- CSP values: MEDIUM — external resources confirmed by code audit; img-src for Maps tiles may need minor adjustment post-deploy
- Pitfalls: HIGH — sourced from official docs and direct code analysis

**Research date:** 2026-02-25
**Valid until:** 2026-08-25 (headers are stable; CSP Google domains occasionally change for JS API but embed iframe is stable)
