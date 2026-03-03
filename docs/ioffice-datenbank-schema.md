# iOffice Datenbank Schema - Besser Sehen Landshut

## Übersicht

Dies ist die Dokumentation der iOffice PostgreSQL-Datenbank für Optiker-Betriebe.
Die Datenbank läuft auf PostgreSQL 9.6.

## Wichtige Tabellen

### Kunden (`public.kunden`)

Haupttabelle für Kundenstammdaten.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| anrede | integer | Anrede (FK) |
| name | text | Nachname |
| vorname | text | Vorname |
| strasse | text | Straße |
| hausnummer | text | Hausnummer |
| plz | text | Postleitzahl |
| ort | text | Ort |
| land | text | Land |
| gebdat | date | Geburtsdatum |
| telpriv | text | Telefon privat |
| telgesch | text | Telefon geschäftlich |
| mobil | text | Mobilnummer |
| email | text | E-Mail |
| praeferenz | integer | Kommunikationspräferenz (1=Brief, 2=Email, 3=SMS) |
| kk_id | integer | Krankenkassen-ID (FK) |
| kk_nr | text | Versichertennummer |
| arzt | integer | Augenarzt (FK zu personen) |
| arzt_termin | date | Nächster Arzttermin |
| bemerk | text | Bemerkung (sichtbar) |
| bemerk_intern | text | Interne Bemerkung |
| aktiv | boolean | Kunde aktiv? |
| filiale | integer | Stammfiliale (FK) |
| werbung_wege | integer | Erlaubte Werbewege |
| werbung_status | integer | Werbestatus |
| created_at | timestamp | Erstellungsdatum |
| updated_at | timestamp | Letzte Änderung |

---

### Termine (`public.dates`)

Terminkalender.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| subject | text | Terminbetreff |
| room | integer | Raum (FK) |
| type | integer | Termintyp |
| begin | timestamp | Beginn |
| ende | timestamp | Ende |
| description | text | Beschreibung |
| customer | integer | Kunde (FK zu kunden.id) |
| entry_employee | integer | Mitarbeiter (FK zu personen.id) |
| nicht_erschienen | boolean | Nicht erschienen? |
| is_gesperrt | boolean | Termin gesperrt? |
| serientermin | integer | Serienreferenz |
| created_at | timestamp | Erstellungsdatum |
| updated_at | timestamp | Letzte Änderung |

---

### Personen / Mitarbeiter (`public.personen`)

Mitarbeiter, Ärzte, Ansprechpartner.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| name | text | Vollständiger Name |
| vorname | text | Vorname |
| nachname | text | Nachname |
| kurzz | text | Kürzel |
| typ | integer | Personentyp (Mitarbeiter, Arzt, etc.) |
| email | text | E-Mail |
| telefon | text | Telefon |
| kalenderposition | integer | Position im Kalender |
| filialen | text | Zugeordnete Filialen |

---

### Filialen (`public.filialen`)

Standorte/Filialen.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| nummer | integer | Primärschlüssel (Filialnummer) |
| name | text | Filialname |
| strasse | text | Straße |
| plz | text | PLZ |
| ort | text | Ort |
| telefon | text | Telefon |
| email | text | E-Mail |
| aktiv | boolean | Filiale aktiv? |

---

### Brillenaufträge (`public.brauftrag`)

Aufträge für Brillen.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| filiale | integer | Filiale (FK) |
| kunde | integer | Kunde (FK zu kunden.id) |
| artikel | integer | Artikelreferenz |
| verkauf_mitarb | integer | Verkäufer (FK zu personen.id) |
| verkauf_datum | date | Verkaufsdatum |
| werkstatt_status | integer | Werkstattstatus |
| fertig_bis | date | Fertig bis |
| fertig_am | date | Fertiggestellt am |
| bezahlt_am | date | Bezahlt am |
| geliefert_am | date | Geliefert am |
| ref_re_sph, ref_re_cyl, etc. | numeric | Refraktionswerte rechts |
| ref_li_sph, ref_li_cyl, etc. | numeric | Refraktionswerte links |

---

### Kontaktlinsen-Aufträge (`public.clauftrag`)

Aufträge für Kontaktlinsen.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| filiale | integer | Filiale (FK) |
| kunde | integer | Kunde (FK zu kunden.id) |
| datum | date | Auftragsdatum |
| art | integer | Auftragsart |
| anpasser | integer | Anpasser (FK zu personen.id) |
| tragezeit | integer | Tragezeit |
| pruefung | date | Nächste Prüfung |
| linsentausch_naechster | date | Nächster Linsentausch |
| linsentausch_intervall | integer | Tauschintervall |
| bemerkung | text | Bemerkung |
| bef_re_*, bef_li_* | diverse | Befundwerte rechts/links |

---

### Rechnungen (`public.rechnung`)

Rechnungen und Zahlungen.

| Spalte | Typ | Beschreibung |
|--------|-----|--------------|
| id | integer | Primärschlüssel |
| kunde | integer | Kunde (FK zu kunden.id) |
| auftragsart | integer | Art (Brille, KL, etc.) |
| auftrag | integer | Auftrags-ID |
| filiale | integer | Filiale (FK) |
| rechnung_nr | text | Rechnungsnummer |
| rechnung_datum | date | Rechnungsdatum |
| anlagedatum | timestamp | Angelegt am |
| preissumme | numeric | Gesamtsumme |
| rabatt | numeric | Rabatt |
| preisoffen | numeric | Offener Betrag |
| mahnung_stufe | integer | Mahnstufe |
| abgeholt_am | date | Abgeholt am |
| verkaufdatum | date | Verkaufsdatum |

---

## Wichtige Beziehungen

```
kunden.id ←──── dates.customer (Termine)
kunden.id ←──── brauftrag.kunde (Brillenaufträge)
kunden.id ←──── clauftrag.kunde (KL-Aufträge)
kunden.id ←──── rechnung.kunde (Rechnungen)
kunden.filiale ───→ filialen.nummer
kunden.arzt ───→ personen.id

personen.id ←──── dates.entry_employee
personen.id ←──── brauftrag.verkauf_mitarb

filialen.nummer ←──── alle Tabellen mit .filiale
```

---

## Weitere wichtige Tabellen

| Tabelle | Beschreibung |
|---------|--------------|
| `public.messungen` | Augenprüfungen/Messungen |
| `public.refraktion` | Refraktionsdaten |
| `public.krankenkassen` | Krankenkassen-Stammdaten |
| `public.handelswaren` | Sonstige Waren (Pflegemittel, etc.) |
| `public.todoliste` | Aufgaben/To-Dos |
| `public.anschreiben` | Anschreiben/Serienbriefe |
| `public.kundenkontakt` | Kundenkontakthistorie |
| `public.clleasinggruppe` | KL-Abo/Leasing-Gruppen |

---

## Beispiel-Queries

### Alle aktiven Kunden
```sql
SELECT id, name, vorname, email, mobil
FROM public.kunden
WHERE aktiv = true
ORDER BY name, vorname;
```

### Kunden ohne Termin seit 6 Monaten
```sql
SELECT k.id, k.name, k.vorname, k.mobil, k.email,
       MAX(d.begin) as letzter_termin
FROM public.kunden k
LEFT JOIN public.dates d ON d.customer = k.id
WHERE k.aktiv = true
GROUP BY k.id, k.name, k.vorname, k.mobil, k.email
HAVING MAX(d.begin) < NOW() - INTERVAL '6 months'
   OR MAX(d.begin) IS NULL
ORDER BY letzter_termin NULLS FIRST;
```

### Termine heute
```sql
SELECT d.begin, d.ende, d.subject,
       k.name, k.vorname, k.mobil,
       p.name as mitarbeiter
FROM public.dates d
LEFT JOIN public.kunden k ON k.id = d.customer
LEFT JOIN public.personen p ON p.id = d.entry_employee
WHERE DATE(d.begin) = CURRENT_DATE
ORDER BY d.begin;
```

### Offene Rechnungen
```sql
SELECT r.rechnung_nr, r.rechnung_datum, r.preisoffen,
       r.mahnung_stufe, k.name, k.vorname
FROM public.rechnung r
JOIN public.kunden k ON k.id = r.kunde
WHERE r.preisoffen > 0
ORDER BY r.rechnung_datum;
```

### Geburtstage diese Woche
```sql
SELECT id, name, vorname, gebdat, mobil, email
FROM public.kunden
WHERE aktiv = true
  AND EXTRACT(MONTH FROM gebdat) = EXTRACT(MONTH FROM CURRENT_DATE)
  AND EXTRACT(DAY FROM gebdat) BETWEEN
      EXTRACT(DAY FROM CURRENT_DATE) AND
      EXTRACT(DAY FROM CURRENT_DATE + INTERVAL '7 days')
ORDER BY EXTRACT(DAY FROM gebdat);
```
