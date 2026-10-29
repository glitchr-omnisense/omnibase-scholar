# omnibase/scholar

omnibase/scholar is a researcher's site on omnibase: the publications read
from the open sources and validated by the researcher, the books, the CV,
the teaching, the research themes and projects, with their structured data
and citation exports. Its namespace is `Base\Scholar\`; it reads the sources
through omnisense/omnischolar (OpenAlex, HAL, Crossref, ORCID, Open Library).

## What it adds

- `Scholar`, the person and the profiles that name them in each source;
  `Publication`, a work as the site shows it (the sources' record and the
  site's own fields); `Theme`, a research theme or project; six CV entries
  (`Position`, `Degree`, `Award`, `Grant`, `Course`, `Supervision`).
- `scholar:sync`, which reads every profile's works, the counts and the CV,
  and lays them over the site's publications: a new work waits for
  validation.
- Seven pages: the publications (filtered and paged), a publication, the
  books, the CV, the teaching, the research, a theme; and two export routes
  (the list or one publication as BibTeX, RIS or CSL-JSON).
- schema.org `Person`, `ScholarlyArticle`, `Book` and the others; the line to
  copy and the exports.
- Twig functions for a host's home page.
- Nine back office screens open to the site's administrator, a validation
  tile.
- English, French and German messages (domain `scholar`).

## Pages

- [Installation and configuration](installation.md)
- [Concepts](concepts.md)
- [Synchronisation and validation](sync.md)
- [Pages, Twig functions and the host's look](pages.md)
- [Structured data and exports](structured-data.md)
- [The back office](admin.md)
- [Configuration reference](configuration.md)
- [Testing](testing.md)
