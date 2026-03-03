# Besser Sehen Landshut – SEO & Analytics Milestone

## What This Is

Statische HTML-Website für Besser Sehen Landshut, einen spezialisierten Augenoptiker und Kontaktlinsen-Experten in Landshut (Niederbayern). Die Website (bessersehen.la) hat in einem ersten SEO-Sprint Grundlagen erhalten (H1-Tags, Canonical, OG-Tags, WebP-Bilder, Breadcrumb-Schema, CI/CD via GitHub Actions). Dieser Milestone baut darauf auf: Analytics, Security, Schema-Vollständigkeit und Content-Tiefe.

## Core Value

Potenzielle Patienten aus Landshut und Umgebung finden Besser Sehen Landshut über Google, wenn sie nach Augenoptiker, Kontaktlinsen oder Nachtlinsen suchen — und nehmen Kontakt auf.

## Requirements

### Validated

- ✓ Deployment automatisiert (GitHub Actions → Pollux-Server via rsync) — v0
- ✓ H1-Tags, Canonical, OG/Twitter-Tags auf allen 8 Seiten — v0
- ✓ WebP-Bilder (23 Dateien, ~35% kleiner) mit picture-Fallback — v0
- ✓ Breadcrumb-Schema (BreadcrumbList) auf allen 7 Unterseiten — v0
- ✓ robots.txt, sitemap.xml — v0

### Active

- [ ] Umami Analytics auf Pollux installieren (DSGVO-konform, kein Cookie-Banner nötig)
- [ ] Umami-Tracking auf alle 8 Seiten einbauen
- [ ] Security-Headers via .htaccess (HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy)
- [ ] Optician-Schema vervollständigen (address, telephone, openingHoursSpecification, geo, sameAs)
- [ ] WebSite-Schema auf index.html
- [ ] Service-Schema: serviceType + image auf Leistungsseiten
- [ ] Person-Schema auf team.html
- [ ] areaServed als City-Objekt in Schema
- [ ] Content leistungen-nachtlinsen.html auf 800+ Wörter (inkl. FAQ Kosten/Kasse, Produktnamen)
- [ ] Content leistungen-beratung-kurzsichtigkeit.html auf 800+ Wörter
- [ ] Content leistungen-visuelle-reha.html auf 800+ Wörter
- [ ] Content leistungen-kontaktlinsen.html auf 800+ Wörter
- [ ] team.html ausbauen: Spezialisierungen/Qualifikationen je Person
- [ ] Meta-Keywords pro Seite individualisieren (aktuell auf allen identisch)
- [ ] Nachbargemeinden als Einzugsgebiet erwähnen (Ergolding, Rottenburg a.d.L.)

### Out of Scope

- GA4 / Google Analytics — bewusst gegen entschieden (DSGVO-Aufwand, US-Server); Umami stattdessen
- E-Commerce / Terminbuchungssystem — zu komplex für statische Seite in diesem Milestone
- Performance-Optimierung des jQuery-Stacks — aufwändig, geringes SEO-Delta
- Mobile App — nicht relevant

## Context

- **Stack:** Statische HTML/CSS/JS, jQuery-Stack (25+ Vendor-Dateien), Apache-Server (KeyHelp-Panel)
- **Hosting:** Pollux VPS (195.201.220.6), User `bessersehen` unter `/home/users/bessersehen/www/`
- **Git:** `github.com/Besser-Sehen-Landshut/besser-sehen-landshut`, Branch `main`
- **Deploy:** GitHub Actions → rsync über SSH, ~19s Deploy-Zeit
- **Umami:** Wird auf Pollux via Docker installiert, Port 3000, Nginx-Reverse-Proxy
- **DSGVO-Vorteil Umami:** Keine Cookies, kein Consent-Banner nötig, EU-Daten

## Constraints

- **Tech:** Nur statisches HTML — kein Build-System, kein CMS
- **Content:** Fachlich korrekte Inhalte zu Augenoptik/Kontaktlinsen erforderlich (Markennamen, Verfahren)
- **Schema:** Fehlende Geschäftsdaten (genaue Öffnungszeiten, Google-Maps-URL) müssen aus bestehendem HTML extrahiert werden
- **Apache:** .htaccess wird von KeyHelp-Setup unterstützt; HSTS nur wenn SSL aktiv (ist es)

## Key Decisions

| Decision | Rationale | Outcome |
|----------|-----------|---------|
| Umami statt GA4 | DSGVO-konform ohne Anwalt, kein Cookie-Banner, self-hosted kostenlos | — Pending |
| rsync-Deploy statt GitHub Pages | .htaccess-Unterstützung für Security-Headers benötigt | ✓ Deployed |
| WebP mit picture-Fallback | ~35% kleinere Bilder, IE11-Kompatibilität durch Fallback | ✓ Live |

---
*Last updated: 2026-03-03 nach Projektinitialisierung*
