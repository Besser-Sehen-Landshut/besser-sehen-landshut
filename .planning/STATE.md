---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
status: unknown
last_updated: "2026-03-03T08:17:23.739Z"
progress:
  total_phases: 3
  completed_phases: 2
  total_plans: 7
  completed_plans: 6
---

---
gsd_state_version: 1.0
milestone: v1.0
milestone_name: milestone
status: active
last_updated: "2026-03-03T07:35:00.000Z"
progress:
  total_phases: 4
  completed_phases: 1
  total_plans: 3
  completed_plans: 3
---

# Project State

## Project Reference

See: .planning/PROJECT.md (updated 2026-03-03)

**Core value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.
**Current focus:** Phase 3 – Schema

## Current Position

Phase: 3 of 4 (Schema)
Plan: 2 of 4 in current phase — COMPLETE
Status: Phase 3 active — 03-02 complete
Last activity: 2026-03-03 — 03-02 complete: Service-Schemas auf 4 Leistungsseiten vervollstaendigt (serviceType, image, areaServed City-Array)

Progress: [████████████████████░░░░] 55% (Phase 1 + Phase 2 + 03-01 + 03-02 complete)

## Performance Metrics

**Velocity:**
- Total plans completed: 5 (01-01, 01-02, 02-01, 03-01, 03-02)
- Average duration: ~10 min (schema plans fast, analytics plans had human setup)
- Total execution time: ~92 min (01-01: 64 min incl. human; 01-02: ~11 min; 02-01: ~15 min; 03-01: ~1 min; 03-02: ~1 min)

**By Phase:**

| Phase | Plans | Total | Avg/Plan |
|-------|-------|-------|----------|
| 01-analytics | 2/2 | ~75 min | ~38 min |
| 02-security | 1/1 | ~15 min | ~15 min |
| 03-schema | 2/4 | ~2 min | ~1 min |

**Recent Trend:**
- Last 5 plans: 01-02 (11 min), 02-01 (15 min), 03-01 (1 min), 03-02 (1 min)
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

### Pending Todos

None yet.

### Blockers/Concerns

- KeyHelp-Warnung: Reverse-Proxy-Direktiven wurden direkt via SSH in VHost eingetragen — können bei KeyHelp-Änderungen (z.B. SSL-Erneuerung) überschrieben werden. Ggf. nach KeyHelp-Aktionen prüfen.
- Phase 3: Genaue Öffnungszeiten und Google-Maps-URL müssen aus bestehendem HTML extrahiert werden (für Schema)
- Phase 4: Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen — Markennamen und Verfahren korrekt benennen

## Session Continuity

Last session: 2026-03-03T08:16Z
Stopped at: Completed 03-02-PLAN.md — Service-Schemas auf 4 Leistungsseiten vervollstaendigt (serviceType, image, areaServed City-Array)
Resume file: None — Phase 3 continues: 03-03 (Person-Schema team.html) und 03-04 (WebSite-Schema index.html) ausstehend
