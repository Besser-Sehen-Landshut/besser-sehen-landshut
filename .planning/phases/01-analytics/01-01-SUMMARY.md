---
phase: 01-analytics
plan: 01
subsystem: infra
tags: [umami, docker, docker-compose, postgresql, analytics, apache, reverse-proxy]

# Dependency graph
requires: []
provides:
  - "Umami Analytics container running on Pollux at http://127.0.0.1:3000"
  - "PostgreSQL database container (umami-db-1) with persistent volume"
  - "Port binding: 127.0.0.1:3000 (not publicly accessible)"
  - "Reference docker-compose.yml at .planning/phases/01-analytics/server/"
affects:
  - 01-02
  - "02-seo-schema"
  - "03-content"

# Tech tracking
tech-stack:
  added:
    - "Umami Analytics (ghcr.io/umami-software/umami:latest)"
    - "PostgreSQL 15-alpine (Docker)"
    - "Docker Compose v2 (already on server)"
  patterns:
    - "Secrets via .env with chmod 600 (APP_SECRET, POSTGRES_PASSWORD via openssl rand)"
    - "Port binding to 127.0.0.1 only to prevent Docker bypassing firewall"
    - "db healthcheck (pg_isready) as dependency gate for umami startup"

key-files:
  created:
    - "/opt/umami/docker-compose.yml (on Pollux server)"
    - "/opt/umami/.env (on Pollux server, not in git — contains secrets)"
    - ".planning/phases/01-analytics/server/docker-compose.yml (local reference copy)"
  modified: []

key-decisions:
  - "Port bound to 127.0.0.1:3000 not 0.0.0.0:3000 — prevents Docker firewall bypass"
  - "POSTGRES_PASSWORD generated with openssl rand -hex 16 (hex-only, no special chars that break PostgreSQL URL parsing)"
  - "DISABLE_TELEMETRY=1 — no data sent to Umami cloud"
  - "mod_proxy and mod_proxy_http were already enabled on Apache — no action needed"

patterns-established:
  - "Server-side files committed as reference copies in .planning/phases/XX/server/ (without secrets)"

requirements-completed: []  # ANAL-01 partially complete — Task 2 (KeyHelp + first login) still pending

# Metrics
duration: 2min
completed: 2026-03-03
---

# Phase 01 Plan 01: Umami Docker Deployment Summary

**Umami Analytics + PostgreSQL deployed via Docker Compose on Pollux VPS, accessible locally at http://127.0.0.1:3000, port-isolated from public internet**

## Performance

- **Duration:** ~2 min (Task 1 only; Task 2 awaiting human action)
- **Started:** 2026-03-03T05:37:28Z
- **Completed (Task 1):** 2026-03-03T05:39:53Z
- **Tasks:** 1 of 2 completed
- **Files modified:** 1 (local reference copy created)

## Accomplishments

- Deployed Umami Analytics via Docker Compose on Pollux VPS (both containers healthy)
- Port 3000 correctly bound to 127.0.0.1 only — not publicly accessible (confirmed via external timeout)
- Generated cryptographically random secrets (APP_SECRET, POSTGRES_PASSWORD) stored in /opt/umami/.env with chmod 600
- Heartbeat confirmed: `curl http://127.0.0.1:3000/api/heartbeat` returns `{"ok":true}` on server

## Task Commits

1. **Task 1: Deploy Umami via Docker Compose on Pollux** - `aced40b` (feat)
2. **Task 2: KeyHelp Reverse Proxy Setup and First Umami Login** - PENDING (checkpoint:human-action)

## Files Created/Modified

- `/opt/umami/docker-compose.yml` (on Pollux) — Umami + PostgreSQL service definitions, port 127.0.0.1:3000
- `/opt/umami/.env` (on Pollux) — APP_SECRET and POSTGRES_PASSWORD, chmod 600, NOT in git
- `.planning/phases/01-analytics/server/docker-compose.yml` — Local reference copy for documentation

## Decisions Made

- Port binding uses `127.0.0.1:3000:3000` (not `3000:3000`) to prevent Docker bypassing the system firewall
- POSTGRES_PASSWORD generated with `openssl rand -hex 16` — hex output avoids special characters that break PostgreSQL URL parsing
- Apache mod_proxy modules were already enabled — no manual activation required

## Deviations from Plan

None — plan executed exactly as written for Task 1.

## Issues Encountered

None. mod_proxy was already active. Both containers reached healthy state within ~40 seconds of `docker compose up -d` (DB migrations completed quickly).

## Status: PAUSED AT CHECKPOINT

Task 2 requires human browser access to:
1. KeyHelp admin panel to configure Apache reverse proxy at analytics.bessersehen.la
2. Umami dashboard to change admin password and add bessersehen.la as a website
3. Copy the Umami website UUID for bessersehen.la (needed by plan 01-02)

See `.planning/phases/01-analytics/01-01-PLAN.md` Task 2 for detailed step-by-step instructions.

## Next Phase Readiness

- Umami is running and healthy on Pollux at http://127.0.0.1:3000
- Requires KeyHelp reverse proxy to be configured (analytics.bessersehen.la → 127.0.0.1:3000)
- Requires Umami website UUID for bessersehen.la before plan 01-02 can run (adds tracking script to HTML)

---
*Phase: 01-analytics*
*Partially completed: 2026-03-03*
