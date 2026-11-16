<?php

namespace Base\Scholar\Repository;

use Base\Scholar\Entity\Scholar;
use Base\Database\Repository\Finders;
use Base\Database\Repository\ServiceEntityRepository;

/**
 * @method Scholar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scholar|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Scholar[]    findAll()
 * @method Scholar[]    findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
class ScholarRepository extends ServiceEntityRepository
{
    public function __construct(Finders $finders)
    {
        parent::__construct($finders, Scholar::class);
    }

    /** The site's scholar: the first one typed (a site is usually one person's). */
    public function findMain(): ?Scholar
    {
        return $this->findOneBy([], ['id' => 'ASC']);
    }
}
