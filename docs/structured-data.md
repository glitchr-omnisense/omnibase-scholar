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

$scholar = (new Scholar('Josiah Carberry'))->setOrcid('0000-0002-1825-0097');
$publication = (new Publication($scholar))
    ->setTitle('Acid-sensitive pot glazes')
    ->setType('article')
    ->setYear(2024)
    ->setVenue('Journal of Psychoceramics')
    ->setAuthors('Jeanne Exemple, Josiah Carberry')
    ->setDoi('10.5555/12345678');

$citations = new Citations();
echo $citations->reference($publication), "\n\n";
echo $citations->export('bibtex', $publication), "\n";
echo $citations->export('ris', $publication), "\n";
```

Its output, run in omnisense/omnibase-harness with omnisense/omnischolar on
the autoloader:

```text
Exemple, J., Carberry, J. (2024). Acid-sensitive pot glazes. Journal of Psychoceramics. https://doi.org/10.5555/12345678

@article{exemple2024acid,
  author = {Exemple, Jeanne and Carberry, Josiah},
  title = {{Acid-sensitive pot glazes}},
  journal = {Journal of Psychoceramics},
  year = {2024},
  doi = {10.5555/12345678}
}

TY  - JOUR
ID  - exemple2024acid
AU  - Exemple, Jeanne
AU  - Carberry, Josiah
TI  - Acid-sensitive pot glazes
T2  - Journal of Psychoceramics
PY  - 2024
DO  - 10.5555/12345678
UR  - https://doi.org/10.5555/12345678
ER  -
```

`scholar.abstracts_in_exports: true` writes the abstracts into the BibTeX and
RIS files as well.
