<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * StudentRepository
 */
class StudentRepository extends EntityRepository
{
  public function getStudentQuery()
  {
    return $this->createQueryBuilder('s')
                ->join('s.user', 'u')
                ->join('s.grade', 'p')
                ->addSelect('u')
                ->addSelect('p');
  }

  public function getById($id)
  {
    $query = $this->getStudentQuery();
    $query->where('s.id = :id')
          ->setParameter('id', $id);

    return $query->getQuery()
                 ->getSingleResult();
  }

  public function getAll($search = null)
  {
    $query = $this->getStudentQuery();
    $query->addOrderBy('p.isActive', 'desc')
          ->addOrderBy('s.surname', 'asc');

    if($search != null) {
        $query->where('s.surname like :search')
              ->setParameter('search', '%'.$search.'%');
    }

    return $query->getQuery();
  }

    public function countAll($active = true, $search = null)
    {
        $query=$this->createQueryBuilder('s')
            ->select('COUNT(s)');

        if ($active) {
            $query->join('s.grade', 'p')
                ->where('p.isActive = true');
        }

      if($search != null) {
          $query->andWhere('s.surname like :search')
                ->setParameter('search', '%'.$search.'%');
      }

        return $query->getQuery()
            ->getSingleScalarResult();
    }

  public function getByUsername($username)
  {
    $query = $this->getStudentQuery();
    $query->where('u.username = :username')
            ->setParameter('username', $username);

    return $query->getQuery()
                 ->getSingleResult();
  }

  public function setGradeUp($current_grade, $next_grade)
  {
    $query = $this->getManager()
                    ->createQuery('UPDATE GessehUserBundle:Student s SET s.grade = :next_grade WHERE s.grade = :current_grade')
                      ->setParameters(array(
                        'current_grade' => $current_grade,
                        'next_grade' => $next_grade,
                      ));

    return $query->getResult();
  }

  public function getRankingOrder()
  {
    $query = $this->getStudentQuery();
    $query->where('u.enabled = true')
          ->andWhere('p.isActive = true')
          ->addOrderBy('p.rank', 'desc')
          ->addOrderBy('s.graduate', 'asc')
          ->addOrderBy('s.ranking', 'asc');

    return $query->getQuery()->getResult();
  }
}
