---
phase: 01-analytics
plan: 01
subsystem: infra
tags: [umami, docker, docker-compose, postgresql, analytics, apache, reverse-proxy, keyhelp]

# Dependency graph
requires: []
provides:
  - "Umami Analytics running on Pollux at https://analytics.bessersehen.la (public, HTTPS)"
  - "PostgreSQL database container (umami-db-1) with persistent volume"
  - "Port 3000 bound to 127.0.0.1 only — not publicly accessible directly"
  - "Apache reverse proxy: analytics.bessersehen.la → http://127.0.0.1:3000 (configured via SSH)"
  - "Umami website UUID for bessersehen.la: a8d3de83-ea5f-429d-9829-d639ad9cdad1"
  - "Umami admin password changed from default"
  - "Reference docker-compose.yml at .planning/phases/01-analytics/server/"
affects:
  - 01-02
  - "02-seo-security"
  - "03-schema"
  - "04-content"

# Tech tracking
tech-stack:
  added:
    - "Umami Analytics (ghcr.io/umami-software/umami:latest)"
    - "PostgreSQL 15-alpine (Docker)"
    - "Docker Compose v2 (already on server)"
    - "Apache mod_proxy + mod_proxy_http (already enabled)"
  patterns:
    - "Secrets via .env with chmod 600 (APP_SECRET, POSTGRES_PASSWORD via openssl rand)"
    - "Port binding to 127.0.0.1 only to prevent Docker bypassing firewall"
    - "Apache reverse proxy via SSH-edited VHost (KeyHelp has no UI field for custom directives)"
    - "db healthcheck (pg_isready) as dependency gate for umami startup"

key-files:
  created:
    - "/opt/umami/docker-compose.yml (on Pollux server)"
    - "/opt/umami/.env (on Pollux server, not in git — contains secrets)"
    - ".planning/phases/01-analytics/server/docker-compose.yml (local reference copy)"
  modified:
    - "Apache VHost for analytics.bessersehen.la (on Pollux — ProxyPass directives added via SSH)"

key-decisions:
  - "Port bound to 127.0.0.1:3000 not 0.0.0.0:3000 — prevents Docker firewall bypass"
  - "POSTGRES_PASSWORD generated with openssl rand -hex 16 (hex-only, no special chars that break PostgreSQL URL parsing)"
  - "DISABLE_TELEMETRY=1 — no data sent to Umami cloud"
  - "mod_proxy and mod_proxy_http were already enabled on Apache — no action needed"
  - "Reverse proxy configured via direct SSH VHost edit — KeyHelp UI had no custom directives field"

patterns-established:
  - "Server-side files committed as reference copies in .planning/phases/XX/server/ (without secrets)"
  - "For KeyHelp servers: reverse proxy directives added directly to Apache VHost via SSH, not KeyHelp UI"

requirements-completed: [ANAL-01]

# Metrics
duration: 64min
completed: 2026-03-03
---

# Phase 01 Plan 01: Umami Docker Deployment Summary

**Umami Analytics + PostgreSQL deployed via Docker Compose on Pollux; reverse proxy configured via SSH; dashboard live at https://analytics.bessersehen.la with UUID a8d3de83-ea5f-429d-9829-d639ad9cdad1**

## Performance

- **Duration:** ~64 min total (including human action for reverse proxy and first login)
- **Started:** 2026-03-03T05:37:28Z
- **Completed:** 2026-03-03T06:41:34Z
- **Tasks:** 2 of 2 completed
- **Files modified:** 2 (local reference copy + server-side Apache VHost)

## Accomplishments

- Deployed Umami Analytics + PostgreSQL via Docker Compose on Pollux (both containers healthy, uptime confirmed)
- Port 3000 correctly bound to 127.0.0.1 — not accessible from the public internet (confirmed: connection timeout on port 3000)
- Generated cryptographically random secrets (APP_SECRET, POSTGRES_PASSWORD) stored in /opt/umami/.env with chmod 600
- Configured Apache reverse proxy at analytics.bessersehen.la → http://127.0.0.1:3000 (via SSH VHost edit)
- SSL certificate was already issued by Let's Encrypt via KeyHelp — no action needed
- Umami dashboard is live at https://analytics.bessersehen.la with HTTPS
- Umami admin password changed from default "umami" to a secure password
- bessersehen.la added as website in Umami; UUID obtained: `a8d3de83-ea5f-429d-9829-d639ad9cdad1`

## Task Commits

1. **Task 1: Deploy Umami via Docker Compose on Pollux** - `aced40b` (feat)
2. **Task 2: KeyHelp Reverse Proxy Setup and First Umami Login** - human action (no code commit; server-side only)

**Plan metadata (checkpoint + final):** `fc6ea1b`, `(this commit)`

## Files Created/Modified

- `/opt/umami/docker-compose.yml` (on Pollux) — Umami + PostgreSQL service definitions, port 127.0.0.1:3000
- `/opt/umami/.env` (on Pollux) — APP_SECRET and POSTGRES_PASSWORD, chmod 600, NOT in git
- `.planning/phases/01-analytics/server/docker-compose.yml` — Local reference copy for documentation
- Apache VHost for `analytics.bessersehen.la` (on Pollux) — ProxyPass directives added via SSH

## Decisions Made

- **Port binding:** `127.0.0.1:3000:3000` (not `3000:3000`) — prevents Docker bypassing the system firewall
- **POSTGRES_PASSWORD:** `openssl rand -hex 16` — hex output avoids special characters that break PostgreSQL URL parsing
- **Reverse proxy via SSH, not KeyHelp UI:** KeyHelp panel had no custom directives field for the subdomain. Proxy was configured by directly editing the Apache VHost file via SSH. This diverges from the plan's instruction to use the KeyHelp UI.

## Deviations from Plan

### Task 2 Execution Deviation

**[Rule 1 - Adaptation] Reverse proxy configured via SSH, not KeyHelp UI**
- **Found during:** Task 2 (KeyHelp Reverse Proxy Setup)
- **Issue:** The plan instructed configuring the reverse proxy via the KeyHelp admin panel UI (Settings -> Apache directives field). The KeyHelp panel had no such field for the subdomain.
- **Fix:** Reverse proxy ProxyPass directives added directly to the Apache VHost file via SSH on the server.
- **Result:** Functionally identical — `https://analytics.bessersehen.la/api/heartbeat` returns `{"ok":true}`, SSL works, port 3000 remains private.
- **Impact:** No negative impact. Note: if KeyHelp regenerates VHost files (e.g., on certificate renewal or panel changes), the custom directives may be overwritten. Manual re-application may be needed.

## Issues Encountered

None beyond the plan deviation documented above.

## Verification Results

All plan success criteria confirmed:

```
curl -sf https://analytics.bessersehen.la/api/heartbeat
→ {"ok":true}

curl -sI https://analytics.bessersehen.la/script.js | grep content-type
→ content-type: application/javascript; charset=UTF-8

ssh pollux "docker compose -f /opt/umami/docker-compose.yml ps"
→ umami-db-1: Up (healthy), umami-umami-1: Up (healthy)

curl --connect-timeout 5 http://195.201.220.6:3000/api/heartbeat
→ curl: (28) Failed to connect ... Timeout was reached
```

## Next Phase Readiness

- Umami dashboard live at https://analytics.bessersehen.la
- Website UUID `a8d3de83-ea5f-429d-9829-d639ad9cdad1` available for plan 01-02
- Admin password changed from default
- Plan 01-02 can proceed immediately

## Self-Check: PASSED

- FOUND: `.planning/phases/01-analytics/01-01-SUMMARY.md`
- FOUND: `.planning/phases/01-analytics/server/docker-compose.yml`
- FOUND: commit `aced40b` (feat: Umami Docker deployment)
- VERIFIED: `https://analytics.bessersehen.la/api/heartbeat` returns `{"ok":true}`
- VERIFIED: Port 3000 NOT publicly accessible (connection timeout confirmed)
- VERIFIED: Both Docker containers healthy on Pollux

---
*Phase: 01-analytics*
*Completed: 2026-03-03*
