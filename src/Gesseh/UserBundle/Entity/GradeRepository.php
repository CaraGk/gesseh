<?php

namespace Gesseh\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GradeRepository
 */
class GradeRepository extends EntityRepository
{
  public function getGradeQuery()
  {
    return $this->createQueryBuilder('g')
                ->addOrderBy('g.isActive', 'desc')
                ->addOrderBy('g.rank', 'asc');
  }

  public function getAll()
  {
    return $this->getGradeQuery()->getQuery()->getResult();
  }
}
