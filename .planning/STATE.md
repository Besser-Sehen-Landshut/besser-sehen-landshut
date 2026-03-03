# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-03-03)

**Core value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.
**Current focus:** Phase 1 – Analytics

## Current Position

Phase: 1 of 4 (Analytics)
Plan: 1 of 2 in current phase (01-01 paused at checkpoint)
Status: Awaiting human action (Task 2: KeyHelp reverse proxy + Umami UUID)
Last activity: 2026-03-03 — 01-01 Task 1 complete: Umami Docker deployed on Pollux

Progress: [░░░░░░░░░░] 5%

## Performance Metrics

**Velocity:**
- Total plans completed: 0
- Average duration: -
- Total execution time: -

**By Phase:**

| Phase | Plans | Total | Avg/Plan |
|-------|-------|-------|----------|
| - | - | - | - |

**Recent Trend:**
- Last 5 plans: -
- Trend: -

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

### Pending Todos

None yet.

### Blockers/Concerns

- 01-01 BLOCKED: Human must configure KeyHelp reverse proxy (analytics.bessersehen.la → 127.0.0.1:3000) and retrieve Umami website UUID for bessersehen.la — see 01-01-PLAN.md Task 2
- Phase 3: Genaue Öffnungszeiten und Google-Maps-URL müssen aus bestehendem HTML extrahiert werden (für Schema)
- Phase 4: Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen — Markennamen und Verfahren korrekt benennen

## Session Continuity

Last session: 2026-03-03T05:39Z
Stopped at: 01-01 Task 2 checkpoint:human-action — waiting for KeyHelp proxy config + Umami UUID
Resume file: .planning/phases/01-analytics/01-01-PLAN.md (Task 2, then Task 3 not applicable — plan has 2 tasks only)
