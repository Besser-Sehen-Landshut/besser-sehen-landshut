# Phase 3: Schema - Research

**Researched:** 2026-02-25
**Domain:** JSON-LD Structured Data / schema.org for Local Business SEO
**Confidence:** HIGH

---

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions

- **openingHoursSpecification:** Kernzeiten Mo–Fr 09:00–16:00 (bestehender Fehler "18:00" muss korrigiert werden). Sa/So geschlossen. Ergänzung `availableByAppointment: true` oder Hinweis in description.
- **sameAs-Links:** Facebook `https://www.facebook.com/bessersehenlandshut/` + Google Maps CID-URL (CID aus Maps-Embed ableitbar)
- **areaServed:** City-Objekte: Landshut, Ergolding, Rottenburg a.d.L. — auf Optician-Schema UND auf allen Service-Schemas
- **Person-Schema:** Alle 7 Teammitglieder (Namen und Qualifikationen festgelegt in CONTEXT.md). `name`, `jobTitle`, `image` als absolute URL, `worksFor` Besser Sehen Landshut.
- **WebSite-Schema:** Ohne SearchAction (statische Seite), nur `@type: WebSite`, `name`, `url`, `inLanguage: de-DE`
- **Service-Schema:** `serviceType` ergänzen (Claude wählt aus Seiteninhalt), `image` = OG-Image der jeweiligen Seite
- **Umsetzung:** JSON-LD im `<head>` als `<script type="application/ld+json">`, absolute URLs für alle image-Felder

### Claude's Discretion

- Genaue jobTitle-Formatierung (Abkürzungen ausschreiben oder nicht)
- `serviceType`-Werte für die 4 Leistungsseiten (aus Seiteninhalt ableiten)
- Ob Person-Schema als eigenständiger `<script>`-Block oder in Optician-Schema eingebettet
- Technische Umsetzung von "nach Vereinbarung" (proprietäres Feld vs. description-Text)

### Deferred Ideas (OUT OF SCOPE)

Keine — Diskussion blieb im Phase-3-Scope.
</user_constraints>

---

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| SCHEMA-01 | Optician-Schema auf index.html enthält address, telephone, openingHoursSpecification, geo, sameAs | address/telephone/geo bereits vorhanden; openingHoursSpecification auf 16:00 korrigieren; sameAs um Maps-CID-URL erweitern |
| SCHEMA-02 | WebSite-Schema auf index.html (CONTEXT.md: ohne SearchAction, nur WebSite-Basics) | `@type: WebSite`, `name`, `url`, `inLanguage: de-DE` — separater `<script>`-Block neben Optician-Schema |
| SCHEMA-03 | Service-Schema auf allen 4 Leistungsseiten enthält serviceType und image | Bestehende Service-Schemas in-place erweitern: `serviceType` (Freitext, kein Controlled Vocabulary) + `image` (URL oder ImageObject) |
| SCHEMA-04 | Person-Schema für jedes Teammitglied auf team.html | Neuer `<script>`-Block auf team.html mit Array von 7 Person-Objekten; `worksFor` referenziert `@type: Organization` |
| SCHEMA-05 | areaServed in Optician-Schema als City-Objekte (Landshut + Nachbargemeinden) | `areaServed` als Array von `{"@type": "City", "name": "..."}` — `name` ist der einzige Pflichtfeldwert |
</phase_requirements>

---

## Summary

Phase 3 ist reines JSON-LD-Handwerk: kein neues Framework, keine externen Abhängigkeiten, kein Build-Prozess. Alle Schemas werden direkt in bestehende HTML-`<head>`-Bereiche eingebettet. Die schema.org-Typen sind stabil und gut dokumentiert.

Der Hauptwert liegt in der Korrektheit: Ein falscher Schlusswert (18:00 statt 16:00), ein fehlendes `@type` im `areaServed`-Objekt oder eine relative statt absoluter Bild-URL reichen aus, um Rich-Results-Eligibility zu verlieren. Google's Rich Results Test ist der Gate-Checker.

Die einzige nicht-triviale Entscheidung betrifft das Person-Schema auf team.html: separater `<script>`-Block (wartungsfreundlicher) vs. Einbettung als `employee`-Array im Optician-Schema (semantisch stärker verknüpft). Research empfiehlt separaten Block — einfacher zu debuggen, Google akzeptiert beide Varianten.

**Primary recommendation:** Jede Datei einzeln bearbeiten, danach sofort im Google Rich Results Test validieren. Keine Sammeländerung ohne Test-Checkpoint.

---

## Standard Stack

### Core

| Element | Version/Format | Purpose | Why Standard |
|---------|---------------|---------|--------------|
| JSON-LD | Aktuelle schema.org-Spec | Strukturierte Daten für Google | Von Google empfohlen, von Googlebot am zuverlässigsten gelesen |
| `<script type="application/ld+json">` | HTML-Standard | Einbettung im `<head>` | Kein DOM-Parsing nötig, unsichtbar für User, Googlebot-optimiert |
| schema.org/Optician | LocalBusiness-Subtyp | Augenoptiker als lokales Geschäft | Spezifischster Typ für Augenoptiker; erbt von MedicalBusiness → LocalBusiness |
| schema.org/Service | Direkte Eigenschaft | Leistungsbeschreibung | Standard für Services eines LocalBusiness |
| schema.org/Person | Direkte Eigenschaft | Teammitglieder | Standard für Personen-/Mitarbeiter-Markup |
| schema.org/WebSite | Direkte Eigenschaft | Website-Grunddaten | Unterstützt inLanguage, url, name |

### Supporting

| Element | Purpose | When to Use |
|---------|---------|-------------|
| `hasMap` Property | Direkte Google Maps URL als Karte | Ergänzend zu `sameAs` für Maps-CID |
| `@id` Property | Eindeutiger Identifier für Entity | Auf Optician-Schema bereits vorhanden (`https://bessersehen.la`) |
| `ImageObject` vs. URL für `image` | Erweitertes Bild-Markup | Einfache URL reicht; ImageObject nur wenn `width`/`height`/`caption` sinnvoll |

### Alternatives Considered

| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| Separater Person-`<script>`-Block | `employee` Array im Optician-Schema | Eingebettet semantisch stärker, separater Block wartungsfreundlicher — beide valide |
| `areaServed` als City-Array | `areaServed` als Text-Array `["Landshut", "Ergolding"]` | City-Objekte geben Google mehr Kontext; Text-Array wäre einfacher aber schwächer |
| `serviceType` als Freitext | Kein `serviceType` | serviceType ist SCHEMA-03-Pflicht; Freitext ist laut schema.org erlaubt |

---

## Architecture Patterns

### Recommended File Structure (Änderungen)

```
public/
├── index.html              # Optician-Schema erweitern + WebSite-Schema (neuer Block) hinzufügen
├── leistungen-kontaktlinsen.html        # Service-Schema: serviceType + image ergänzen
├── leistungen-nachtlinsen.html          # Service-Schema: serviceType + image ergänzen
├── leistungen-beratung-kurzsichtigkeit.html  # Service-Schema: serviceType + image ergänzen
├── leistungen-visuelle-reha.html        # Service-Schema: serviceType + image ergänzen
└── team.html               # Neuen Person-Schema-Block im <head> hinzufügen
```

### Pattern 1: Optician-Schema-Erweiterung (index.html)

**Was:** Bestehenden Schema-Block in-place erweitern — `openingHoursSpecification` korrigieren, `sameAs` erweitern, `areaServed` als City-Array hinzufügen.
**Wann:** Genau einmal, in index.html, Zeilen 60–92.

```json
// Source: https://schema.org/Optician + https://developers.google.com/search/docs/appearance/structured-data/local-business
{
  "@context": "https://schema.org",
  "@type": "Optician",
  "name": "Besser Sehen Landshut",
  "image": "https://bessersehen.la/bilder/startseite-hero.jpg",
  "@id": "https://bessersehen.la",
  "url": "https://bessersehen.la",
  "telephone": "+4987114228020",
  "priceRange": "€€",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Veldener Str. 16",
    "addressLocality": "Landshut",
    "postalCode": "84036",
    "addressCountry": "DE"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 48.5239261,
    "longitude": 12.1449762
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
      "opens": "09:00",
      "closes": "16:00"
    }
  ],
  "areaServed": [
    {"@type": "City", "name": "Landshut"},
    {"@type": "City", "name": "Ergolding"},
    {"@type": "City", "name": "Rottenburg an der Laaber"}
  ],
  "sameAs": [
    "https://www.facebook.com/bessersehenlandshut/",
    "https://www.google.com/maps?cid=13745904768855660490"
  ]
}
```

**Key change:** `openingHoursSpecification` wird zu einem Array (auch wenn nur ein Objekt drin ist) — expliziter und erweiterbar. Wert `"closes": "18:00"` wird auf `"16:00"` korrigiert.

### Pattern 2: WebSite-Schema (index.html, neuer Block)

**Was:** Separater `<script>`-Block direkt nach dem Optician-Block.
**Wann:** Nur einmal, in index.html.

```json
// Source: https://schema.org/WebSite
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Besser Sehen Landshut",
  "url": "https://bessersehen.la",
  "inLanguage": "de-DE"
}
```

**Note:** REQUIREMENTS.md erwähnt `SearchAction`, CONTEXT.md locked: OHNE SearchAction (statische Seite). CONTEXT.md hat Vorrang.

### Pattern 3: Service-Schema-Erweiterung (4 Leistungsseiten)

**Was:** Bestehende Service-Schemas in-place erweitern: `serviceType` + `image` + `areaServed` als City-Array.
**Wann:** Je einmal auf allen 4 Leistungsseiten.

```json
// Source: https://schema.org/Service
{
  "@context": "https://schema.org",
  "@type": "Service",
  "name": "Kontaktlinsenanpassung",
  "description": "...",
  "serviceType": "Kontaktlinsenanpassung",
  "image": "https://bessersehen.la/bilder/leistungen-kontaktlinsenversorgung-01.jpg",
  "provider": {
    "@type": "Optician",
    "name": "Besser Sehen Landshut",
    "url": "https://bessersehen.la"
  },
  "areaServed": [
    {"@type": "City", "name": "Landshut"},
    {"@type": "City", "name": "Ergolding"},
    {"@type": "City", "name": "Rottenburg an der Laaber"}
  ],
  "url": "https://bessersehen.la/leistungen-kontaktlinsen.html"
}
```

**serviceType-Werte (Empfehlung Claude's Discretion):**
- `leistungen-kontaktlinsen.html` → `"Kontaktlinsenanpassung"`
- `leistungen-nachtlinsen.html` → `"Orthokeratologie"`
- `leistungen-beratung-kurzsichtigkeit.html` → `"Myopie-Management"`
- `leistungen-visuelle-reha.html` → `"Visuelle Rehabilitation"`

**image-Werte** (aus bestehenden OG-Images der Seiten):
- Kontaktlinsen: `https://bessersehen.la/bilder/leistungen-kontaktlinsenversorgung-01.jpg`
- Nachtlinsen: `https://bessersehen.la/bilder/leistungen-nachtlinsen-01.jpg`
- Kurzsichtigkeit: `https://bessersehen.la/bilder/leistungen-beratung-kurzsichtigkeit-01.jpg`
- Visuelle Reha: `https://bessersehen.la/bilder/leistungen-visuelle-rehabilitation-01.jpg`

### Pattern 4: Person-Schema (team.html, neuer Block)

**Was:** Neuer `<script>`-Block im `<head>` von team.html mit allen 7 Person-Objekten.
**Wann:** Einmal auf team.html.

```json
// Source: https://schema.org/Person
[
  {
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "Andreas Polzer",
    "jobTitle": "Augenoptikermeister, Diplom-Kaufmann (FH)",
    "image": "https://bessersehen.la/bilder/team-andreas-polzer.webp",
    "worksFor": {
      "@type": "Organization",
      "name": "Besser Sehen Landshut",
      "url": "https://bessersehen.la"
    }
  },
  ...
]
```

**Alle 7 Personen:**
1. Andreas Polzer — Dipl.-Kaufmann (FH), Augenoptikermeister → `bilder/team-andreas-polzer.webp`
2. Christiane Schaucher — M.Sc. Vision Science & Business, Augenoptikermeisterin → `bilder/team-christiane-schaucher.webp`
3. Natascha Pfeuffer — B.Sc. Augenoptik/Optometrie, Augenoptikerin → `bilder/team-natascha-pfeuffer.webp`
4. Melanie Brand — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin → `bilder/team-melanie-brand.webp`
5. Jennifer Hügel — B.Sc. Augenoptik/Optometrie, Augenoptikerin → `bilder/team-jenny-huegel.webp`
6. Maria Handke — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin → `bilder/team-maria-handke.webp`
7. Nicole Polzer — Augenoptikermeisterin, Staatl. gepr. Augenoptikerin → `bilder/team-nicole-polzer.webp`

**ACHTUNG Bilddatei-Bug:** `team-jenny-huegel.jpg` hat `alt="Maria Schüler"` im HTML — Fehler im Markup. Im Person-Schema den korrekten Namen "Jennifer Hügel" verwenden, Bilddatei ist korrekt (team-jenny-huegel.webp).

### Anti-Patterns to Avoid

- **`"areaServed": "Landshut"` als String lassen:** Bleibt als Plain-Text ohne semantischen Wert. Ersetzen durch City-Array.
- **`openingHoursSpecification` ohne Array:** Einzelobjekt ohne Array ist valide aber nicht erweiterbar. Array ist best practice.
- **Relative URLs in `image`:** z.B. `"image": "bilder/team-..."` — Google folgt keinen relativen URLs in JSON-LD. Immer absolute `https://bessersehen.la/...`.
- **SearchAction trotz statischer Seite:** REQUIREMENTS.md erwähnt SearchAction, CONTEXT.md lockt "ohne SearchAction" — CONTEXT.md ist verbindlich.
- **Person-Schema ohne `worksFor`:** Verliert die Verknüpfung zum Unternehmen. `worksFor` mit `@type: Organization` ist für Knowledge Graph wichtig.

---

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Schema-Validierung | Eigenen Validator | Google Rich Results Test (https://search.google.com/test/rich-results) | Prüft exakt das, was Google crawlt |
| CID-zu-Decimal-Konvertierung | Manuell | `python3 -c "print(int('0xbec3438eeffd57ca', 16))"` = 13745904768855660490 | Bereits berechnet |
| JSON-LD-Syntax-Check | Manuell | schema.org Validator (https://validator.schema.org/) | Findet Typ-Fehler, falsche Property-Namen |

**Key insight:** Bei reinem JSON-LD gibt es nichts zu "hand-rollen". Das Risiko liegt in falschen Werten (falsche Uhrzeit, falscher Name), nicht in Code-Komplexität.

---

## Common Pitfalls

### Pitfall 1: Uhrzeitfehler im openingHoursSpecification
**What goes wrong:** Bestehendes Schema hat `"closes": "18:00"` — falsch laut CONTEXT.md (korrekt: 16:00). Bleibt bestehen wenn nicht explizit ersetzt.
**Why it happens:** Ursprünglicher Fehler bei der Schema-Erstellung.
**How to avoid:** Wert `"closes": "18:00"` → `"closes": "16:00"` explizit ändern, nicht nur hinzufügen.
**Warning signs:** Rich Results Test zeigt Diskrepanz zu sichtbaren Öffnungszeiten auf der Seite.

### Pitfall 2: Google Maps CID — Hex vs. Decimal
**What goes wrong:** CID aus Maps-Embed ist hex (`0xbec3438eeffd57ca`). Google Maps URL erwartet Decimal.
**Why it happens:** Unterschiedliche Repräsentationen in Maps-Embed vs. direkter Maps-URL.
**How to avoid:** Decimal-Wert: **13745904768855660490**. URL: `https://www.google.com/maps?cid=13745904768855660490`
**Warning signs:** Maps-Link öffnet falsches oder kein Business.

### Pitfall 3: REQUIREMENTS.md vs. CONTEXT.md Widerspruch (SearchAction)
**What goes wrong:** REQUIREMENTS.md (SCHEMA-02) erwähnt "mit SearchAction". CONTEXT.md locked: "Ohne SearchAction".
**Why it happens:** Requirements wurden vor der Discuss-Phase geschrieben; Discuss-Phase hat die Entscheidung präzisiert.
**How to avoid:** CONTEXT.md hat immer Vorrang über REQUIREMENTS.md. WebSite-Schema ohne SearchAction implementieren.

### Pitfall 4: Bildpfad-Fehler bei Jennifer Hügel
**What goes wrong:** HTML-Datei hat `alt="Maria Schüler"` für `team-jenny-huegel.jpg` — irreführend.
**Why it happens:** Fehler im ursprünglichen HTML.
**How to avoid:** Im Person-Schema korrekt `"name": "Jennifer Hügel"` verwenden; Bilddatei `team-jenny-huegel.webp` ist korrekt benannt.

### Pitfall 5: areaServed auf Service-Schemas vergessen
**What goes wrong:** SCHEMA-05 betrifft primär das Optician-Schema, aber CONTEXT.md locked: areaServed auch auf allen Service-Schemas aktualisieren (aktuell alle `"areaServed": "Landshut"` als String).
**Why it happens:** Lokalisierungsschritt bei 4 Dateien leicht zu übersehen.
**How to avoid:** Service-Schema-Task explizit areaServed-Update einschließen.

### Pitfall 6: Person-Schema mit WebP-Bildern — Accessibility
**What goes wrong:** WebP-URLs in `image` sind korrekt, aber nur wenn WebP tatsächlich öffentlich abrufbar ist.
**Why it happens:** WebP-Dateien wurden bereits in Phase 0 erzeugt und sind deploybar.
**How to avoid:** Nach Deployment prüfen ob `https://bessersehen.la/bilder/team-andreas-polzer.webp` korrekt antwortet (HTTP 200).

---

## Code Examples

Verified patterns from schema.org and Google documentation:

### OpeningHoursSpecification Array (korrekte Schreibweise)
```json
// Source: https://schema.org/OpeningHoursSpecification
"openingHoursSpecification": [
  {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
    "opens": "09:00",
    "closes": "16:00"
  }
]
```
Format: `"HH:MM"` (24-Stunden). Keine Sekunden nötig (obwohl `"09:00:00"` auch valide ist). `dayOfWeek` als Array für mehrere Tage.

### areaServed als City-Array
```json
// Source: https://schema.org/City + https://schema.org/areaServed
"areaServed": [
  {"@type": "City", "name": "Landshut"},
  {"@type": "City", "name": "Ergolding"},
  {"@type": "City", "name": "Rottenburg an der Laaber"}
]
```
`name` ist der einzige Pflichtfeldwert. Kein weiteres Attribut nötig.

### sameAs mit Google Maps CID
```json
// Source: https://schemavalidator.org/guides/local-seo-schema-markup
"sameAs": [
  "https://www.facebook.com/bessersehenlandshut/",
  "https://www.google.com/maps?cid=13745904768855660490"
]
```
CID Decimal: 13745904768855660490 (aus Hex `0xbec3438eeffd57ca`).

### WebSite-Schema (minimal, ohne SearchAction)
```json
// Source: https://schema.org/WebSite
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Besser Sehen Landshut",
  "url": "https://bessersehen.la",
  "inLanguage": "de-DE"
}
```

### Person-Schema für Teammitglied
```json
// Source: https://schema.org/Person
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Christiane Schaucher",
  "jobTitle": "Augenoptikermeisterin, M.Sc. Vision Science & Business",
  "image": "https://bessersehen.la/bilder/team-christiane-schaucher.webp",
  "worksFor": {
    "@type": "Organization",
    "name": "Besser Sehen Landshut",
    "url": "https://bessersehen.la"
  }
}
```

---

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| `areaServed: "Landshut"` (String) | `areaServed: [{"@type": "City", ...}]` (City-Objekte) | Schema.org-Empfehlung aktuell | Google kann Einzugsgebiet als Entität verstehen |
| Einzelnes `openingHoursSpecification`-Objekt | Array von Objekten | Best Practice | Erweiterbar für Sonderzeiten/Feiertage |
| Kein Person-Schema auf Team-Seiten | Person-Schema mit `worksFor` | Zunehmend empfohlen für Knowledge Graph | Stärkt E-E-A-T-Signale |
| Google Analytics UA | Umami (bereits Phase 1 erledigt) | UA abgeschaltet Juli 2023 | Nicht relevant für Phase 3 |

**Deprecated/outdated:**
- `openingHours` (simples String-Format wie `"Mo-Fr 09:00-18:00"`): Weiter valide, aber `openingHoursSpecification` ist präziser und Google-bevorzugt für Rich Results.
- `SearchAction` auf WebSite ohne echte Suchfunktion: Kann zu Google-Penalty führen wenn nicht funktionsfähig. Bewusst weggelassen per CONTEXT.md-Entscheidung.

---

## Open Questions

1. **`availableByAppointment`-Feld für Öffnungszeiten**
   - What we know: CONTEXT.md erwähnt dieses Feld als mögliche Ergänzung; schema.org kennt kein `availableByAppointment` auf LocalBusiness/Optician direkt
   - What's unclear: Ob ein proprietäres/nicht-standardisiertes Feld Probleme verursacht, oder ob `description` der bessere Ort ist
   - Recommendation: "Nach Vereinbarung" besser in `description`-Text des Optician-Schemas erwähnen; kein proprietäres Feld im JSON-LD

2. **Ob `hasMap` zusätzlich zu `sameAs` sinnvoll ist**
   - What we know: `hasMap` ist eine valide schema.org-Property auf Place-Typen; erwartet Google Maps URL
   - What's unclear: Ob Google `hasMap` oder `sameAs` stärker wertet für Knowledge Panel
   - Recommendation: Maps-CID in `sameAs` ist ausreichend und gut dokumentiert; `hasMap` optional ergänzen

3. **Rottenburg an der Laaber vs. Rottenburg a.d.L. im City-Namen**
   - What we know: CONTEXT.md schreibt "Rottenburg a.d.L." als Abkürzung
   - What's unclear: Ob Google die Abkürzung oder den ausgeschriebenen Namen bevorzugt
   - Recommendation: Ausgeschriebenen Namen `"Rottenburg an der Laaber"` verwenden — konsistenter mit Google Maps-Einträgen

---

## Sources

### Primary (HIGH confidence)
- https://schema.org/Optician — Typ-Hierarchie, verfügbare Properties
- https://schema.org/OpeningHoursSpecification — dayOfWeek-Werte, opens/closes-Format, Array-Nutzung
- https://schema.org/Service — serviceType (Freitext erlaubt), image-Property
- https://schema.org/Person — jobTitle, worksFor, image
- https://schema.org/WebSite — inLanguage, url, name; keine SearchAction-Pflicht
- https://schema.org/City — @type City, name als einziger Pflichtfeldwert
- https://developers.google.com/search/docs/appearance/structured-data/local-business — Google-Anforderungen: name + address als Required; geo, openingHoursSpecification, telephone als Recommended
- https://developers.google.com/search/docs/appearance/structured-data/organization — sameAs-Empfehlung für Knowledge Panel

### Secondary (MEDIUM confidence)
- https://schemavalidator.org/guides/local-seo-schema-markup — Google Maps CID-URL-Format (`https://www.google.com/maps?cid=DECIMAL`) für sameAs
- WebSearch-Ergebnis bestätigt durch multiple Quellen: beide Formate `maps.google.com/?cid=` und `google.com/maps?cid=` werden verwendet

### Tertiary (LOW confidence)
- Empfehlung zu `hasMap` vs. `sameAs` für Maps-CID: nur durch einzelne Web-Quellen gestützt, nicht durch Google-offizielle Doku

---

## Metadata

**Confidence breakdown:**
- Standard Stack: HIGH — schema.org direkt konsultiert, Google-Doku direkt konsultiert
- Architecture: HIGH — klare in-place-Ergänzungen, alle Dateien geprüft, Code-Beispiele aus offiziellen Quellen
- Pitfalls: HIGH (Uhrzeit-Bug, CID-Konvertierung, CONTEXT-Widerspruch) — direkt aus Codebase-Analyse und Entscheidungsdokumentation; MEDIUM für Pitfall 6 (WebP-Serving nach Deploy)

**Research date:** 2026-02-25
**Valid until:** 2026-04-25 (schema.org-Specs sind sehr stabil; 60 Tage Gültigkeit)
