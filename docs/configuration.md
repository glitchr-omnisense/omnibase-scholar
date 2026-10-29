---
title: "Configuration reference"
order: 40
---

# Configuration reference

## The `scholar` tree

Dumped with Symfony's `YamlReferenceDumper` from
`Base\Scholar\DependencyInjection\ScholarConfiguration`:

```yaml
scholar:

    # The most works scholar:sync reads from one source for one scholar.
    max_per_source:       2000

    # The omnischolar source whose author profile gives the counts (works, citations, h-index); null: none.
    metrics_source:       openalex

    # The omnischolar source the CV is read from (positions, degrees, distinctions), when the scholar has a profile there; null: the CV is typed only.
    cv_source:            orcid

    # Publications listed per page.
    per_page:             100

    # schema.org Person, ScholarlyArticle and Book on the pages.
    jsonld:               true

    # Write the abstracts into the BibTeX and RIS exports.
    abstracts_in_exports: false
```

| Key | Default | Read by |
|---|---|---|
| `max_per_source` | 2000 | `Synchronizer`: the most works read from one source for one scholar |
| `metrics_source` | `openalex` | `Synchronizer`: the source whose author profile gives the counts; null, none |
| `cv_source` | `orcid` | `Synchronizer`: the source the CV lines are read from, when the scholar has a profile there; null, the CV is typed only |
| `per_page` | 100 | `/publications` |
| `jsonld` | true | the pages' schema.org |
| `abstracts_in_exports` | false | `Citations`: the abstracts in the BibTeX and RIS files |

Each key is a container parameter: `%scholar.max_per_source%`,
`%scholar.metrics_source%`, `%scholar.cv_source%`, `%scholar.per_page%`,
`%scholar.jsonld%`, `%scholar.abstracts_in_exports%`.

The sources themselves are omnisense/omnischolar's (`omnischolar.sources`,
[Installation and configuration](installation.md)): a profile's source name
is one of them.

## Services

| Service | Role |
|---|---|
| `Base\Scholar\Service\Synchronizer` | `isAvailable()`, `sync($scholar)`, `merge($scholar, $works)` |
| `Base\Scholar\Service\Citations` | `reference($publication)`, `export($format, $publications)` |
| `Base\Scholar\Service\JsonLd` | `person()`, `publication()`, `list()` |
| `Base\Scholar\Service\Curriculum` | `sections()`, `entries()`, `countHidden()` |
| `Base\Scholar\Service\WorkData` | a `Work` as the JSON column keeps it and back, every field included; what makes two records the same work (`keys()`, `fingerprint()`) |
| `Base\Scholar\Command\SyncCommand` | `scholar:sync`, `--scholar=<id>` |
| `Base\Scholar\EventListener\SitemapListener` | the shown publications and the visible themes in `/sitemap.xml` |
| `Base\Scholar\Twig\ScholarExtension` | the Twig functions and the `scholar_type_label` filter |

`Base\Scholar\Service\PublicationIndex` is built by the synchronizer from a
scholar's publications, not taken from the container: it finds the site's
publication for a `Work` by any identifier ever seen for it (a DOI, a HAL id,
an ISBN of any edition), else by its title and year, unless both carry a DOI
and they differ (two versions of a dataset, an erratum).

## Translations

The domain `scholar`, in English, French and German (ICU format). A site
overrides a message in its own `translations/scholar+intl-icu.<locale>.yaml`.
