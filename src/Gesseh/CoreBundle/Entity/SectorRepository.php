<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SectorRepository
 */
class SectorRepository extends EntityRepository
{
  public function listOtherSectors(array $exclude)
  {
    $query = $this->createQueryBuilder('s')
                  ->where('s.id NOT IN (' . implode(',', $exclude) . ')');

    return $query;
  }
}
