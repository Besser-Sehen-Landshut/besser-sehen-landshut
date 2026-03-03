---
phase: 03-schema
plan: 02
subsystem: seo
tags: [schema.org, structured-data, service-schema, local-seo]

# Dependency graph
requires:
  - phase: 03-schema
    provides: Bestehende Service-Schema-Grundstruktur auf 4 Leistungsseiten
provides:
  - Service-Schemas mit serviceType, image und areaServed City-Array auf allen 4 Leistungsseiten
affects: [03-03, 03-04, seo, schema-validation]

# Tech tracking
tech-stack:
  added: []
  patterns: [areaServed als City-Array statt String, serviceType als Freitext-Kategorisierung, image als absolute OG-Bild-URL]

key-files:
  created: []
  modified:
    - public/leistungen-kontaktlinsen.html
    - public/leistungen-nachtlinsen.html
    - public/leistungen-beratung-kurzsichtigkeit.html
    - public/leistungen-visuelle-reha.html

key-decisions:
  - "Bestehende descriptions aus Seiteninhalt beibehalten (nicht durch Plan-Vorschlaege ersetzt)"
  - "areaServed City-Array mit Landshut, Ergolding, Rottenburg an der Laaber — konsistent auf allen 4 Seiten"
  - "image-URL = OG-Image der jeweiligen Seite (bereits verifiziert in RESEARCH.md)"

patterns-established:
  - "Service-Schema City-Array: [{\"@type\": \"City\", \"name\": \"Landshut\"}, ...]"
  - "serviceType = Freitext-Label der Dienstleistung (kein Schema.org-Enum)"
  - "image = absolute URL zum OG-Bild der Seite"

requirements-completed: [SCHEMA-03, SCHEMA-05]

# Metrics
duration: 1min
completed: 2026-03-03
---

# Phase 03 Plan 02: Service-Schemas Leistungsseiten Summary

**serviceType, image und areaServed City-Array (Landshut, Ergolding, Rottenburg) auf 4 Leistungsseiten — Service-Schemas fuer Google Rich Results vollstaendig**

## Performance

- **Duration:** 1 min
- **Started:** 2026-03-03T08:15:30Z
- **Completed:** 2026-03-03T08:16:38Z
- **Tasks:** 1
- **Files modified:** 4

## Accomplishments
- serviceType ergaenzt auf allen 4 Seiten: Kontaktlinsenanpassung, Orthokeratologie, Myopie-Management, Visuelle Rehabilitation
- image (absolute URL) ergaenzt: je das OG-Image der Seite (https://bessersehen.la/bilder/...)
- areaServed von String "Landshut" auf City-Array mit 3 Staedten umgestellt (12 City-Objekte total)
- Alle 4 Service-Schema-Bloecke valides JSON, Python-Verifikation besteht

## Task Commits

Each task was committed atomically:

1. **Task 1: Service-Schemas auf allen 4 Leistungsseiten erweitern** - `7a54272` (feat)

**Plan metadata:** (wird in naechstem Commit erstellt)

## Files Created/Modified
- `public/leistungen-kontaktlinsen.html` - serviceType=Kontaktlinsenanpassung, image, areaServed City-Array
- `public/leistungen-nachtlinsen.html` - serviceType=Orthokeratologie, image, areaServed City-Array
- `public/leistungen-beratung-kurzsichtigkeit.html` - serviceType=Myopie-Management, image, areaServed City-Array
- `public/leistungen-visuelle-reha.html` - serviceType=Visuelle Rehabilitation, image, areaServed City-Array

## Decisions Made
- Bestehende descriptions aus Seiteninhalt beibehalten — die bereits im HTML vorhandenen Texte sind praeziser als die Plan-Vorschlaege (z.B. "Speziell angepasste Nachtlinsen zum Tragen beim Schlafen" statt Plan-Vorschlag)
- areaServed City-Array konsistent auf allen 4 Seiten: Landshut, Ergolding, Rottenburg an der Laaber
- image = OG-Image der jeweiligen Seite (bereits per RESEARCH.md verifiziert)

## Deviations from Plan

None - plan executed exactly as written.

## Issues Encountered
None.

## User Setup Required
None - no external service configuration required.

## Next Phase Readiness
- Service-Schemas auf allen 4 Leistungsseiten vollstaendig (serviceType, image, areaServed City-Array)
- Bereit fuer 03-03 (Person-Schema auf team.html) und 03-04 (WebSite-Schema auf index.html)
- Alle Aenderungen noch nicht deployed — Deployment-Phase steht separat aus

---
*Phase: 03-schema*
*Completed: 2026-03-03*

## Self-Check: PASSED

- FOUND: public/leistungen-kontaktlinsen.html
- FOUND: public/leistungen-nachtlinsen.html
- FOUND: public/leistungen-beratung-kurzsichtigkeit.html
- FOUND: public/leistungen-visuelle-reha.html
- FOUND: .planning/phases/03-schema/03-02-SUMMARY.md
- FOUND: commit 7a54272
