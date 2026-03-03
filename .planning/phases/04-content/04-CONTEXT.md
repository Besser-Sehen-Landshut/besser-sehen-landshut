# Phase 4: Content - Context

**Gathered:** 2026-03-03
**Status:** Ready for planning

<domain>
## Phase Boundary

Alle 4 Leistungsseiten auf 800+ Wörter fachlich korrekten Inhalt ausbauen. Meta-Keywords auf jeder der 8 Seiten individualisieren. team.html bleibt unverändert (CONT-05 verschoben). Neue Capabilities (Terminbuchung, Galerie etc.) sind explizit Out of Scope.

Betroffene Dateien: leistungen-nachtlinsen.html, leistungen-beratung-kurzsichtigkeit.html, leistungen-visuelle-reha.html, leistungen-kontaktlinsen.html, alle 8 Seiten für meta keywords.

</domain>

<decisions>
## Implementation Decisions

### FAQ-Abschnitt (Nachtlinsen)
- Eigener FAQ-Block am Ende der Seite (unter dem bestehenden Inhalt) — klar scanbar, gut für Google Featured Snippets
- Themen: Kosten & Kasse, Sicherheit, Eignung
- Kassenleistung: Nein — Nachtlinsen sind Privatleistung, das wird klar und ehrlich kommuniziert
- Keine Produktnamen (DreamLens, Paragon CRT) — allgemein "formstabile Nachtlinsen" oder "Orthokeratologie-Linsen"

### Alleinstellungsmerkmal Myopie-Management
- Augenlängen-Messung ist DAS USP von Besser Sehen Landshut
- Prominente eigene H2-Sektion auf leistungen-beratung-kurzsichtigkeit.html
- Formulierung betont: "Augenlängen-Messung bei Besser Sehen Landshut" — suchbar und differenzierend
- Dieses Feature fehlt bei normalen Augenoptikern — das klar herausstellen

### Ton und Schreibstil
- Bestehenden Stil beibehalten: zugänglich, patientenorientiert, keine Fachbegriffe ohne Erklärung
- Zielgruppe: Patienten ohne Vorwissen — kein Mediziner-Jargon, aber trotzdem kompetent
- Inhalte werden aus öffentlichen Fachquellen recherchiert und formuliert (Berufsverbände, Kliniken, Fachzeitschriften)

### Ortsnennung
- "Landshut" natürlich eingebettet auf allen 4 Leistungsseiten (mindestens einmal je Seite)
- Ergolding und Rottenburg an der Laaber auf mehreren Leistungsseiten (mindestens 2-3 Seiten)
- Formulierung natürlich: "Patienten aus Landshut, Ergolding und Rottenburg" oder "In Landshut und Umgebung"
- Kein Keyword-Stuffing — Ortsangaben wirken als normaler Satz, nicht als Liste

### team.html
- Bleibt unverändert — team.html-Ausbau ist für diese Phase bewusst verschoben
- CONT-05 (300+ Wörter team.html) wird als deferred Requirement geführt

### Claude's Discretion
- Gliederung und Reihenfolge der neuen Abschnitte je Seite
- Exakte Formulierungen, Zwischenüberschriften, Übergangssätze
- Welche öffentlichen Quellen für die Recherche genutzt werden
- Wie viel jede Seite über 800 Wörter hinausgeht

</decisions>

<specifics>
## Specific Ideas

- Augenlängen-Messung auf Myopie-Seite: "Wir messen die Augenlänge Ihres Kindes" — für Eltern als Zielgruppe formuliert
- FAQ Nachtlinsen: Fragen aus Patientenperspektive ("Darf mein Kind Nachtlinsen tragen?", "Was kostet das?", "Ist das sicher?")
- Bestehende Struktur der Nachtlinsen-Seite hat bereits H2-Fragen — FAQ-Block passt stilistisch als Erweiterung ans Ende

</specifics>

<code_context>
## Existing Code Insights

### Reusable Assets
- Alle 4 Leistungsseiten haben identische HTML-Struktur (Header, Nav, Main, Footer)
- Breadcrumb-Schema bereits vorhanden — Content-Änderungen berühren Schema nicht
- Service-Schema mit serviceType und areaServed bereits live — Content ergänzt HTML, nicht JSON-LD

### Established Patterns
- Leistungsseiten nutzen H2 für Hauptfragen ("Wie funktioniert X?") und kurze Paragraphen
- Kein CMS — direkte Bearbeitung der HTML-Dateien
- meta keywords im <head> zwischen den anderen Meta-Tags platzieren

### Integration Points
- Neue Textblöcke in <main>-Element nach bestehendem Content einfügen
- meta keywords als neues <meta name="keywords" content="..."> Tag im <head>
- Keine JS- oder CSS-Änderungen nötig — reiner HTML/Text-Content

</code_context>

<deferred>
## Deferred Ideas

- CONT-05: team.html Ausbau auf 300+ Wörter — verschoben, bis Inhalt je Person vorliegt
- Produktnamen (DreamLens, Paragon CRT) — kann später hinzugefügt werden wenn gewünscht

</deferred>

---

*Phase: 04-content*
*Context gathered: 2026-03-03*
