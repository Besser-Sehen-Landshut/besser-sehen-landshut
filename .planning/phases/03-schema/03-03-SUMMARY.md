---
phase: 03-schema
plan: "03"
subsystem: seo
tags: [schema.org, json-ld, person-schema, e-e-a-t, structured-data]

# Dependency graph
requires:
  - phase: 03-schema-01
    provides: Optician-Schema auf index.html (Organization-Referenz)
provides:
  - Person-Schema mit 7 Teammitgliedern auf team.html (name, jobTitle, image, worksFor)
affects: [03-schema-04, content-phase]

# Tech tracking
tech-stack:
  added: []
  patterns: [JSON-Array fuer mehrere Personen im selben Script-Block, @context pro Array-Item]

key-files:
  created: []
  modified:
    - public/team.html

key-decisions:
  - "JSON-Array mit @context pro Item statt @graph — konsistent mit Plan-Vorgabe"
  - "worksFor @type Organization (nicht Optician) — allgemeiner fuer Arbeitgeber-Kontext"
  - "Jennifer Huegel korrekt im Schema, trotz fehlerhaftem alt-Attribut 'Maria Schueler' im HTML"

patterns-established:
  - "Person-Schema: @context pro Item bei Array-Form (keine @graph-Wrapper)"
  - "image-URLs: immer absolut mit https://bessersehen.la/ Prefix und .webp Endung"

requirements-completed: [SCHEMA-04]

# Metrics
duration: 5min
completed: 2026-03-03
---

# Phase 03 Plan 03: Person-Schema Summary

**Person-Schema als JSON-Array mit 7 Teammitgliedern in team.html eingefuegt — staerkt E-E-A-T-Signale fuer Google im Gesundheitsbereich**

## Performance

- **Duration:** 5 min
- **Started:** 2026-03-03T08:15:33Z
- **Completed:** 2026-03-03T08:20:00Z
- **Tasks:** 1
- **Files modified:** 1

## Accomplishments
- Person-Schema-Block mit 7 Personen als gueltiges JSON-Array in team.html
- Alle Personen mit name, jobTitle, absoluter WebP-Bild-URL und worksFor-Organization
- Jennifer Huegel korrekt im Schema benannt (unabhaengig vom fehlerhaften HTML-alt-Attribut "Maria Schueler")
- Block positioniert nach BreadcrumbList-Schema, vor CSS-Links — saubere Trennung der Schema-Bloecke

## Task Commits

Jeder Task wurde atomar committet:

1. **Task 1: Person-Schema fuer alle 7 Teammitglieder** - `746d2e8` (feat)

**Plan metadata:** wird in diesem Commit erfasst

## Files Created/Modified
- `public/team.html` - Neuer `<script type="application/ld+json">`-Block mit 7 Person-Objekten nach BreadcrumbList-Block eingefuegt

## Decisions Made
- JSON-Array mit `@context` pro Item: Plan sieht Array-Form vor (nicht `@graph`), jedes Item traegt eigenen `@context` — standard-konform und Google-kompatibel
- `worksFor` mit `@type: Organization` statt Optician: allgemeiner Typ fuer Arbeitgeber-Referenz, Optician-Typ wuerde zirkulaere Verschachtelung implizieren
- Jennifer Huegel: Schema-Name korrekt, obwohl `<img alt="Maria Schueler">` im HTML ein bekannter Fehler ist — Schema-Daten sind die autoritaetiven Daten

## Deviations from Plan

None - plan executed exactly as written.

## Issues Encountered
None

## User Setup Required
None - no external service configuration required.

## Next Phase Readiness
- Person-Schema komplett, team.html bereit fuer Deployment
- Phase 03-04 (WebSite-Schema + areaServed-Ergaenzungen) kann direkt starten
- Fehlerhaftes alt-Attribut bei Jennifer Huegel (team-jenny-huegel.jpg hat alt="Maria Schueler") ist bekannt und sollte in einer Content-Phase korrigiert werden — kein Schema-Blocker

---
*Phase: 03-schema*
*Completed: 2026-03-03*
