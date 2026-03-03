---
phase: 01-analytics
verified: 2026-02-25T00:00:00Z
status: human_needed
score: 3/4 success criteria verified (4th requires human test)
re_verification: false
human_verification:
  - test: "Open https://bessersehen.la in a browser (incognito or fresh profile without umami.disabled localStorage key set), then immediately check the Umami Realtime dashboard at https://analytics.bessersehen.la"
    expected: "The page visit appears in the Realtime view within 60 seconds, attributed to the bessersehen.la website"
    why_human: "End-to-end tracking confirmation requires a live browser sending a beacon to the Umami API. Cannot be verified via curl — the Umami script fires a JavaScript beacon after page load, not a server-side request."
---

# Phase 1: Analytics Verification Report

**Phase Goal:** Install Umami Analytics and wire tracking into all HTML pages so that real visitor data is being collected at analytics.bessersehen.la
**Verified:** 2026-02-25
**Status:** human_needed — all automated checks pass; one item requires a live browser test
**Re-verification:** No — initial verification

## Goal Achievement

### Observable Truths (from ROADMAP.md Success Criteria)

| # | Truth | Status | Evidence |
|---|-------|--------|---------|
| 1 | Umami dashboard is accessible at analytics.bessersehen.la and shows real-time data | VERIFIED | `curl -sf https://analytics.bessersehen.la/api/heartbeat` returns `{"ok":true}` live; Next.js Umami app served at `/` |
| 2 | All 10 HTML files contain the Umami tracking script in `<head>` | VERIFIED | `grep -c analytics.bessersehen.la/script.js` returns exactly `1` for all 10 files; script placed immediately before `</head>` confirmed |
| 3 | A test visit on bessersehen.la appears in Umami within 60 seconds | HUMAN NEEDED | Infrastructure is fully wired: live production HTML serves correct script tag, `/script.js` returns HTTP 200, UUID correct, `data-domains` set. Cannot verify browser-fired beacon programmatically. |
| 4 | No cookie banner is present or required on the website | VERIFIED | Umami is cookiefree by design. No cookie consent markup or code found in any HTML file. No `localStorage` / cookie code wired to a banner. |

**Score:** 3/4 success criteria verified automatically (SC-3 needs human)

### Required Artifacts

| Artifact | Expected | Status | Details |
|----------|----------|--------|---------|
| `/opt/umami/docker-compose.yml` (server) | Umami + PostgreSQL container definitions with port `127.0.0.1:3000:3000` | VERIFIED (via reference copy) | Local reference at `.planning/phases/01-analytics/server/docker-compose.yml` contains exact port binding `127.0.0.1:3000:3000`. Server-side file not in git (correct — secrets excluded). |
| `/opt/umami/.env` (server) | APP_SECRET and POSTGRES_PASSWORD secrets | NOT VERIFIABLE LOCALLY | By design — secrets file on server only, chmod 600, not in git. SUMMARY confirms it was created. |
| `public/index.html` | Umami tracking script before `</head>` | VERIFIED | Line 368-373: full `<script defer src="https://analytics.bessersehen.la/script.js" data-website-id="a8d3de83-ea5f-429d-9829-d639ad9cdad1" data-domains="bessersehen.la">` at line 368; `</head>` at line 375. |
| `public/datenschutz.html` | Tracking script (legal pages also tracked) | VERIFIED | Line 99: `src="https://analytics.bessersehen.la/script.js"` with correct UUID and data-domains |
| `public/impressum.html` | Tracking script (legal pages also tracked) | VERIFIED | Line 99: `src="https://analytics.bessersehen.la/script.js"` with correct UUID and data-domains |
| All 8 other HTML files | Tracking script present | VERIFIED | `grep -c` confirms count of 1 in each: leistungen.html, leistungen-kontaktlinsen.html, leistungen-nachtlinsen.html, leistungen-beratung-kurzsichtigkeit.html, leistungen-visuelle-reha.html, team.html, kontakt.html |

### Key Link Verification

| From | To | Via | Status | Details |
|------|----|-----|--------|---------|
| All 10 HTML files (script tag) | `https://analytics.bessersehen.la/script.js` | `src` attribute with `defer` | WIRED | Confirmed via grep: all 10 files have exactly one `src="https://analytics.bessersehen.la/script.js"` match; live production HTML verified with `curl https://bessersehen.la/` |
| Script tag | `bessersehen.la` production domain | `data-domains="bessersehen.la"` attribute | WIRED | Confirmed via grep: all 10 files have exactly one `data-domains="bessersehen.la"` match |
| Script tag | Umami website UUID | `data-website-id` attribute | WIRED | UUID `a8d3de83-ea5f-429d-9829-d639ad9cdad1` confirmed consistent across all 10 files (only one unique value in `sort -u` output) |
| Apache VHost for `analytics.bessersehen.la` | `http://127.0.0.1:3000` | ProxyPass directive (SSH-configured) | WIRED | `curl https://analytics.bessersehen.la/api/heartbeat` returns `{"ok":true}` live — reverse proxy is active |
| Docker umami container | Docker db container | `DATABASE_URL: postgresql://umami:${POSTGRES_PASSWORD}@db:5432/umami` | WIRED | Pattern confirmed in reference copy `.planning/phases/01-analytics/server/docker-compose.yml`; `depends_on: db: condition: service_healthy` gate present |
| Port 3000 (security) | localhost only (not internet) | `127.0.0.1:3000:3000` binding | VERIFIED | `curl --connect-timeout 5 http://195.201.220.6:3000/` → `curl: (28) Failed to connect ... Timeout was reached` — port is NOT publicly accessible |

### Requirements Coverage

| Requirement | Source Plan | Description | Status | Evidence |
|-------------|------------|-------------|--------|---------|
| ANAL-01 | 01-01-PLAN.md | Umami Analytics auf Pollux-Server via Docker installiert und erreichbar | SATISFIED | `/api/heartbeat` returns `{"ok":true}`; `/script.js` returns HTTP 200; port 3000 not public; both Docker containers healthy per SUMMARY |
| ANAL-02 | 01-02-PLAN.md | Umami-Tracking-Script auf allen 10 HTML-Seiten eingebunden | SATISFIED | All 10 HTML files contain exactly one Umami script tag with correct UUID and `data-domains`; commit `4a58e78` confirmed in git log |
| ANAL-03 | 01-02-PLAN.md | Besuche auf bessersehen.la werden korrekt in Umami erfasst | PARTIALLY VERIFIABLE | Infrastructure verified: live HTML serves script, script.js accessible, UUID correct. End-to-end beacon delivery requires human browser test (see Human Verification section) |

No orphaned requirements: ANAL-01, ANAL-02, ANAL-03 are the only Phase 1 requirements in REQUIREMENTS.md, and all are claimed by the plans.

### Anti-Patterns Found

| File | Line | Pattern | Severity | Impact |
|------|------|---------|----------|--------|
| — | — | — | — | No anti-patterns found |

Checked all 10 HTML files for: TODO/FIXME/PLACEHOLDER comments, GA legacy code (`google-analytics`, `UA-185735048`, `gtag.js`, `googletagmanager`), empty implementations. All clean.

### Human Verification Required

#### 1. End-to-End Tracking Confirmation

**Test:** Open https://bessersehen.la in a fresh browser session (incognito tab, or a different browser than the one with `umami.disabled=1` set in localStorage from the Umami setup). Navigate one or two pages. Then open https://analytics.bessersehen.la, log in as admin, and check the Realtime view.

**Expected:** The visit appears in the Realtime dashboard within 60 seconds, attributed to the "Besser Sehen Landshut" website (UUID `a8d3de83-ea5f-429d-9829-d639ad9cdad1`).

**Why human:** The Umami tracking script (`/script.js`) fires a JavaScript `fetch()` beacon after page load — this is client-side only. A `curl` request does not execute JavaScript. There is no server-side log verifiable programmatically without SSH access to Umami's PostgreSQL DB. The infrastructure is fully verified (script wired, endpoint live, UUID correct), but beacon delivery confirmation requires a real browser.

### Gaps Summary

No gaps found. All automated checks pass.

The only unresolved item (SC-3 / ANAL-03 end-to-end tracking confirmation) is structurally complete but requires a human to confirm the browser-to-Umami data flow, which cannot be simulated programmatically. This is expected for analytics tracking verification.

### Additional Notes

- **Commit `4a58e78`** exists in git and shows all 10 HTML files modified (+83/-129 lines)
- **Branch state:** `main` is up to date with `origin/main` — changes were pushed and deployed
- **Live production confirmation:** `curl https://bessersehen.la/` confirms the Umami script tag is present in the live site HTML
- **Reverse proxy deviation:** Configured via SSH rather than KeyHelp UI — functionally identical result, noted as a risk if KeyHelp regenerates VHost files on SSL renewal
- **Umami admin password:** Changed from default `umami` via API on 2026-03-03 (documented in SUMMARY)

---
_Verified: 2026-02-25_
_Verifier: Claude (gsd-verifier)_
