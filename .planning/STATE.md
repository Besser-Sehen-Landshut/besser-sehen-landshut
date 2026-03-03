# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-03-03)

**Core value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.
**Current focus:** Phase 1 – Analytics

## Current Position

Phase: 1 of 4 (Analytics)
Plan: 2 of 2 in current phase (01-02 complete; awaiting KeyHelp proxy verification)
Status: Awaiting human action (KeyHelp reverse proxy for analytics.bessersehen.la)
Last activity: 2026-03-03 — 01-02 complete: Umami script on all 10 pages, GA code removed, deployed to production

Progress: [████████░░] 75%

## Performance Metrics

**Velocity:**
- Total plans completed: 1 (01-02 complete; 01-01 partially complete — pending human action)
- Average duration: ~11 min
- Total execution time: ~13 min (01-01 Task 1: 2 min, 01-02: 11 min)

**By Phase:**

| Phase | Plans | Total | Avg/Plan |
|-------|-------|-------|----------|
| 01-analytics | 1/2 | ~13 min | ~11 min |

**Recent Trend:**
- Last 5 plans: 01-02 (11 min)
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

### Pending Todos

None yet.

### Blockers/Concerns

- BLOCKING: KeyHelp reverse proxy für analytics.bessersehen.la muss im Browser konfiguriert werden — detaillierte Anleitung in 01-02-SUMMARY.md "What Remains" Abschnitt
- Phase 3: Genaue Öffnungszeiten und Google-Maps-URL müssen aus bestehendem HTML extrahiert werden (für Schema)
- Phase 4: Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen — Markennamen und Verfahren korrekt benennen

## Session Continuity

Last session: 2026-03-03T05:50Z
Stopped at: 01-02 complete — awaiting KeyHelp reverse proxy setup for analytics.bessersehen.la
Resume file: None — configure KeyHelp proxy per 01-02-SUMMARY.md, then verify live tracking in Umami dashboard
