# Phase 3: Schema - Context

**Gathered:** 2026-03-03
**Status:** Ready for planning

<domain>
## Phase Boundary

Alle strukturierten JSON-LD-Daten auf bessersehen.la so vervollständigen, dass Google das Geschäft als lokalen Augenoptiker vollständig versteht: Optician-Schema (Öffnungszeiten, sameAs, areaServed), WebSite-Schema, Service-Schema (serviceType + image), Person-Schema (alle Teammitglieder). Keine neuen Seiten, kein Content-Ausbau — das ist Phase 4.

</domain>

<decisions>
## Implementation Decisions

### Öffnungszeiten (openingHoursSpecification)
- Kernzeiten: Montag–Freitag 09:00–16:00 Uhr (HTML ist korrekt, Schema-Wert "18:00" ist ein Fehler → korrigieren)
- Länger nach Vereinbarung möglich → `"openingHoursSpecification"` zeigt Kernzeiten, zusätzlich `"makesOffer"` oder proprietäres Feld `"availableByAppointment": true` als Ergänzung
- Samstag/Sonntag: geschlossen (so im HTML)

### sameAs-Links
- Facebook bereits vorhanden: `https://www.facebook.com/bessersehenlandshut/`
- Google Business Profil existiert → URL aus dem Maps-Embed im HTML ableiten (CID: `0xbec3438eeffd57ca`, Koordinaten 48.5239261/12.1449762)
- Kein Instagram

### areaServed
- Als City-Objekte: Landshut (Hauptstadt), Ergolding, Rottenburg a.d.L.
- Auf Optician-Schema UND auf allen Service-Schemas (aktuell nur "Landshut" als String)

### Person-Schema (team.html)
- Alle 7 Teammitglieder erfassen:
  1. Andreas Polzer — Dipl.-Kaufmann (FH), Augenoptikermeister
  2. Christiane Schaucher — M.Sc. Vision Science & Business, Augenoptikermeisterin
  3. Natascha Pfeuffer — B.Sc. Augenoptik/Optometrie, Augenoptikerin
  4. Melanie Brand — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin
  5. Jennifer Hügel — B.Sc. Augenoptik/Optometrie, Augenoptikerin
  6. Maria Handke — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin
  7. Nicole Polzer — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin
- Jede Person bekommt: `name`, `jobTitle`, `image` (bilder/team-*.webp)
- Fotos als absolute URLs: `https://bessersehen.la/bilder/team-*.webp`
- Alle Personen als `"worksFor"` Besser Sehen Landshut

### WebSite-Schema
- Ohne SearchAction (statische Seite hat keine Suchfunktion)
- Nur `@type: WebSite`, `name`, `url`, `inLanguage: de-DE`

### Service-Schema (4 Leistungsseiten)
- `serviceType` ergänzen (Claude wählt passende Werte aus Seiteninhalt)
- `image` ergänzen (jeweils das OG-Image der Seite als Wert)

### Claude's Discretion
- Genaue jobTitle-Formatierung (Abkürzungen ausschreiben oder nicht)
- `serviceType`-Werte für die 4 Leistungsseiten (aus Seiteninhalt ableiten)
- Ob Person-Schema als eigenständiger `<script>`-Block oder in Optician-Schema eingebettet
- Technische Umsetzung von "nach Vereinbarung" (proprietäres Feld vs. description-Text)

</decisions>

<code_context>
## Existing Code Insights

### Reusable Assets
- Optician-Schema in index.html (Zeile 60–92): Grundstruktur vorhanden, gezielt erweitern
- Service-Schema auf allen 4 Leistungsseiten: Zeile 30–44 je Datei, nur `serviceType` + `image` fehlen
- Breadcrumb-Schema auf team.html: vorhanden, Person-Schema als neuer `<script>`-Block daneben

### Established Patterns
- JSON-LD immer im `<head>` als `<script type="application/ld+json">`
- Absolute URLs für alle `image`-Felder (https://bessersehen.la/...)
- WebP-Bilder existieren für alle Teammitglieder unter `bilder/team-*.webp`

### Integration Points
- index.html: Optician-Schema erweitern + WebSite-Schema als neuer Block hinzufügen
- leistungen-*.html (4 Dateien): Service-Schema in-place ergänzen
- team.html: Person-Schema als neuer `<script>`-Block im `<head>`

</code_context>

<specifics>
## Specific Ideas

- Öffnungszeiten-Diskrepanz: Schema hatte "closes": "18:00" — das ist ein bestehender Fehler, muss auf "16:00" korrigiert werden
- Google Business CID aus Embed: `!1s0x0%3A0xbec3438eeffd57ca!2sBesser%20Sehen%20Landshut` — Maps-URL daraus konstruieren
- Bilddatei-Mapping: team-jenny-huegel.jpg hat `alt="Maria Schüler"` (Fehler im HTML) — im Person-Schema den korrekten Namen Jennifer Hügel verwenden

</specifics>

<deferred>
## Deferred Ideas

Keine — Diskussion blieb im Phase-3-Scope.

</deferred>

---

*Phase: 03-schema*
*Context gathered: 2026-03-03*
