---
phase: 03-schema
plan: 01
subsystem: seo
tags: [schema.org, structured-data, json-ld, knowledge-panel, local-seo]

# Dependency graph
requires:
  - phase: 01-analytics
    provides: GA-cleanup, Umami-Tracking aktiv
  - phase: 02-security
    provides: Security-Headers, HTTPS-Grundlage
provides:
  - Vollstaendiges Optician-Schema auf index.html (openingHoursSpecification Array, closes=16:00, sameAs mit Maps CID, areaServed City-Objekte, description)
  - WebSite-Schema auf index.html (inLanguage=de-DE, kein SearchAction)
affects: [03-02, 03-03, 03-04, google-knowledge-panel, schema-validation]

# Tech tracking
tech-stack:
  added: []
  patterns:
    - "Optician-Schema als vollstaendiger Business-Block fuer Google Knowledge Panel"
    - "openingHoursSpecification immer als Array (auch wenn nur ein Eintrag)"
    - "areaServed als City-Objekt-Array statt String fuer maschinenlesbare Einzugsgebiete"
    - "WebSite-Schema als separater Block nach dem primaren Business-Schema"

key-files:
  created: []
  modified:
    - public/index.html

key-decisions:
  - "openingHoursSpecification als Array (auch bei einem Eintrag) — Schema.org-konform, JSON-LD best practice"
  - "closes=16:00 korrigiert (war 18:00 — inhaltlich falsch)"
  - "Google Maps CID Decimal 13745904768855660490 in sameAs — ermoeglicht Google Knowledge Panel Verknuepfung"
  - "areaServed als City-Objekte statt String — maschinenlesbar, fuer Local-SEO in Ergolding/Rottenburg wichtig"
  - "Kein SearchAction in WebSite-Schema — statische Seite, kein Search-Endpoint verfuegbar"
  - "description in Optician-Schema als Standard-Feld (nicht proprietaer) mit Terminvereinbarungs-Hinweis"

patterns-established:
  - "Schema-Bloecke in index.html: Optician-Block zuerst, dann WebSite-Block — Reihenfolge beibehalten"

requirements-completed: [SCHEMA-01, SCHEMA-02, SCHEMA-05]

# Metrics
duration: 1min
completed: 2026-03-03
---

# Phase 3 Plan 01: Schema — Optician + WebSite Summary

**Optician-Schema um openingHoursSpecification (Array, closes=16:00), Google Maps CID, areaServed (3 City-Objekte) und description erweitert; separater WebSite-Schema-Block (inLanguage=de-DE) hinzugefuegt**

## Performance

- **Duration:** ~1 min
- **Started:** 2026-03-03T08:15:28Z
- **Completed:** 2026-03-03T08:16:19Z
- **Tasks:** 2
- **Files modified:** 1

## Accomplishments
- Optician-Schema jetzt vollstaendig fuer Google Knowledge Panel: Oeffnungszeiten (korrekte Array-Form, korrekte Schliesszeit 16:00), Google Maps CID-Link, 3 Einzugsgebiete als maschinenlesbare City-Objekte
- Schliesszeit-Fehler "18:00" zu "16:00" korrigiert (waere in Knowledge Panel sichtbar gewesen)
- WebSite-Schema als separater Block hinzugefuegt: signalisiert Google Sprache (de-DE) und Identitaet der Website
- Beide Schema-Bloecke valides JSON (via Python-Assertion geprueft)

## Task Commits

Jeder Task wurde einzeln committed:

1. **Task 1: Optician-Schema erweitern** - `a78692c` (feat)
2. **Task 2: WebSite-Schema hinzufuegen** - `a817dee` (feat)

**Plan metadata:** wird nach SUMMARY erstellt (docs-commit)

## Files Created/Modified
- `public/index.html` - Optician-Schema erweitert (openingHoursSpecification Array, sameAs+Maps CID, areaServed City-Array, description), neuer WebSite-Schema-Block

## Decisions Made
- openingHoursSpecification als Array — Schema.org best practice, auch wenn nur ein Objekt enthalten ist
- closes=16:00 aus bestehender Betriebswissen-Quelle korrigiert (alter Wert 18:00 war inhaltlich falsch)
- Google Maps CID als Decimal-Zahl (13745904768855660490) in sameAs — Verbindung zum Google My Business Eintrag
- areaServed mit 3 City-Objekten (Landshut, Ergolding, Rottenburg an der Laaber) — Einzugsgebiet maschinenlesbar
- KEIN SearchAction in WebSite-Schema — statische Seite ohne echte Suche, SearchAction koennte Google-Penalty verursachen

## Deviations from Plan

None - plan executed exactly as written.

## Issues Encountered

None.

## User Setup Required

None - keine externen Services konfiguriert, nur statisches HTML geaendert.

## Next Phase Readiness
- Plan 03-02 (Service-Schema auf Leistungsseiten) kann beginnen
- Plan 03-03 (Person-Schema auf team.html) kann beginnen
- Optician-Schema ist Referenzpunkt fuer organization-Referenzen in nachfolgenden Schema-Bloecken

---
*Phase: 03-schema*
*Completed: 2026-03-03*

## Self-Check: PASSED

- FOUND: public/index.html
- FOUND: commit a78692c (feat: Optician-Schema erweitern)
- FOUND: commit a817dee (feat: WebSite-Schema hinzufuegen)
- FOUND: .planning/phases/03-schema/03-01-SUMMARY.md
