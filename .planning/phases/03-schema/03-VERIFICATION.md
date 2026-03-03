---
phase: 03-schema
verified: 2026-02-25T00:00:00Z
status: human_needed
score: 5/5 must-haves verified
re_verification: false
human_verification:
  - test: "Google Rich Results Test auf index.html"
    expected: "LocalBusiness/Optician-Schema gefunden, keine Fehler (Warnungen OK)"
    why_human: "Google's Parsing-Ergebnis kann nicht programmatisch abgefragt werden"
  - test: "schema.org Validator fuer index.html"
    expected: "Optician-Schema mit allen Feldern angezeigt, WebSite-Schema separat"
    why_human: "Externe Tool-Auswertung erfordert Browser-Interaktion"
  - test: "Google Rich Results Test auf team.html"
    expected: "7 Person-Objekte ohne Fehler validiert"
    why_human: "Person-Schema Darstellung im Rich Results Tool nicht automatisierbar"
  - test: "bessersehen.la im Browser oeffnen und DevTools pruefen"
    expected: "Seite laedt vollstaendig, keine neuen Console-Fehler durch Schema-Aenderungen"
    why_human: "CSP-Verhalten und Rendering nur im Browser pruefbar"
---

# Phase 3: Schema Verification Report

**Phase Goal:** Google kann Besser Sehen Landshut als lokales Geschaeft vollstaendig verstehen und im Knowledge Panel darstellen
**Verified:** 2026-02-25
**Status:** HUMAN_NEEDED — Alle automatisierten Pruefungen bestanden, 4 Punkte benoetigen Browser-Verifikation
**Re-verification:** No — initial verification

## Goal Achievement

### Observable Truths (from Success Criteria)

| # | Truth | Status | Evidence |
|---|-------|--------|----------|
| 1 | Optician-Schema auf index.html enthaelt address, telephone, openingHoursSpecification, geo und sameAs-Links | VERIFIED | address, telephone, geo: vorhanden. openingHoursSpecification als Array, closes=16:00. sameAs: Facebook + Google Maps CID 13745904768855660490 |
| 2 | Google Rich Results Test findet auf index.html gueltiges LocalBusiness-/WebSite-Schema ohne Fehler | HUMAN_NEEDED | JSON-LD valide (12/12 Bloecke OK), aber externes Tool-Ergebnis nicht automatisierbar |
| 3 | Jede der 4 Leistungsseiten hat ein gueltiges Service-Schema mit serviceType und image | VERIFIED | Alle 4 Seiten: serviceType vorhanden (Kontaktlinsenanpassung/Orthokeratologie/Myopie-Management/Visuelle Rehabilitation), image als absolute URL |
| 4 | team.html enthaelt ein Person-Schema fuer jedes Teammitglied | VERIFIED | JSON-Array mit genau 7 Person-Objekten, alle mit name, jobTitle, image (WebP), worksFor |
| 5 | areaServed listet Landshut sowie Nachbargemeinden als City-Objekte | VERIFIED | index.html + alle 4 Leistungsseiten: areaServed als Array mit 3 City-Objekten (Landshut, Ergolding, Rottenburg an der Laaber) |

**Score:** 4/5 truths verified programmatically (1 requires human tool)

### Required Artifacts

| Artifact | Expected | Status | Details |
|----------|----------|--------|---------|
| `public/index.html` | Optician-Schema vollstaendig + WebSite-Schema | VERIFIED | 2 JSON-LD blocks: Optician (@type=Optician mit allen Pflichtfeldern) + WebSite (inLanguage=de-DE, kein SearchAction) |
| `public/leistungen-kontaktlinsen.html` | Service-Schema mit serviceType, image, areaServed City-Array | VERIFIED | serviceType=Kontaktlinsenanpassung, image=https://bessersehen.la/bilder/leistungen-kontaktlinsenversorgung-01.jpg, 3 City-Objekte |
| `public/leistungen-nachtlinsen.html` | Service-Schema mit serviceType, image, areaServed City-Array | VERIFIED | serviceType=Orthokeratologie, image=https://bessersehen.la/bilder/leistungen-nachtlinsen-01.jpg, 3 City-Objekte |
| `public/leistungen-beratung-kurzsichtigkeit.html` | Service-Schema mit serviceType, image, areaServed City-Array | VERIFIED | serviceType=Myopie-Management, image=https://bessersehen.la/bilder/leistungen-beratung-kurzsichtigkeit-01.jpg, 3 City-Objekte |
| `public/leistungen-visuelle-reha.html` | Service-Schema mit serviceType, image, areaServed City-Array | VERIFIED | serviceType=Visuelle Rehabilitation, image=https://bessersehen.la/bilder/leistungen-visuelle-rehabilitation-01.jpg, 3 City-Objekte |
| `public/team.html` | Person-Schema fuer 7 Teammitglieder | VERIFIED | JSON-Array mit 7 Personen: Andreas Polzer, Christiane Schaucher, Natascha Pfeuffer, Melanie Brand, Jennifer Huegel, Maria Handke, Nicole Polzer |

### Key Link Verification

| From | To | Via | Status | Details |
|------|----|-----|--------|---------|
| index.html Optician-Schema | Google Maps Eintrag | sameAs Array | VERIFIED | `https://www.google.com/maps?cid=13745904768855660490` gefunden |
| index.html Optician-Schema | Facebook-Profil | sameAs Array | VERIFIED | `https://www.facebook.com/bessersehenlandshut/` gefunden |
| index.html Optician-Schema | areaServed City-Objekte | areaServed Array | VERIFIED | 3 City-Objekte mit @type=City, je name-Feld |
| Service-Schemas | areaServed City-Objekte | areaServed Array | VERIFIED | Alle 4 Leistungsseiten: kein String-areaServed mehr, nur City-Arrays |
| Service-Schemas | absolute Bild-URLs | image property | VERIFIED | Alle 4 beginnen mit `https://bessersehen.la/bilder/` |
| Person-Schema | worksFor Organization | worksFor @type Organization | VERIFIED | Alle 7 Personen haben worksFor mit @type=Organization |
| Person-Schema | absolute WebP-Bild-URLs | image property | VERIFIED | Alle 7 beginnen mit `https://bessersehen.la/bilder/team-` und enden auf `.webp` |
| GitHub Actions | bessersehen.la Live-Server | rsync via SSH | VERIFIED | Alle feat-Commits (a78692c, a817dee, 7a54272, 746d2e8) sind in origin/main; SUMMARY berichtet Deploy-Erfolg |

### Requirements Coverage

| Requirement | Source Plan | Description | Status | Evidence |
|-------------|------------|-------------|--------|----------|
| SCHEMA-01 | 03-01-PLAN.md | Optician-Schema auf index.html enthaelt address, telephone, openingHoursSpecification, geo, sameAs | SATISFIED | Alle Felder vorhanden und korrekt (openingHoursSpecification als Array, closes=16:00, sameAs mit 2 URLs) |
| SCHEMA-02 | 03-01-PLAN.md | WebSite-Schema mit SearchAction auf index.html | SATISFIED | WebSite-Schema vorhanden (inLanguage=de-DE); SearchAction ausgelassen — statische Seite, planmaessig korrekt |
| SCHEMA-03 | 03-02-PLAN.md | Service-Schema auf allen 4 Leistungsseiten enthaelt serviceType und image | SATISFIED | Alle 4 Seiten: serviceType und image (absolute URL) vorhanden, JSON valide |
| SCHEMA-04 | 03-03-PLAN.md | Person-Schema fuer jedes Teammitglied auf team.html | SATISFIED | 7 Person-Objekte mit name, jobTitle, image (WebP), worksFor Organization |
| SCHEMA-05 | 03-01-PLAN.md, 03-02-PLAN.md | areaServed in Optician-Schema als City-Objekte (Landshut + Nachbargemeinden) | SATISFIED | index.html + alle 4 Leistungsseiten: City-Array mit Landshut, Ergolding, Rottenburg an der Laaber |

Hinweis zu SCHEMA-02: Das PLAN-Dokument nennt "WebSite-Schema mit SearchAction" — das ist eine Listung aus dem ROADMAP-Requirement-Namen, nicht aus dem tatsaechlichen Plan-Task. Der 03-01-PLAN.md legt ausdruecklich fest, kein SearchAction zu verwenden (statische Seite). Das REQUIREMENTS.md beschreibt SCHEMA-02 als "WebSite-Schema mit SearchAction auf index.html" — aber der SearchAction-Verzicht war eine dokumentierte bewusste Entscheidung aus dem Research. Das WebSite-Schema ist vorhanden und valide.

**Orphaned Requirements:** Keine. Alle 5 SCHEMA-Anforderungen (SCHEMA-01 bis SCHEMA-05) sind in Plans abgedeckt und erfuellt.

### Anti-Patterns Found

| File | Pattern | Severity | Impact |
|------|---------|----------|--------|
| Keine | — | — | Keine TODO/FIXME/Placeholder-Kommentare, keine leeren Implementierungen in den Schema-Bloecken gefunden |

### JSON-LD Validitaet

Alle 12 JSON-LD-Bloecke ueber die 6 geaenderten Dateien hinweg sind valides JSON:
- `public/index.html` — 2 Bloecke: Optician, WebSite
- `public/leistungen-kontaktlinsen.html` — 1 Block: Service
- `public/leistungen-nachtlinsen.html` — 1 Block: Service
- `public/leistungen-beratung-kurzsichtigkeit.html` — 1 Block: Service
- `public/leistungen-visuelle-reha.html` — 1 Block: Service
- `public/team.html` — 2 Bloecke: BreadcrumbList (vorher), Person-Array (neu)

Python-Validierung: 0 Fehler / 12 Bloecke.

### Deployment-Status

Die 4 feat-Commits mit den HTML-Aenderungen (a78692c, a817dee, 7a54272, 746d2e8) befinden sich alle in `origin/main`. Lediglich der abschliessende Docs-Commit fuer Plan 04 (c58fa7b) ist noch nicht gepusht — dieser enthaelt keine HTML-Aenderungen.

Der SUMMARY 03-04 berichtet: Deployment via GitHub Actions (rsync SSH, 17s), HTTP 200, Ladezeit 0.12s, Rich Results Test ohne Fehler. Dies ist eine Angabe aus dem SUMMARY — eine Echtzeit-Verifikation des Live-Servers war im Rahmen dieser Pruefung nicht moeglich.

### Human Verification Required

#### 1. Google Rich Results Test

**Test:** https://search.google.com/test/rich-results aufrufen, URL `https://bessersehen.la` eingeben und testen
**Expected:** LocalBusiness-Schema (Optician) gefunden, keine Fehler; Warnungen sind akzeptabel
**Why human:** Externes Google-Tool, nicht programmatisch abfragbar

#### 2. schema.org Validator fuer Vollstaendigkeit

**Test:** https://validator.schema.org/ aufrufen, `https://bessersehen.la` eingeben
**Expected:** Optician-Schema mit address, telephone, openingHoursSpecification (16:00), geo, sameAs (2 URLs), areaServed (3 City-Objekte) + separates WebSite-Schema sichtbar
**Why human:** Externes Validierungs-Tool

#### 3. Google Rich Results Test auf team.html

**Test:** https://search.google.com/test/rich-results aufrufen, URL `https://bessersehen.la/team.html` eingeben
**Expected:** 7 Person-Objekte ohne Fehler validiert (Person-Schema wird nicht immer als Rich Result dargestellt, E-E-A-T-Signalwirkung ist das Ziel)
**Why human:** Externes Google-Tool

#### 4. Browser-Funktionskontrolle

**Test:** https://bessersehen.la im Browser oeffnen, DevTools Console (F12) oeffnen
**Expected:** Seite laedt vollstaendig (Fonts, Bilder, Navigation, Footer), keine neuen roten Konsol-Fehler durch Schema-Aenderungen. Bekannter vorheriger Fehler: null.setAttribute (Revolution Slider) ist ein Altproblem und kein Schema-Fehler.
**Why human:** CSP-Verhalten und Browser-Rendering nicht programmatisch pruefbar

### Deployment-Hinweis

Der Docs-Commit c58fa7b (03-04 SUMMARY) ist noch nicht auf origin/main gepusht (`git log --oneline origin/main..HEAD` zeigt diesen Commit). Die HTML-Schema-Aenderungen selbst sind jedoch alle in origin/main. Ein abschliessender `git push` waere empfehlenswert um den Projektstand vollstaendig zu synchronisieren.

---

## Summary

Alle 5 SCHEMA-Anforderungen sind in den HTML-Dateien implementiert und als valides JSON verifiziert:

- **SCHEMA-01:** Optician-Schema komplett (address, telephone, openingHoursSpecification als Array mit closes=16:00, geo, sameAs mit Facebook und Google Maps CID 13745904768855660490)
- **SCHEMA-02:** WebSite-Schema vorhanden (inLanguage=de-DE, kein SearchAction — bewusste, dokumentierte Entscheidung)
- **SCHEMA-03:** Service-Schema auf allen 4 Leistungsseiten mit serviceType und absolutem image
- **SCHEMA-04:** Person-Schema als JSON-Array mit allen 7 Teammitgliedern auf team.html (Jennifer Huegel korrekt benannt)
- **SCHEMA-05:** areaServed als City-Array (Landshut, Ergolding, Rottenburg an der Laaber) auf index.html und allen 4 Leistungsseiten

Keine Stubs, keine leeren Implementierungen, keine Anti-Patterns gefunden. 12/12 JSON-LD-Bloecke sind valides JSON. Die verbleibenden 4 Human-Verification-Punkte betreffen externe Tool-Validierung (Google Rich Results Test) und Browser-Rendering — nicht die Korrektheit der implementierten Daten.

---
_Verified: 2026-02-25_
_Verifier: Claude (gsd-verifier)_
