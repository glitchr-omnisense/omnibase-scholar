---
title: "Concepts"
order: 20
---

# Concepts

## The scholar

`Base\Scholar\Entity\Scholar` (`scholar_scholar`) is the person the site is
about. The pages and the Twig functions read the first one.

| Field | Meaning |
|---|---|
| `name` (required), `givenName`, `familyName`, `honorific` | `getFullName()` is the honorific and the name: "Dr. Monica Neagoy" |
| `jobTitle`, `affiliation`, `affiliationUrl` | for the CV and schema.org |
| `orcid` | `0000-0000-0000-000X` |
| `profiles` | source name => the authors there (an OpenAlex id, an ORCID, an idHAL, a name); typed in the back office one `source: author` a line (`getProfilesText()`, `setProfilesText()`) |
| `sameAs` | other pages about the person, one a line |
| `keywords` | what they work on (schema.org `knowsAbout`) |
| `portrait`, `biography` | an image (16 MB); `getBiographyParagraphs()` |
| `autoApprove` | the works read go online without validation |
| `fromYear` | the sources are asked for the works from that year on |
| `metrics` | the counts of the metrics source (works, citations, h-index, i10-index) |
| `lastSyncAt`, `lastSync` | what the last run did (`rememberSync()`) |

`getIdentityUrls()` gathers the `sameAs` pages, the ORCID page, the OpenAlex
author pages (ids like `A5108007452`) and the HAL CVs (an idHAL, not a name).

## A publication

`Base\Scholar\Entity\Publication` (`scholar_publication`) is a work as the site
shows it. It holds two kinds of fields:

- the sources' record: `work` (omnisense/omnischolar's `Work`, as JSON: the
  abstract, the sources, the identifiers, the editions, the PDF and the page
  the sources give), with its columns `title`, `type`, `year`, `venue`,
  `authors`, `doi` and `citations`, refreshed by every run
  (`refresh($work)`), and what the merge recognises it by (`matchKeys`,
  `fingerprint`, `fetchedAt`, `lastSeenAt`);
- the site's own: `status`, `manual` (typed by hand: its columns are the
  site's), `hidden`, `pinned` (first on the list), `selected` (the
  selection), `pdf` (an upload, 64 MB), `pdfUrl`, `buyUrl`, `publisherUrl`,
  `cover` (16 MB), `note`, `themes`.

Its `type` is one of omnisense/omnischolar's `WorkType` values: `article`,
`book`, `chapter`, `conference`, `preprint`, `thesis`, `report`, `dataset`,
`software`, `review`, `editorial`, `other` (an unknown value becomes `other`).

| Method | Returns |
|---|---|
| `isShown()` | validated and not hidden: on the site |
| `approve()`, `reject()`, `isPending()`, `isPublished()` | the status (`PublicationStatus`: `pending`, `published`, `rejected`) |
| `getCoverUrl()` | the cover uploaded, else the sources' (Open Library's) |
| `getFullTextUrl()` | the PDF uploaded, the address given, else the sources' |
| `getLink()` | the publisher's page given, else the DOI's address, else the sources' page |
| `isOpenAccess()` | the sources say so, or there is a full text to offer |
| `getSlug()` | the title's slug, 80 characters at most, `publication` for none |
| `getAbstract()`, `getIdentifiers()`, `getHalId()`, `getArxivId()`, `getIsbns()`, `getEditions()`, `getSources()` | read from `work` |
| `toWork()` | the publication as a `Work`, with the site's own fields over the sources' (what the exports write) |

## Themes and projects

`Base\Scholar\Entity\Theme` (`scholar_theme`) is a research theme or a project
(`ThemeKind`: `theme`, `project`; `isProject()`): title, unique slug,
summary, description (`getParagraphs()`), image, start and end years
(`getYears()`), funder, address, position, visible. Publications are linked
to themes (`scholar_publication_theme`).

## The CV

Six entities share `Base\Scholar\Entity\Cv\CvEntry`: the scholar, `title`
(required), `organization`, `city`, `country` (two letters), `start` and `end`
(`YYYY`, `YYYY-MM` or `YYYY-MM-DD`), `description`, `url`, `position`,
`visible`, `origin` (`site`, or the source it was read from) and
`externalKey` (the source's key, so a line is read once).

| Entity | Table | Its own fields |
|---|---|---|
| `Position` | `scholar_position` | `department` |
| `Degree` | `scholar_degree` | `thesis`, `advisor` |
| `Award` | `scholar_award` | |
| `Grant` | `scholar_grant` | `reference`, `role`, `amount` |
| `Course` | `scholar_course` | `level`, `volume` |
| `Supervision` | `scholar_supervision` | `level` (`SupervisionLevel`: `phd`, `master`, `postdoc`, `intern`, `other`), `subject`, `coSupervisors`, `outcome`; the title is the student (`getStudent()`) |

`getPeriod()` writes "2019–2023", "2019–" for a current one, "2019" for one
year; `isCurrent()` is started and not ended.

`Base\Scholar\Service\Curriculum::sections($names)` returns the visible
entries of each section that has some (`positions`, `degrees`, `awards`,
`grants`, `courses`, `supervisions`), by position, the current ones first,
then the latest; a section whose table is not migrated yet is left out.
`countHidden()` counts the hidden lines (a line read from the CV source waits
hidden).

## The three enums

`PublicationStatus`, `ThemeKind` and `SupervisionLevel` are PHP enums mapped
with Doctrine's `enumType` on string columns; each case names itself in the
`scholar` domain.
