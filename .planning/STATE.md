# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-03-03)

**Core value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.
**Current focus:** Phase 1 – Analytics

## Current Position

Phase: 2 of 4 (Security)
Plan: 0 of TBD in current phase
Status: Ready to plan
Last activity: 2026-03-03 — Phase 1 complete: Umami live at analytics.bessersehen.la, tracking on all 10 pages

Progress: [██████████] 25% (Phase 1 complete)

## Performance Metrics

**Velocity:**
- Total plans completed: 2 (01-01 and 01-02 both complete)
- Average duration: ~38 min (incl. human checkpoint time in 01-01)
- Total execution time: ~75 min (01-01: 64 min incl. human; 01-02: ~11 min)

**By Phase:**

| Phase | Plans | Total | Avg/Plan |
|-------|-------|-------|----------|
| 01-analytics | 2/2 | ~75 min | ~38 min |

**Recent Trend:**
- Last 5 plans: 01-01 (64 min incl. human), 01-02 (11 min)
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

### Pending Todos

None yet.

### Blockers/Concerns

- KeyHelp-Warnung: Reverse-Proxy-Direktiven wurden direkt via SSH in VHost eingetragen — können bei KeyHelp-Änderungen (z.B. SSL-Erneuerung) überschrieben werden. Ggf. nach KeyHelp-Aktionen prüfen.
- Phase 3: Genaue Öffnungszeiten und Google-Maps-URL müssen aus bestehendem HTML extrahiert werden (für Schema)
- Phase 4: Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen — Markennamen und Verfahren korrekt benennen

## Session Continuity

Last session: 2026-03-03T06:41Z
Stopped at: Phase 1 complete — 01-01 SUMMARY.md finalized, reverse proxy confirmed live, moving to Phase 2
Resume file: None — Phase 2 (Security) next: plan .htaccess security headers
