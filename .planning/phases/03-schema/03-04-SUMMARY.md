---
phase: 03-schema
plan: 04
subsystem: seo
tags: [schema-org, json-ld, structured-data, deployment, github-actions]

# Dependency graph
requires:
  - phase: 03-schema plan 01
    provides: Optician-Schema + WebSite-Schema auf index.html
  - phase: 03-schema plan 02
    provides: Service-Schema auf 4 Leistungsseiten
  - phase: 03-schema plan 03
    provides: Person-Schema auf team.html
provides:
  - Alle 6 Schema-Dateien live auf bessersehen.la via GitHub Actions deployed
  - 12 JSON-LD-Bloecke validiert (0 Fehler) — Optician, WebSite, Service x4, Person-Array
  - Google Rich Results Test bestätigt valides LocalBusiness-Schema ohne Fehler
  - Phase 3 Schema-Markup vollständig abgeschlossen
affects: [04-content, seo-phase]

# Tech tracking
tech-stack:
  added: []
  patterns:
    - "GitHub Actions rsync-Deploy: git push triggert automatischen Deploy via SSH (17s)"
    - "JSON-LD Syntax-Validierung: Python-Skript extrahiert und parsed alle <script type=application/ld+json> Bloecke"

key-files:
  created: []
  modified:
    - public/index.html
    - public/leistungen-kontaktlinsen.html
    - public/leistungen-nachtlinsen.html
    - public/leistungen-beratung-kurzsichtigkeit.html
    - public/leistungen-visuelle-reha.html
    - public/team.html

key-decisions:
  - "Deployment via git push + GitHub Actions rsync (17s) — kein manueller Upload noetig"
  - "Lokal-Validierung aller 12 JSON-LD-Bloecke vor Checkpoint sichergestellt"
  - "Human-Verification via Rich Results Test + bessersehen.la Browser-Check bestätigt"

patterns-established:
  - "Deploy-Verifikation: gh run list + curl-Checks nach jedem Schema-Commit"
  - "JSON-LD-Validierung: Python-Skript extrahiert alle Bloecke und prüft JSON-Syntax lokal"

requirements-completed: [SCHEMA-01, SCHEMA-02, SCHEMA-03, SCHEMA-04, SCHEMA-05]

# Metrics
duration: ~20min (incl. human verification)
completed: 2026-03-03
---

# Phase 3 Plan 04: Deploy & Validate Summary

**Alle 6 Schema-Dateien via GitHub Actions deployed, 12 JSON-LD-Bloecke validiert — Optician/WebSite/Service/Person-Schema live auf bessersehen.la, Google Rich Results Test ohne Fehler bestätigt**

## Performance

- **Duration:** ~20 min (incl. human verification checkpoint)
- **Started:** 2026-03-03T08:20:00Z
- **Completed:** 2026-03-03T08:25:12Z
- **Tasks:** 3 (2 auto + 1 human-verify checkpoint)
- **Files modified:** 6

## Accomplishments

- Alle 6 geänderten HTML-Dateien committed und via GitHub Actions (rsync SSH, 17s) live deployed
- 12 JSON-LD-Bloecke lokal validiert — 0 Python-Fehler (index.html x2, 4x Leistungsseiten, team.html x1 Array)
- Google Rich Results Test: valides LocalBusiness-Schema auf bessersehen.la ohne Fehler
- bessersehen.la: HTTP 200, Ladezeit 0.12s, keine CSP-Fehler durch Schema-Änderungen
- analytics.bessersehen.la: HTTP 200 (Umami unveraendert erreichbar)
- Phase 3 Schema-Markup vollständig: SCHEMA-01 bis SCHEMA-05 alle erfüllt

## Task Commits

Each task was committed atomically:

1. **Task 1: Alle 6 Schema-Dateien deployen** - `ce6b1aa` (feat — Teil des 03-03 Batch-Commits)
2. **Task 2: JSON-LD Syntax aller 6 Dateien validieren** - `ce6b1aa` (12 Bloecke, 0 Fehler)
3. **Task 3: Google Rich Results Test und Site-Funktionskontrolle** - Human-verified (approved)

**Plan metadata:** (this commit — docs: complete 03-04 plan)

## Files Created/Modified

- `public/index.html` - Optician-Schema + WebSite-Schema live (openingHoursSpecification, sameAs CID, areaServed 3 Cities)
- `public/leistungen-kontaktlinsen.html` - Service-Schema mit serviceType + areaServed live
- `public/leistungen-nachtlinsen.html` - Service-Schema mit serviceType + areaServed live
- `public/leistungen-beratung-kurzsichtigkeit.html` - Service-Schema mit serviceType + areaServed live
- `public/leistungen-visuelle-reha.html` - Service-Schema mit serviceType + areaServed live
- `public/team.html` - Person-Schema Array mit 7 Teammitgliedern live

## Decisions Made

- Deployment via git push + GitHub Actions rsync (17s Deploy-Zeit) — kein manueller Upload noetig, alles automatisiert
- Lokal-Validierung aller JSON-LD-Bloecke via Python-Skript vor dem Checkpoint-Stopp sichergestellt
- Human-Verification bestätigt: Rich Results Test ohne Fehler, Site laedt fehlerfrei

## Deviations from Plan

None — plan executed exactly as written. Tasks 1 und 2 auto-completed, Task 3 Checkpoint korrekt approved.

## Issues Encountered

- 1 JS console error (null.setAttribute) bei Revolution Slider — bekanntes Altproblem aus vorherigen Phasen, kein Schema-Fehler, keine Auswirkung auf Schema-Validierung.

## User Setup Required

None — no external service configuration required.

## Next Phase Readiness

- Phase 3 Schema-Markup vollständig abgeschlossen (alle 5 SCHEMA-Anforderungen erfüllt)
- Phase 4 Content bereit: Alle Leistungsseiten unter Mindest-Wortanzahl (Ziel 800+ Wörter je Seite)
- Offene Content-Aufgaben: leistungen-nachtlinsen (~340 Wörter), leistungen-beratung-kurzsichtigkeit (~240), leistungen-visuelle-reha (~220), leistungen-kontaktlinsen (~430), team.html (~80 Wörter)
- Bekanntes Problem: Fehlerhaftes alt-Attribut bei Jennifer Huegel (alt="Maria Schueler") — für Phase 4 Content vorgemerkt

---
*Phase: 03-schema*
*Completed: 2026-03-03*
