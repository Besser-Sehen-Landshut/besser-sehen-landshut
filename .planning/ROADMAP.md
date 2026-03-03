# Roadmap: Besser Sehen Landshut – SEO & Analytics

## Overview

This milestone builds on the SEO foundation (H1-Tags, Canonical, OG-Tags, WebP, Breadcrumb-Schema) already deployed. Four phases deliver the remaining work in dependency order: first measure (Analytics), then protect (Security), then enrich structured data (Schema), then deepen content (Content + Meta). When complete, the site is fully trackable, secured against common HTTP attacks, understood by Google's Knowledge Panel, and deep enough on each page to rank for local search queries.

## Phases

**Phase Numbering:**
- Integer phases (1, 2, 3): Planned milestone work
- Decimal phases (2.1, 2.2): Urgent insertions (marked with INSERTED)

Decimal phases appear between their surrounding integers in numeric order.

- [x] **Phase 1: Analytics** - Umami self-hosted auf Pollux installiert, Reverse Proxy aktiv, Tracking auf allen 10 Seiten eingebunden
- [ ] **Phase 2: Security** - HTTP Security-Headers via .htaccess auf dem Apache-Server setzen
- [ ] **Phase 3: Schema** - Alle strukturierten Daten vervollständigen (Optician, WebSite, Service, Person, areaServed)
- [ ] **Phase 4: Content** - Alle Leistungsseiten auf 800+ Wörter ausbauen, team.html vertiefen, Meta-Keywords individualisieren

## Phase Details

### Phase 1: Analytics
**Goal**: Besuchsströme auf bessersehen.la werden DSGVO-konform erfasst und sind auswertbar
**Depends on**: Nothing (first phase)
**Requirements**: ANAL-01, ANAL-02, ANAL-03
**Success Criteria** (what must be TRUE):
  1. Umami-Dashboard ist unter einer URL auf dem Pollux-Server erreichbar und zeigt Echtzeit-Daten
  2. Alle 10 HTML-Seiten enthalten das Umami-Tracking-Script im `<head>`
  3. Ein Testbesuch auf bessersehen.la erscheint innerhalb von 60 Sekunden in Umami
  4. Kein Cookie-Banner ist auf der Website sichtbar (Umami ist cookiefrei)
**Plans**: 2 plans

Plans:
- [x] 01-01-PLAN.md — Umami Docker-Setup auf Pollux, Reverse Proxy via SSH konfiguriert, dashboard live unter analytics.bessersehen.la
- [x] 01-02-PLAN.md — Tracking-Script in alle 10 HTML-Dateien eingebaut, GA-Code entfernt, deployed

### Phase 2: Security
**Goal**: Der Apache-Server sendet alle wichtigen Security-Headers und schützt Besucher vor gängigen HTTP-Angriffen
**Depends on**: Phase 1
**Requirements**: SEC-01, SEC-02, SEC-03, SEC-04, SEC-05
**Success Criteria** (what must be TRUE):
  1. `curl -I https://bessersehen.la` zeigt Strict-Transport-Security, X-Frame-Options, X-Content-Type-Options, Referrer-Policy und Content-Security-Policy
  2. securityheaders.com gibt der Website mindestens Grade B
  3. Die Website lädt weiterhin fehlerfrei (keine durch CSP blockierten Ressourcen)
**Plans**: 1 plan

Plans:
- [ ] 02-01-PLAN.md — .htaccess mit allen 5 Security-Headers erstellen, deployen, securityheaders.com Grade B verifizieren

### Phase 3: Schema
**Goal**: Google kann Besser Sehen Landshut als lokales Geschäft vollständig verstehen und im Knowledge Panel darstellen
**Depends on**: Phase 2
**Requirements**: SCHEMA-01, SCHEMA-02, SCHEMA-03, SCHEMA-04, SCHEMA-05
**Success Criteria** (what must be TRUE):
  1. Das Optician-Schema auf index.html enthält address, telephone, openingHoursSpecification, geo-Koordinaten und sameAs-Links (Google Maps, Social)
  2. Das Google Rich Results Test Tool findet auf index.html gültiges LocalBusiness- und WebSite-Schema ohne Fehler
  3. Jede der 4 Leistungsseiten hat ein gültiges Service-Schema mit serviceType und image
  4. team.html enthält ein Person-Schema für jedes Teammitglied
  5. areaServed listet Landshut sowie Nachbargemeinden als City-Objekte
**Plans**: TBD

### Phase 4: Content
**Goal**: Jede Leistungsseite hat genug Tiefe, um bei lokalen Suchanfragen zu ranken, und Meta-Daten sind seitenindividuell
**Depends on**: Phase 3
**Requirements**: CONT-01, CONT-02, CONT-03, CONT-04, CONT-05, CONT-06, META-01
**Success Criteria** (what must be TRUE):
  1. Alle 4 Leistungsseiten (Nachtlinsen, Myopie-Management, Visuelle Reha, Kontaktlinsen) haben 800+ Wörter fachlich korrekten Inhalts
  2. leistungen-nachtlinsen.html enthält ein FAQ-Abschnitt zu Kosten/Kassenleistungen sowie die Produktnamen DreamLens und Paragon CRT
  3. team.html enthält Spezialisierungen und Qualifikationen je Person (300+ Wörter gesamt)
  4. Ergolding und Rottenburg a.d.L. sind auf mindestens einer Seite als Einzugsgebiet erwähnt
  5. Jede der 8 Seiten hat ein eigenes, seitenspezifisches `meta name="keywords"`-Tag
**Plans**: TBD

## Progress

**Execution Order:**
Phases execute in numeric order: 1 → 2 → 3 → 4

| Phase | Plans Complete | Status | Completed |
|-------|----------------|--------|-----------|
| 1. Analytics | 2/2 | Complete | 2026-03-03 |
| 2. Security | 0/1 | Not started | - |
| 3. Schema | 0/TBD | Not started | - |
| 4. Content | 0/TBD | Not started | - |
