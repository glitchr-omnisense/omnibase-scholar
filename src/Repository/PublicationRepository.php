<?php

namespace Base\Scholar\Repository;

use Base\Scholar\Entity\Publication;
use Base\Scholar\Entity\Scholar;
use Base\Scholar\Entity\Theme;
use Base\Scholar\Enum\PublicationStatus;
use Base\Database\Repository\Finder\Criterion;
use Base\Database\Repository\Finder\Group;
use Base\Database\Repository\Finder\Parser;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Publication::class);
    }

    /**
     * What the site shows: validated, not hidden - the criteria of the finder language.
     *
     * @return array<int|string, mixed>
     */
    public function shown(): array
    {
        return ['status' => PublicationStatus::PUBLISHED->value, 'hidden' => false];
    }

    /**
     * The list of the publications page: pinned first, then newest first;
     * filtered by kind, year, theme and words of the title, authors or venue.
     *
     * @param list<string> $types
     *
     * @return list<Publication>
     */
    public function findShown(array $types = [], ?int $year = null, ?Theme $theme = null, ?string $text = null, ?int $limit = null, int $offset = 0, array $excludeTypes = []): array
    {
        return $this->findBy($this->filtered($types, $year, $theme, $text, $excludeTypes), ['pinned' => 'DESC', 'year' => 'DESC', 'id' => 'DESC'], $limit ?: null, $offset);
    }

    /** @param list<string> $types */
    public function countShown(array $types = [], ?int $year = null, ?Theme $theme = null, ?string $text = null, array $excludeTypes = []): int
    {
        return $this->count($this->filtered($types, $year, $theme, $text, $excludeTypes));
    }

    /**
     * @param list<string> $types
     *
     * @return array<int|string, mixed>
     */
    private function filtered(array $types, ?int $year, ?Theme $theme, ?string $text, array $excludeTypes = []): array
    {
        $criteria = $this->shown();
        if ($types) {
            $criteria['type'] = $types;
        }
        if ($excludeTypes) {
            $criteria[] = Group::of(Parser::SEPARATOR_AND, Criterion::of('type', $excludeTypes, Parser::OPTION_NOT_EQUAL));
        }
        if ($year) {
            $criteria['year'] = $year;
        }
        if ($theme) {
            // :theme MEMBER OF p.themes: a join of the themes, on that one.
            $criteria['themes'] = $theme;
        }
        foreach (array_slice(preg_split('/\s+/u', trim((string) $text)) ?: [], 0, 6) as $word) {
            if ('' !== $word) {
                $like = '%'.addcslashes($word, '%_').'%';
                $criteria[] = Group::of(Parser::SEPARATOR_OR,
                    Criterion::of('title', $like, Parser::OPTION_PARTIAL),
                    Criterion::of('authors', $like, Parser::OPTION_PARTIAL),
                    Criterion::of('venue', $like, Parser::OPTION_PARTIAL),
                );
            }
        }

        return $criteria;
    }

    /** @return list<Publication> the selection: the ones marked, else the pinned, else the latest */
    public function findSelected(int $limit = 5): array
    {
        $selected = $this->findBy([...$this->shown(), 'selected' => true], ['year' => 'DESC', 'id' => 'DESC'], $limit);

        return $selected ?: $this->findShown(limit: $limit);
    }

    public function findOneShown(int $id): ?Publication
    {
        return $this->findOneBy([...$this->shown(), 'id' => $id]);
    }

    /**
     * The years that have a publication, newest first: read from the publications shown (the language selects
     * entities, no DISTINCT column).
     *
     * @return list<int>
     */
    public function findYears(): array
    {
        $years = [];
        foreach ($this->findBy([...$this->shown(), Group::of(Parser::SEPARATOR_AND, Criterion::of('year', null, Parser::OPTION_NOT_NULL))], ['year' => 'DESC']) as $publication) {
            $years[(int) $publication->getYear()] = true;
        }

        return array_keys($years);
    }

    /**
     * type => count, the most first: counted from the publications shown (the language has no GROUP BY).
     *
     * @return array<string, int>
     */
    public function countByType(): array
    {
        $counts = [];
        foreach ($this->findBy($this->shown()) as $publication) {
            $counts[$publication->getType()] = ($counts[$publication->getType()] ?? 0) + 1;
        }
        arsort($counts);

        return $counts;
    }

    /** @return list<Publication> every publication of a scholar, whatever its status: what a sync matches against */
    public function findAllOf(Scholar $scholar): array
    {
        return $this->findBy(['scholar' => $scholar]);
    }

    /** @return list<Publication> the ones waiting for the scholar's eye, newest first */
    public function findPending(?int $limit = null): array
    {
        return $this->findBy(['status' => PublicationStatus::PENDING->value], ['year' => 'DESC', 'id' => 'DESC'], $limit);
    }

    public function countPending(): int
    {
        return $this->count(['status' => PublicationStatus::PENDING->value]);
    }
}
