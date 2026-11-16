<?php

namespace Base\Scholar\Repository;

use Base\Scholar\Entity\Theme;
use Base\Scholar\Enum\ThemeKind;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Theme::class);
    }

    /** @return list<Theme> the visible ones, in their order; of one kind when asked */
    public function findVisible(?ThemeKind $kind = null): array
    {
        $criteria = ['visible' => true];
        if ($kind) {
            $criteria['kind'] = $kind->value;
        }

        return $this->findBy($criteria, ['position' => 'ASC', 'id' => 'ASC']);
    }

    public function findOneVisible(string $slug): ?Theme
    {
        return $this->findOneBy(['slug' => $slug, 'visible' => true]);
    }
}
