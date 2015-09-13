<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

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

  public function getActiveQuery()
  {
    $query = $this->getGradeQuery();
    $query->where('g.isActive = true');

    return $query;
  }

  public function getAllActiveInverted()
  {
    $query = $this->getActiveQuery();
    $query->orderBy('g.rank', 'desc');

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
    } catch ( \Doctrine\Orm\NoResultException $e ) {
      $grade = null;
    }

    return $grade;
  }

  public function getLastActiveRank()
  {
    $query = $this->getGradeQuery();
    $query->where('g.isActive = :active')
            ->setParameter('active', true)
          ->orderBy('g.rank', 'desc')
          ->setMaxResults(1);

    try {
      $grade = $query->getQuery()->getSingleResult();

      return $grade->getRank();
    } catch ( \Doctrine\Orm\NoResultException $e ) {
      return null;
    }
  }

  public function updateNextRank($rank)
  {
    return true;
  }
}
