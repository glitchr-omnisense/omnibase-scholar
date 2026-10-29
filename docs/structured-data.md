---
title: "Structured data and exports"
order: 33
---

# Structured data and exports

## JSON-LD (`Service\JsonLd`, `scholar.jsonld`)

| Page | schema.org |
|---|---|
| `/cv`, and a host's home page through `scholar_person_jsonld()` | `Person`: name, given and family names, honorific, jobTitle, affiliation, ORCID as `identifier`, `sameAs` (ORCID, OpenAlex, HAL's CV, the pages typed), `knowsAbout` |
| a publication | `ScholarlyArticle` (a journal's `Periodical`, a conference's `Event`, a book's as `isPartOf`), `Book` (ISBNs, publisher, edition, an `Offer` to buy it), `Chapter`, `Thesis`, `Report`, `Dataset`, `SoftwareSourceCode`; the authors, the scholar's with his ORCID |
| `/publications`, `/books` | an `ItemList` of them |

## Citing

`Service\Citations::reference()` writes the line to copy (authors with
initials, year, title, journal, volume(issue), pages, DOI - or ISBN).
`export('bibtex'|'ris'|'csl-json', $publications)` writes the files through
omnisense/omnischolar's `Export`, from what the site shows: a PDF or a
publisher's page given on the site goes into the file.

## An example

```php
use Base\Scholar\Entity\Publication;
use Base\Scholar\Entity\Scholar;
use Base\Scholar\Service\Citations;

$scholar = (new Scholar('Keitaro Nakatani'))->setOrcid('0009-0005-1387-7295');
$publication = (new Publication($scholar))
    ->setTitle('Acid-sensitive photoswitches')
    ->setType('article')
    ->setYear(2024)
    ->setVenue('Chemical Science')
    ->setAuthors('Lucas Chocron, Keitaro Nakatani')
    ->setDoi('10.1039/d4sc04973j');

$citations = new Citations();
echo $citations->reference($publication), "\n\n";
echo $citations->export('bibtex', $publication), "\n";
echo $citations->export('ris', $publication), "\n";
```

Its output, run in omnisense/omnibase-harness with omnisense/omnischolar on
the autoloader:

```text
Chocron, L., Nakatani, K. (2024). Acid-sensitive photoswitches. Chemical Science. https://doi.org/10.1039/d4sc04973j

@article{chocron2024acid,
  author = {Chocron, Lucas and Nakatani, Keitaro},
  title = {{Acid-sensitive photoswitches}},
  journal = {Chemical Science},
  year = {2024},
  doi = {10.1039/d4sc04973j}
}

TY  - JOUR
ID  - chocron2024acid
AU  - Chocron, Lucas
AU  - Nakatani, Keitaro
TI  - Acid-sensitive photoswitches
T2  - Chemical Science
PY  - 2024
DO  - 10.1039/d4sc04973j
UR  - https://doi.org/10.1039/d4sc04973j
ER  -
```

`scholar.abstracts_in_exports: true` writes the abstracts into the BibTeX and
RIS files as well.
