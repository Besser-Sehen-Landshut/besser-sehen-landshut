---
phase: 02-security
plan: 01
subsystem: infra
tags: [apache, htaccess, security-headers, csp, hsts, mod_headers]

# Dependency graph
requires:
  - phase: 01-analytics
    provides: analytics.bessersehen.la reverse proxy (needed in CSP connect-src/script-src)
provides:
  - HTTP Security-Headers via public/.htaccess (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, CSP)
  - securityheaders.com Grade B+ on bessersehen.la
affects: [03-seo-schema, 04-content]

# Tech tracking
tech-stack:
  added: [Apache mod_headers, .htaccess]
  patterns: [Header always set (gilt auch fuer 4xx/5xx), IfModule Guard fuer Fehlertoleranz]

key-files:
  created: [public/.htaccess]
  modified: []

key-decisions:
  - "Header always set statt Header set: gilt auch fuer Error-Responses (4xx/5xx)"
  - "unsafe-inline in script-src und style-src behalten: 69+ inline document.write()-Calls und jQuery-Dynamic-Styles machen Refactoring in dieser Phase nicht praktikabel"
  - "analytics.bessersehen.la explizit in script-src UND connect-src: 'self' deckt keine Subdomains ab"
  - "Kein HSTS preload fuer ersten Deploy: Preload-List-Submission ist irreversibel, max-age=31536000 + includeSubDomains genuegt fuer Grade B"
  - "Kein X-XSS-Protection: deprecated seit 2020, schadet mehr als nuetzt (veraltete Browser-Verhalten werden aktiviert)"

patterns-established:
  - "IfModule mod_headers.c Guard: jede Header-Direktive im Guard — verhindert 500-Fehler falls Modul deaktiviert"
  - "Header always set Reihenfolge: HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, CSP"

requirements-completed: [SEC-01, SEC-02, SEC-03, SEC-04, SEC-05]

# Metrics
duration: ~15min (exkl. human checkpoint wartezeit)
completed: 2026-03-03
---

# Phase 2 Plan 01: Security-Headers Summary

**5 HTTP Security-Headers via Apache .htaccess live auf bessersehen.la — HSTS, Clickjacking-Schutz, MIME-Sniffing-Schutz, Referrer-Policy und massgeschneiderte CSP mit Google Fonts/Maps und Umami Analytics — securityheaders.com Grade B+**

## Performance

- **Duration:** ~15 min (exkl. human checkpoint wartezeit)
- **Started:** 2026-02-25 (Plan erstellt), ausgefuehrt 2026-03-03
- **Completed:** 2026-03-03T07:35Z
- **Tasks:** 3 (inkl. human checkpoint)
- **Files modified:** 1

## Accomplishments

- `.htaccess` mit `IfModule mod_headers.c` Block erstellt — alle 5 Security-Headers in einer Datei
- Deployed via `git push` und GitHub Actions rsync-Workflow auf bessersehen.la live
- securityheaders.com Grade B+ bestaetigt (human checkpoint approved)
- CSP massgeschneidert auf alle externen Ressourcen der Site: Google Fonts, Google Maps iframe, Umami Analytics — keine CSP-Violations
- Google Fonts, Google Maps Embed, E-Mail-Obfuskierung per document.write() und Umami-Tracking weiterhin funktional

## Task Commits

Alle Tasks in einem Commit zusammengefasst (Tasks 1+2 zeitlich eng verbunden):

1. **Task 1: .htaccess mit allen 5 Security-Headers erstellen** - `76b7392` (feat)
2. **Task 2: Deployen und Headers live verifizieren** - `76b7392` (feat)
3. **Task 3: securityheaders.com Grade und Seitenfunktion pruefen** - Human checkpoint approved — kein separater Commit

**Plan metadata:** (docs commit folgt)

## Files Created/Modified

- `public/.htaccess` - IfModule mod_headers.c Block mit 5 `Header always set` Direktiven; CSP mit 8 Policies abgestimmt auf alle externen Ressourcen der Site

## Decisions Made

- **unsafe-inline behalten:** 69+ inline `document.write()`-Calls und jQuery-erzeugte inline-Styles machen eine Umstellung auf Nonces/Hashes in Phase 2 zu aufwändig. CSP-Schutz via andere Direktiven (frame-src, object-src, connect-src) trotzdem wirksam.
- **HSTS ohne preload:** Erster Deploy — `max-age=31536000; includeSubDomains` genuegt fuer Grade B. Preload-List ist irreversibel und bedarf separater Entscheidung.
- **analytics.bessersehen.la explizit adressiert:** `'self'` wuerde Subdomain blockieren — explizite Aufnahme in `script-src` und `connect-src` sichert Umami-Tracking.
- **Kein X-XSS-Protection:** Per OWASP und MDN deprecated seit 2020 — Aufnahme wuerde schlechtere Ergebnisse bei modernen Browsern produzieren.

## Deviations from Plan

None - plan executed exactly as written.

## Issues Encountered

None — mod_headers war auf dem Apache-Server aktiv, keine doppelten Header durch KeyHelp, Umami-CSP-Integration funktionierte auf Anhieb.

## User Setup Required

None - no external service configuration required.

## Next Phase Readiness

- Security-Headers-Fundament ist gelegt — kuenftige Phasen koennen CSP bei Bedarf erweitern
- Phase 3 (SEO-Schema): Optician-Schema auf index.html vervollstaendigen (address, telephone, openingHoursSpecification, geo, sameAs)
- Phase 4 (Content): Leistungsseiten auf 800+ Woerter ausbauen, team.html Qualifikationen ergaenzen
- Blocker: KeyHelp-Warnung bleibt — Reverse-Proxy-Direktiven fuer analytics.bessersehen.la direkt via SSH eingetragen, koennen bei KeyHelp SSL-Erneuerung ueberschrieben werden

---

## Self-Check

- [x] `public/.htaccess` exists and committed in `76b7392`
- [x] All 5 headers confirmed live via `curl -sI https://bessersehen.la`
- [x] Human checkpoint approved (Grade B+, site loads correctly)
- [x] SUMMARY.md created at `.planning/phases/02-security/02-01-SUMMARY.md`

## Self-Check: PASSED

*Phase: 02-security*
*Completed: 2026-03-03*
