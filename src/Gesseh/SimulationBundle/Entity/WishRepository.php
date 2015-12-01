<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * WishRepository
 */
class WishRepository extends EntityRepository
{
  public function getWishStudentQuery($student_id)
  {
    return $this->createQueryBuilder('w')
                ->join('w.simstudent', 't')
                ->where('t.student = :student')
                  ->setParameter('student', $student_id);
  }

  public function getWishQuery()
  {
    return $this->createQueryBuilder('w')
                ->join('w.simstudent', 't')
                ->join('w.department', 'd')
                ->join('d.hospital', 'h')
                ->join('d.sector', 'u')
                ->addSelect('d')
                ->addSelect('h')
                ->addSelect('t')
                ->addSelect('u');
  }

  public function getByStudent($student_id)
  {
    $query = $this->getWishQuery();
    $query->where('t.student = :student')
            ->setParameter('student', $student_id)
          ->addOrderBy('w.rank', 'asc');

    return $query->getQuery()->getResult();
  }

  public function findByUsername($username)
  {
    $query = $this->getWishQuery();
    $query->join('t.student', 's')
          ->join('s.user', 'v')
          ->where('v.username = :username')
            ->setParameter('username', $username);

    return $query->getQuery()->getResult();
  }

  public function getStudentWishList($simstudent_id)
  {
    $query = $this->createQueryBuilder('w')
                  ->join('w.department', 'd')
                  ->join('d.sector', 's')
                  ->where('w.simstudent = :simstudent_id')
                    ->setParameter('simstudent_id', $simstudent_id)
                  ->addSelect('d')
                  ->addSelect('s');

    return $query->getQuery()->getResult();
  }

  public function findByStudentAndRank($student_id, $rank)
  {
    $query = $this->getWishStudentQuery($student_id);
    $query->join('w.department', 'd')
          ->addSelect('d')
          ->andWhere('w.rank = :rank')
          ->setParameter('rank', $rank);
    $wish = $query->getQuery()->getSingleResult();

    if ($wish->getDepartment()->getCluster() != null) {
        $query = $this->getWishQuery();
        $query->where('t.student = :student_id')
              ->setParameter('student_id', $student_id)
              ->andWhere('d.cluster = :cluster')
              ->setParameter('cluster', $wish->getDepartment()->getCluster());

        return $query->getQuery()
                     ->getResult();
    } else {
        return array($wish);
    }
  }

  public function findByStudentAndId($student_id, $id)
  {
    $query = $this->getWishStudentQuery($student_id);
    $query->andWhere('w.id = :id')
            ->setParameter('id', $id);

    return $query->getQuery()->getSingleResult();
  }

  public function findByRankAfter($student_id, $rank)
  {
    $query = $this->getWishStudentQuery($student_id);
    $query->andWhere('w.rank > :rank')
            ->setParameter('rank', $rank)
          ->addOrderBy('w.rank', 'asc');

    return $query->getQuery()->getResult();
  }

  public function getMaxRank($student_id)
  {
    $query = $this->getWishStudentQuery($student_id)
                  ->select('COUNT(w.id)');

    return $query->getQuery()->getSingleScalarResult();
  }

  public function getWishCluster($student_id, $wish_id)
  {
      $query = $this->getWishQuery();
      $query->where('w.id = :wish_id')
            ->setParameter('wish_id', $wish_id)
            ->andWhere('t.student = :student_id')
            ->setParameter('student_id', $student_id);
      $wish = $query->getQuery()->getSingleResult();

      if (null != $wish->getDepartment()->getCluster()) {
        $query = $this->getWishQuery();
        $query->where('t.student = :student_id')
              ->setParameter('student_id', $student_id)
              ->andWhere('d.cluster = :cluster')
              ->setParameter('cluster', $wish->getDepartment()->getCluster());

        return $query->getQuery()
                     ->getResult();
      } else {
          return array($wish);
      }
  }
}
