# Requirements: Besser Sehen Landshut – SEO & Analytics

**Defined:** 2026-03-03
**Core Value:** Potenzielle Patienten aus Landshut finden bessersehen.la über Google und nehmen Kontakt auf.

## v1 Requirements

### Analytics

- [ ] **ANAL-01**: Umami Analytics wird auf Pollux-Server via Docker installiert und ist erreichbar
- [x] **ANAL-02**: Umami-Tracking-Script ist auf allen 10 HTML-Seiten eingebunden (2026-03-03, commit 4a58e78)
- [x] **ANAL-03**: Besuche auf bessersehen.la werden korrekt in Umami erfasst (2026-03-03, Script live — KeyHelp-Proxy für Dashboard-Ansicht noch offen)

### Sicherheit

- [ ] **SEC-01**: .htaccess setzt HSTS-Header (Strict-Transport-Security, max-age=31536000)
- [ ] **SEC-02**: .htaccess setzt X-Frame-Options: SAMEORIGIN
- [ ] **SEC-03**: .htaccess setzt X-Content-Type-Options: nosniff
- [ ] **SEC-04**: .htaccess setzt Referrer-Policy: strict-origin-when-cross-origin
- [ ] **SEC-05**: .htaccess setzt Content-Security-Policy (erlaubt eigene Assets + Google Fonts)

### Schema-Markup

- [ ] **SCHEMA-01**: Optician-Schema auf index.html enthält address, telephone, openingHoursSpecification, geo, sameAs
- [ ] **SCHEMA-02**: WebSite-Schema mit SearchAction auf index.html
- [ ] **SCHEMA-03**: Service-Schema auf allen 4 Leistungsseiten enthält serviceType und image
- [ ] **SCHEMA-04**: Person-Schema für jedes Teammitglied auf team.html
- [ ] **SCHEMA-05**: areaServed in Optician-Schema als City-Objekte (Landshut + Nachbargemeinden)

### Content

- [ ] **CONT-01**: leistungen-nachtlinsen.html hat 800+ Wörter (inkl. FAQ zu Kosten/Kasse, DreamLens, Paragon CRT)
- [ ] **CONT-02**: leistungen-beratung-kurzsichtigkeit.html hat 800+ Wörter (Myopie-Management, Zielgruppe Kinder/Jugendliche)
- [ ] **CONT-03**: leistungen-visuelle-reha.html hat 800+ Wörter (Hilfsmittel, Beratung, Zielgruppe)
- [ ] **CONT-04**: leistungen-kontaktlinsen.html hat 800+ Wörter (Linsentypen, Anpassung, Vorteile)
- [ ] **CONT-05**: team.html enthält Spezialisierungen und Qualifikationen je Person (80 → 300+ Wörter)
- [ ] **CONT-06**: Nachbargemeinden Ergolding und Rottenburg a.d.L. in relevantem Content erwähnt

### Meta-Daten

- [ ] **META-01**: meta name="keywords" ist auf jeder der 8 Seiten individuell (aktuell identisch)

## v2 Requirements

### Performance

- **PERF-01**: jQuery-Stack durch moderne Alternativen ersetzen (aktuell 25+ Vendor-Dateien)
- **PERF-02**: Bilder lazy-loading konsequent auf allen Seiten

### Conversion

- **CONV-01**: Terminbuchung / Kontaktformular mit direkter E-Mail-Bestätigung
- **CONV-02**: Google-Rezensionen-Widget einbinden

## Out of Scope

| Feature | Grund |
|---------|-------|
| GA4 / Google Analytics | DSGVO-Aufwand, US-Server; Umami gewählt |
| Cookie-Banner | Mit Umami nicht erforderlich |
| CMS-Integration | Statischer Stack bleibt erhalten |
| Mobile App | Nicht relevant für lokalen Dienstleister |

## Traceability

| Requirement | Phase | Status |
|-------------|-------|--------|
| ANAL-01 | Phase 1 | Pending |
| ANAL-02 | Phase 1 | Complete (2026-03-03) |
| ANAL-03 | Phase 1 | Complete (2026-03-03) |
| SEC-01 | Phase 2 | Pending |
| SEC-02 | Phase 2 | Pending |
| SEC-03 | Phase 2 | Pending |
| SEC-04 | Phase 2 | Pending |
| SEC-05 | Phase 2 | Pending |
| SCHEMA-01 | Phase 3 | Pending |
| SCHEMA-02 | Phase 3 | Pending |
| SCHEMA-03 | Phase 3 | Pending |
| SCHEMA-04 | Phase 3 | Pending |
| SCHEMA-05 | Phase 3 | Pending |
| CONT-01 | Phase 4 | Pending |
| CONT-02 | Phase 4 | Pending |
| CONT-03 | Phase 4 | Pending |
| CONT-04 | Phase 4 | Pending |
| CONT-05 | Phase 4 | Pending |
| CONT-06 | Phase 4 | Pending |
| META-01 | Phase 4 | Pending |

**Coverage:**
- v1 requirements: 20 total
- Mapped to phases: 20
- Unmapped: 0 ✓

---
*Requirements defined: 2026-03-03*
*Last updated: 2026-03-03 nach Roadmap-Erstellung (META-01 → Phase 4)*
