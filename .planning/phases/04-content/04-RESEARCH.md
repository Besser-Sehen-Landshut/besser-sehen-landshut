# Phase 4: Content - Research

**Researched:** 2026-02-25
**Domain:** German-language SEO content authoring for static HTML pages (Augenoptik / Optometrie)
**Confidence:** HIGH

<user_constraints>
## User Constraints (from CONTEXT.md)

### Locked Decisions

**FAQ-Abschnitt (Nachtlinsen)**
- Eigener FAQ-Block am Ende der Seite (unter dem bestehenden Inhalt) — klar scanbar, gut für Google Featured Snippets
- Themen: Kosten & Kasse, Sicherheit, Eignung
- Kassenleistung: Nein — Nachtlinsen sind Privatleistung, das wird klar und ehrlich kommuniziert
- Keine Produktnamen (DreamLens, Paragon CRT) — allgemein "formstabile Nachtlinsen" oder "Orthokeratologie-Linsen"

**Alleinstellungsmerkmal Myopie-Management**
- Augenlängen-Messung ist DAS USP von Besser Sehen Landshut
- Prominente eigene H2-Sektion auf leistungen-beratung-kurzsichtigkeit.html
- Formulierung betont: "Augenlängen-Messung bei Besser Sehen Landshut" — suchbar und differenzierend
- Dieses Feature fehlt bei normalen Augenoptikern — das klar herausstellen

**Ton und Schreibstil**
- Bestehenden Stil beibehalten: zugänglich, patientenorientiert, keine Fachbegriffe ohne Erklärung
- Zielgruppe: Patienten ohne Vorwissen — kein Mediziner-Jargon, aber trotzdem kompetent
- Inhalte werden aus öffentlichen Fachquellen recherchiert und formuliert (Berufsverbände, Kliniken, Fachzeitschriften)

**Ortsnennung**
- "Landshut" natürlich eingebettet auf allen 4 Leistungsseiten (mindestens einmal je Seite)
- Ergolding und Rottenburg an der Laaber auf mehreren Leistungsseiten (mindestens 2-3 Seiten)
- Formulierung natürlich: "Patienten aus Landshut, Ergolding und Rottenburg" oder "In Landshut und Umgebung"
- Kein Keyword-Stuffing — Ortsangaben wirken als normaler Satz, nicht als Liste

**team.html**
- Bleibt unverändert — team.html-Ausbau ist für diese Phase bewusst verschoben
- CONT-05 (300+ Wörter team.html) wird als deferred Requirement geführt

### Claude's Discretion
- Gliederung und Reihenfolge der neuen Abschnitte je Seite
- Exakte Formulierungen, Zwischenüberschriften, Übergangssätze
- Welche öffentlichen Quellen für die Recherche genutzt werden
- Wie viel jede Seite über 800 Wörter hinausgeht

### Deferred Ideas (OUT OF SCOPE)
- CONT-05: team.html Ausbau auf 300+ Wörter — verschoben, bis Inhalt je Person vorliegt
- Produktnamen (DreamLens, Paragon CRT) — kann später hinzugefügt werden wenn gewünscht
</user_constraints>

<phase_requirements>
## Phase Requirements

| ID | Description | Research Support |
|----|-------------|-----------------|
| CONT-01 | leistungen-nachtlinsen.html hat 800+ Wörter (inkl. FAQ zu Kosten/Kasse) | Current: 275 words. Needs ~525 new words. FAQ block at end covers cost/kasse/sicherheit/eignung. H2-Fragen-Stil bereits vorhanden. |
| CONT-02 | leistungen-beratung-kurzsichtigkeit.html hat 800+ Wörter | Current: 177 words. Needs ~623 new words. USP Augenlängen-Messung als H2-Sektion. Zielgruppe Kinder/Eltern. |
| CONT-03 | leistungen-visuelle-reha.html hat 800+ Wörter | Current: 185 words. Needs ~615 new words. Themen: Zielgruppen, Hilfsmittel-Typen, Ablauf, MVZ-Kooperation. |
| CONT-04 | leistungen-kontaktlinsen.html hat 800+ Wörter | Current: 320 words. Needs ~480 new words. Gut strukturiert, ergänzend: Linsentypen, Eignung schwieriger Fälle, Nachsorge. |
| CONT-05 | team.html Spezialisierungen/Qualifikationen (DEFERRED) | Out of scope this phase per locked decisions. |
| CONT-06 | Ergolding und Rottenburg a.d.L. auf mindestens einer Seite als Einzugsgebiet | Already in Service-Schema areaServed; needs HTML-visible mention in body text on 2-3 pages. |
| META-01 | meta name="keywords" auf jeder der 8 Seiten individuell | All 8 pages currently share identical string. Simple find/replace in each <head> section. |
</phase_requirements>

## Summary

Phase 4 is a pure content-authoring phase: no new libraries, no build tools, no JavaScript changes. The work is editing static HTML files to add German-language body text, then updating a single meta tag in eight files. There is no framework, no package to install, and no tooling involved beyond a text editor and a word-count verification step.

The four service pages are severely under their 800-word target. Current word counts extracted from the live HTML (visible content only, excluding nav/header/footer): leistungen-nachtlinsen.html has 275 words (gap: 525), leistungen-beratung-kurzsichtigkeit.html has 177 words (gap: 623), leistungen-visuelle-reha.html has 185 words (gap: 615), leistungen-kontaktlinsen.html has 320 words (gap: 480). All four pages share an identical HTML structure; new content inserts as additional `<h2>` + `<p>` blocks inside `<div role="main">` before the closing `</div>` of `col-lg-12`.

The meta keywords task (META-01) is mechanical: all 8 pages carry the same 8-word string and need page-specific replacements in `<head>`.

**Primary recommendation:** Write each page's content block as a self-contained set of `<h2>` + `<p>` HTML fragments, insert before the existing CTA section, verify word count with a simple script, then update meta keywords across all 8 files in a single pass.

## Standard Stack

### Core
| Library | Version | Purpose | Why Standard |
|---------|---------|---------|--------------|
| Plain HTML | — | Content authoring | No CMS; direct file editing is the established pattern across all 8 pages |
| Git | system | Version control and deploy trigger | rsync deploy via GitHub Actions already configured |

### Supporting
| Library | Version | Purpose | When to Use |
|---------|---------|---------|-------------|
| Python 3 (stdlib) | system | Word count verification script | Quick check: strip HTML tags, count words, confirm 800+ per page |

### Alternatives Considered
| Instead of | Could Use | Tradeoff |
|------------|-----------|----------|
| Inline `<h2>/<p>` | `<details>/<summary>` for FAQ | `<details>` is semantic but not a standard pattern on this site; H2+P matches existing style |
| Python word count | `wc -w` on stripped text | Python strip is more accurate (ignores HTML attrs); either works |

**Installation:** None required.

## Architecture Patterns

### Recommended Project Structure

No structural changes. All edits are in-place within:
```
public/
├── leistungen-nachtlinsen.html         # CONT-01: insert after line ~354
├── leistungen-beratung-kurzsichtigkeit.html  # CONT-02: insert after line ~348
├── leistungen-visuelle-reha.html       # CONT-03: insert after line ~349
├── leistungen-kontaktlinsen.html       # CONT-04: insert after line ~417
├── index.html                          # META-01: update meta keywords line 11
├── leistungen.html                     # META-01: update meta keywords line 11
├── team.html                           # META-01: update meta keywords line 11
└── kontakt.html                        # META-01: update meta keywords line 11
```

### Pattern 1: Content Block Insertion (Service Pages)

**What:** New H2+P content blocks are inserted into the `<div class="col-lg-12 order-1 order-lg-2">` container, after the existing paragraphs and lightbox gallery, before the closing `</div>` of that container. The `<section class="bg-color-primary">` CTA block immediately follows.

**When to use:** All 4 service page additions.

**Example (established pattern from existing pages):**
```html
<!-- Insert before closing </div> of col-lg-12 block -->
<h2 class="text-color-primary font-weight-bolder text-transform-none mb-2">Für wen ist [Leistung] geeignet?</h2>
<p class="mb-5">[Paragraph text. City mention here: Patienten aus Landshut, Ergolding und Rottenburg a.d.L. nutzen dieses Angebot...]</p>

<h2 class="text-color-primary font-weight-bolder text-transform-none mb-2">[Weitere Frage]</h2>
<p class="mb-5">[Further paragraph]</p>
```

The H2 classes `text-color-primary font-weight-bolder text-transform-none mb-2` and P class `mb-5` are used verbatim across all existing service pages. The FAQ block on nachtlinsen follows the same H2+P pattern (no `<dl>` or accordion — plain H2 questions, P answers).

### Pattern 2: FAQ Block (Nachtlinsen only)

**What:** A dedicated FAQ section appended after the expanded content blocks. Uses H2 for each question, P for the answer. This is the simplest valid approach and consistent with existing site style.

**Example:**
```html
<h2 class="text-color-primary font-weight-bolder text-transform-none mb-2">Werden die Kosten für Nachtlinsen von der Krankenkasse übernommen?</h2>
<p class="mb-5">Nachtlinsen (Orthokeratologie) sind eine Privatleistung. Die gesetzliche Krankenversicherung übernimmt die Kosten in der Regel nicht. ...</p>

<h2 class="text-color-primary font-weight-bolder text-transform-none mb-2">Ist das Tragen von Nachtlinsen sicher?</h2>
<p class="mb-5">...</p>

<h2 class="text-color-primary font-weight-bolder text-transform-none mb-2">Ab welchem Alter können Nachtlinsen getragen werden?</h2>
<p class="mb-5">...</p>
```

### Pattern 3: Meta Keywords Update

**What:** Replace the identical `content="..."` attribute of `<meta name="keywords">` on line 11 of each of the 8 HTML files with a page-specific value.

**Current identical value (all 8 pages):**
```html
<meta name="keywords" content="Besser Sehen, Besser Sehen Landshut, Tageslinsen, Monatslinsen, Nachtlinsen, Gleitsichtlinsen, weiche Linsen, harte Linsen" />
```

**Target:** Each page gets 5-10 keywords relevant to that page's content. The `/>` self-closing form must be preserved (matches existing style). Example for nachtlinsen:
```html
<meta name="keywords" content="Nachtlinsen, Orthokeratologie, Ortho-K, Nachtlinsen Landshut, tagsüber ohne Brille, Kurzsichtigkeit korrigieren, Nachtlinsen Kinder, Besser Sehen Landshut" />
```

### Anti-Patterns to Avoid

- **Keyword stuffing in H2 tags:** Don't write H2s like "Nachtlinsen Landshut günstiger Preis Orthokeratologie". Write natural German questions as H2s.
- **Duplicate location paragraphs:** Each page needs Ergolding/Rottenburg mentioned but only once, integrated naturally. Not a list, not repeated multiple times on the same page.
- **Breaking the lightbox gallery block:** The existing `<div class="lightbox">` and card grid must not be disrupted. Insert new H2+P blocks either before the lightbox (not possible — they follow the intro paragraphs) or after the lightbox, before the closing `</div>` of `col-lg-12`.
- **Using `<dl>/<dt>/<dd>` for FAQ:** This site has no CSS for definition lists and no precedent. H2+P is the correct pattern here.
- **Changing H2 class attributes:** Every H2 on every service page uses `class="text-color-primary font-weight-bolder text-transform-none mb-2"`. Deviate from this and the visual style breaks.

## Don't Hand-Roll

| Problem | Don't Build | Use Instead | Why |
|---------|-------------|-------------|-----|
| Word count verification | Custom HTML parser | Python `re.sub` strip tags + `.split()` | Already verified to work (see methodology below); fast and sufficient |
| FAQ accordion | Custom JS toggle | Plain H2+P | Adds JS dependency, breaks existing style, not needed for Google Featured Snippets |
| Content templating | Jinja/Handlebars | Direct HTML editing | No build pipeline; overhead not justified for 4 files |

**Key insight:** This is a content sprint, not a feature build. The HTML infrastructure (schema, CSS, JS) is already complete. The only task is authoring German text and placing it in the correct HTML location.

## Common Pitfalls

### Pitfall 1: Inserting Content in Wrong Location
**What goes wrong:** New H2+P blocks are placed inside the `<div class="lightbox">` gallery container, or after the `</div>` closing the `col-lg-12`, which puts text outside the layout column.
**Why it happens:** The nesting depth is significant in these files — the target insertion point is a closing `</div>` at indentation level 6.
**How to avoid:** The insertion point is always immediately before the `</div>` that closes `<div class="col-lg-12 order-1 order-lg-2">`. On each file this appears just before `</div>` closing `<div class="row">` closing `<div class="container py-3">`.
**Warning signs:** If the inserted text has no visual separation from the CTA section, it was inserted one level too late.

### Pitfall 2: Word Count Counts Navigation/Footer Words
**What goes wrong:** A naive word count of the entire HTML file or even a broad `<body>` extract counts nav links, footer text ("Adresse", "Telefon", "Öffnungszeiten"), and JS/CSS content as words, inflating the apparent count.
**Why it happens:** Stripping HTML tags doesn't automatically remove boilerplate regions.
**How to avoid:** Count only content within `<div role="main">`, specifically the `col-lg-12` content column. The Python script used in research (strip tags within the `role="main"` div) gives reliable counts. A 800-word target means 800 words in that region alone.
**Warning signs:** Claimed word count is suspiciously high (e.g. 500+) before any new content is added.

### Pitfall 3: Ortsnennung Sounds Like a List
**What goes wrong:** "Wir betreuen Patienten aus Landshut, Ergolding, Rottenburg an der Laaber, Altdorf, Essenbach..." reads as a keyword list, not natural copy.
**Why it happens:** Easy to add place names mechanically.
**How to avoid:** Embed the location in a meaningful sentence with context. Example: "Patienten aus Landshut und den umliegenden Gemeinden wie Ergolding und Rottenburg an der Laaber kommen zu uns für..." — the "umliegenden Gemeinden" frame makes it natural.

### Pitfall 4: FAQ Violates Honest Communication Policy
**What goes wrong:** FAQ softens the Privatleistung fact by hedging ("In manchen Fällen kann...") instead of clearly stating it.
**Why it happens:** Reluctance to state a negative fact directly.
**How to avoid:** The locked decision is explicit: Nachtlinsen = Privatleistung, klar und ehrlich. The answer should say directly that GKV does not cover it as a rule, and what the typical cost range looks like (if known). Uncertainty about exact prices should say "individuell, bitte Beratungsgespräch" rather than give a hedged number.

### Pitfall 5: Mismatched mb-2 vs mb-5 on Paragraphs
**What goes wrong:** Using `mb-2` on the last paragraph before a heading looks cramped; using `mb-5` in the middle of flowing text creates gaps.
**Why it happens:** Mechanical copying of class without thinking about spacing.
**How to avoid:** The established pattern on all service pages: paragraphs before a section break get `mb-5`, paragraphs mid-section get `mb-2`. The final paragraph before the CTA section uses `mb-5`.

## Code Examples

### Verified Word Count Script (from research)
```python
# Source: validated against all 4 service pages
import re

def count_main_words(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()
    match = re.search(r'<div role="main".*?</div>\s*<footer', content, re.DOTALL)
    if match:
        html = match.group(0)
        text = re.sub(r'<[^>]+>', ' ', html)
        text = re.sub(r'\s+', ' ', text).strip()
        return len(text.split())
    return None
```

Verified results (current state, before Phase 4):
- leistungen-nachtlinsen.html: **275 words**
- leistungen-beratung-kurzsichtigkeit.html: **177 words**
- leistungen-visuelle-reha.html: **185 words**
- leistungen-kontaktlinsen.html: **320 words**

### Insertion Point Pattern (per file)

**leistungen-nachtlinsen.html** — insert before line 355:
```html
						</div>
					</div>
				</div>

				<section class="bg-color-primary">
```
New H2+P blocks go immediately before the `</div>` that is at the start of the above excerpt (closing `col-lg-12`).

**leistungen-beratung-kurzsichtigkeit.html** — insert before line 348 (same pattern).

**leistungen-visuelle-reha.html** — insert before line 349 (same pattern).

**leistungen-kontaktlinsen.html** — insert before line 417 (same pattern, after the gallery lightbox block).

### Meta Keywords — Current Identical Value
```html
<!-- All 8 pages currently: -->
<meta name="keywords" content="Besser Sehen, Besser Sehen Landshut, Tageslinsen, Monatslinsen, Nachtlinsen, Gleitsichtlinsen, weiche Linsen, harte Linsen" />
```

### Proposed Page-Specific Keywords

| Page | Proposed keywords (5-10) |
|------|--------------------------|
| index.html | Augenoptiker Landshut, Besser Sehen Landshut, Kontaktlinsen Landshut, Nachtlinsen Landshut, Myopie-Management, Spezialist Optometrie |
| leistungen.html | Leistungen Augenoptiker Landshut, Kontaktlinsenanpassung, Nachtlinsen Orthokeratologie, Myopie-Management, visuelle Rehabilitation, Sehberatung Landshut |
| leistungen-nachtlinsen.html | Nachtlinsen Landshut, Orthokeratologie Landshut, Ortho-K, tagsüber ohne Brille, Nachtlinsen Kinder, Myopie bremsen, formstabile Kontaktlinsen |
| leistungen-beratung-kurzsichtigkeit.html | Myopie-Management Landshut, Kurzsichtigkeit Kinder Landshut, Augenlängen-Messung, Kurzsichtigkeit verlangsamen, Myopie-Kontrolle, Beratung Kurzsichtigkeit |
| leistungen-visuelle-reha.html | Visuelle Rehabilitation Landshut, Sehminderung Hilfsmittel, Lupen Lesegeräte Landshut, Makuladegeneration Sehhilfen, Spezialbrille Sehschwäche, Low Vision |
| leistungen-kontaktlinsen.html | Kontaktlinsenanpassung Landshut, Kontaktlinsen Spezialversorgung, 3D-Hornhautvermessung, Videospaltlampe, harte Kontaktlinsen, Tageslinsen Monatslinsen Landshut |
| team.html | Team Besser Sehen Landshut, Augenoptiker Team, Kontaktlinsen Experte Landshut, Optometristin Landshut |
| kontakt.html | Kontakt Besser Sehen Landshut, Augenoptiker Landshut Adresse, Termin Kontaktlinsen, Veldener Straße Landshut |

## State of the Art

| Old Approach | Current Approach | When Changed | Impact |
|--------------|------------------|--------------|--------|
| Thin content (200-300 words) | 800+ words with topical depth | Phase 4 goal | Google can index meaningful content; E-E-A-T signals improve |
| Identical meta keywords | Page-specific keywords | Phase 4 goal | Minor direct SEO value; shows intent and is not harmful |
| No FAQ | FAQ block with Kassenleistungs-Info | Phase 4 goal | Google Featured Snippet eligibility for "Nachtlinsen Kosten" queries |

**Note on meta keywords:** Google has not used the `meta keywords` tag as a ranking signal since ~2009. The tag still has minor value for some secondary search engines (Bing gives it low weight). The primary value here is organizational — ensuring each page has a clearly defined keyword focus. The decision to individualize rather than remove is per project requirements. (Confidence: HIGH — confirmed by Google's public statements.)

## Open Questions

1. **Exact word count target for kontaktlinsen.html**
   - What we know: Current count is 320 words, which is the highest of the four pages. The card grid (Videospaltlampe, 3D-Vermessung, Endothel-Analyse, Handhabungstraining) contributes card title text to the count but is not paragraph prose.
   - What's unclear: The planner must decide how many additional prose sections to add (e.g. Linsentypen-Abschnitt, Nachsorge-Abschnitt, Ortsnennung-Paragraph) to confidently reach 800+ without padding.
   - Recommendation: Plan for 3-4 new H2+P blocks (~500 words of new prose), bringing the total comfortably above 800.

2. **Fachlich korrekte Quellenangaben**
   - What we know: Content is to be sourced from public specialist sources (Berufsverbände, Kliniken, Fachzeitschriften) per locked decisions.
   - What's unclear: Specific claims (e.g. "Orthokeratologie korrigiert bis -6 Dioptrien Kurzsichtigkeit") are already stated on the nachtlinsen page; new content should remain consistent with or cautiously reference published ranges.
   - Recommendation: Rely on generally accepted optometric facts; avoid citing specific studies by name unless the text naturally calls for it. Keep claims consistent with existing page content.

3. **Ergolding / Rottenburg distribution across pages**
   - What we know: Locked decision says "mindestens 2-3 Seiten". Service-Schema already has these cities as areaServed.
   - What's unclear: Which specific pages are best suited for the natural mention.
   - Recommendation: Include on nachtlinsen, kurzsichtigkeit, and kontaktlinsen (the highest-traffic service pages). Leave visuelle-reha as optional. The phrase "Patienten aus Landshut und Umgebung, darunter Ergolding und Rottenburg an der Laaber" is a natural fit in any introductory or wrap-up paragraph.

## Sources

### Primary (HIGH confidence)
- Direct file inspection of all 4 service HTML pages — word counts, insertion points, existing CSS class patterns
- Direct file inspection of all 8 pages — identical meta keywords confirmed
- `.planning/phases/04-content/04-CONTEXT.md` — locked decisions, deferred items
- `.planning/REQUIREMENTS.md` — requirement definitions CONT-01 through CONT-06, META-01

### Secondary (MEDIUM confidence)
- Google public documentation on meta keywords (confirmed: not a ranking signal since 2009)

### Tertiary (LOW confidence)
- None.

## Metadata

**Confidence breakdown:**
- Current word counts: HIGH — extracted via script from actual HTML files
- Insertion point locations: HIGH — verified by reading all 4 files
- CSS class patterns: HIGH — verified by reading all 4 files
- FAQ HTML pattern: HIGH — consistent with existing H2 question style
- Meta keywords SEO impact: HIGH — established Google policy
- Content topic recommendations: MEDIUM — based on general optometric knowledge, subject to medical accuracy review

**Research date:** 2026-02-25
**Valid until:** Indefinite — static HTML project, no dependency upgrades possible
