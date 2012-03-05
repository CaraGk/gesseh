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

  public function getAllActiveInverted()
  {
    $query = $this->getGradeQuery();
    $query->where('g.isActive = :active')
            ->setParameter('active', true)
          ->orderBy('g.rank', 'desc');

    return $query->getQuery()
                 ->getResult();
  }

  public function getNext($grade_rank)
  {
    $query = $this->getGradeQuery();
    $query->where('g.rank > :rank')
              ->setParameter('rank', $grade_rank)
          ->setMaxResults(1);

    try {
      $grade = $query->getQuery()->getSingleResult();
    } catch( \Doctrine\Orm\NoResultException $e ) {
      $grade = null;
    }

    return $grade;
  }
}
