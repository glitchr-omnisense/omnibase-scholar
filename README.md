# Scholar

A researcher's site for [omnibase](https://github.com/glitchr-omnisense/omnibase):
their publications read from the services that know them, through
[omnisense/omnischolar](https://github.com/glitchr-omnisense/omnischolar)
(OpenAlex, HAL, Crossref, ORCID, Open Library, arXiv, INSPIRE), validated
before they show, with what the site adds to each - hidden, pinned, in the
selection, a PDF, a link to buy it or to the publisher, a cover, a note,
themes - surviving every sync; the CV (positions, degrees, distinctions,
grants, courses, supervisions); research themes and projects; the pages and
their schema.org JSON-LD.

The bundle holds no talk and no service: a scholar's talks are
[omnibase/agenda](https://github.com/glitchr-omnisense/omnibase-agenda) `Event`s
(`agenda.jsonld_type: EducationEvent`), their services
[omnibase/consulting](https://github.com/glitchr-omnisense/omnibase-consulting)
`Offering`s.

```bash
composer require omnibase/scholar omnisense/omnischolar omnischolar/openalex omnischolar/hal omnischolar/crossref
```

Stable releases follow semantic versioning: this installs the latest 1.x, with omnisense/omnibase 5.1 or later
on Symfony (omnibridge/symfony) and Doctrine ORM (DoctrineBundle), omnibase/admin for the back office. No `minimum-stability` setting is needed.

Under Symfony, register the bundles and the pages, then create the tables:

```php
// config/bundles.php
Omnischolar\Bridge\Symfony\OmnischolarBundle::class => ['all' => true],
Base\Scholar\ScholarBundle::class => ['all' => true],
```

```yaml
# config/routes.yaml
scholar_controller:
    resource: "@ScholarBundle/src/Controller/Client"
    type: attribute
    prefix: /
```

```sh
bin/console make:migration && bin/console doctrine:migrations:migrate
```

First use: create the scholar in the back office (*Identity and sources*: a name, an ORCID, an OpenAlex author id),
run `bin/console scholar:sync`, validate the works it found, and open `/publications`. The public sources need no
key; [docs/installation.md](docs/installation.md) has the rest.

| | |
|---|---|
| `Entity\Scholar` | the person: name, title, affiliation, ORCID, the profiles works are read from (`openalex: A5000000001`, `hal: Josiah Carberry`), sameAs, portrait, biography |
| `Entity\Publication` | a `Work` of omnischolar kept whole (JSON) + columns to sort by + the site's overrides; `pending`, `published` or `rejected` |
| `Entity\Cv\*` | `Position`, `Degree`, `Award`, `Grant`, `Course`, `Supervision` |
| `Entity\Theme` | a research theme or a project, a page each |
| `scholar:sync` | every scholar's works, counts and CV read again (the cron container, weekly) |
| widget `scholar_pending` | "New publications to validate", with Validate / Reject |

Pages: `/publications` (filters; BibTeX, RIS, CSL-JSON of what is listed),
`/publications/{id}-{slug}`, `/books`, `/cv`, `/teaching`, `/research`,
`/research/{slug}`.

## Documentation

- [Overview](docs/index.md)
- [Installation and configuration](docs/installation.md)
- [Concepts](docs/concepts.md)
- [Synchronisation and validation](docs/sync.md)
- [Pages, Twig functions and the host's look](docs/pages.md)
- [The back office](docs/admin.md)
- [Structured data and exports](docs/structured-data.md)
- [Configuration reference](docs/configuration.md)
- [Testing](docs/testing.md)

## Tests

`vendor/bin/phpunit` (or, inside a host application,
`php vendor/bin/phpunit -c vendor/omnibase/scholar/phpunit.xml.dist`): the
Work's storage and its keys, the sync laid over what the site has (a new work
waits, a known one follows its sources and keeps the site's overrides, a
rejected one stays rejected, one typed by hand is the site's), the reference
line, the exports, the JSON-LD. Inside a host, also who writes in the back
office (`tests/Controller/Admin/OpenToAdminsTest`: an administrator does, a
plain user does not).

License: MIT since 2026-10-09; earlier versions remain published under LGPL-3.0-or-later.
