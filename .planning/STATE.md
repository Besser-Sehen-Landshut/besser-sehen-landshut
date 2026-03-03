---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
status: active
last_updated: "2026-03-03T08:20:00.000Z"
progress:
  total_phases: 4
  completed_phases: 2
  total_plans: 7
  completed_plans: 6
---

# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-03-03)

**Core value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.
**Current focus:** Phase 3 – SEO Schema

## Current Position

Phase: 3 of 4 (Schema)
Plan: 3 of 4 in current phase — COMPLETE
Status: Phase 3 active — 03-03 complete
Last activity: 2026-03-03 — 03-03 complete: Person-Schema mit 7 Teammitgliedern auf team.html (E-E-A-T)

Progress: [█████████████████████░░░] 65% (Phase 1 + Phase 2 + 03-01 + 03-02 + 03-03 complete)

## Performance Metrics

**Velocity:**
- Total plans completed: 6 (01-01, 01-02, 02-01, 03-01, 03-02, 03-03)
- Average duration: ~10 min (schema plans fast, analytics plans had human setup)
- Total execution time: ~97 min (01-01: 64 min incl. human; 01-02: ~11 min; 02-01: ~15 min; 03-01: ~1 min; 03-02: ~1 min; 03-03: ~5 min)

**By Phase:**

| Phase | Plans | Total | Avg/Plan |
|-------|-------|-------|----------|
| 01-analytics | 2/2 | ~75 min | ~38 min |
| 02-security | 1/1 | ~15 min | ~15 min |
| 03-schema | 3/4 | ~7 min | ~2 min |

**Recent Trend:**
- Last 5 plans: 02-01 (15 min), 03-01 (1 min), 03-02 (1 min), 03-03 (5 min)
- Trend: On track

*Updated after each plan completion*

## Accumulated Context

### Decisions

Decisions are logged in PROJECT.md Key Decisions table.
Recent decisions affecting current work:

- Umami statt GA4: DSGVO-konform, self-hosted, kein Cookie-Banner
- rsync-Deploy via GitHub Actions auf Pollux VPS bereits aktiv
- .htaccess für Security-Headers nutzbar (KeyHelp-Setup unterstützt es, SSL aktiv)
- Umami Docker port: 127.0.0.1:3000:3000 (nicht 3000:3000) — verhindert Docker-Firewall-Bypass
- POSTGRES_PASSWORD: openssl rand -hex 16 (nur hex, keine Sonderzeichen die PostgreSQL-URL brechen)
- Umami website UUID für bessersehen.la: a8d3de83-ea5f-429d-9829-d639ad9cdad1 (via API registriert)
- Umami Admin-Passwort geändert (nicht mehr "umami") — neues PW in 01-02-SUMMARY.md
- Dead GA code war in 2 Stellen pro Datei: im <head>-Kommentar UND im <body>-Template-Block
- Reverse Proxy für analytics.bessersehen.la via SSH in Apache VHost eingetragen (KeyHelp-UI hat kein Feld dafür)
- unsafe-inline in CSP behalten: 69+ document.write()-Calls und jQuery inline-Styles — Refactoring in Phase 2 nicht praktikabel
- HSTS ohne preload deployed: max-age=31536000 + includeSubDomains genuegt fuer Grade B; Preload-List ist irreversibel
- analytics.bessersehen.la explizit in script-src + connect-src: 'self' deckt Subdomains nicht ab
- [Phase 03-schema]: openingHoursSpecification als Array (auch bei einem Eintrag) — Schema.org-konform
- [Phase 03-schema]: closes=16:00 korrigiert (war 18:00 — inhaltlich falsch)
- [Phase 03-schema]: Kein SearchAction in WebSite-Schema — statische Seite, kein Search-Endpoint
- [03-02]: Bestehende descriptions beibehalten (praeziser als Plan-Vorschlaege); areaServed City-Array mit Landshut, Ergolding, Rottenburg auf allen 4 Leistungsseiten
- [03-03]: Person-Schema JSON-Array mit @context pro Item (keine @graph-Wrapper) — standard-konform
- [03-03]: worksFor @type Organization (nicht Optician) — allgemeiner fuer Arbeitgeber-Kontext
- [03-03]: Jennifer Huegel im Schema korrekt benannt trotz fehlerh. alt-Attr "Maria Schueler" im HTML

### Pending Todos

None yet.

### Blockers/Concerns

- KeyHelp-Warnung: Reverse-Proxy-Direktiven wurden direkt via SSH in VHost eingetragen — können bei KeyHelp-Änderungen (z.B. SSL-Erneuerung) überschrieben werden. Ggf. nach KeyHelp-Aktionen prüfen.
- Phase 4: Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen — Markennamen und Verfahren korrekt benennen
- Fehlerhaftes alt-Attribut bei Jennifer Huegel (team-jenny-huegel.jpg alt="Maria Schueler") — Content-Phase

## Session Continuity

Last session: 2026-03-03T08:20Z
Stopped at: Completed 03-03-PLAN.md — Person-Schema mit 7 Teammitgliedern auf team.html (E-E-A-T)
Resume file: None — Phase 3 Plan 04 (WebSite-Schema + areaServed auf index.html) next
