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
                ->join('s.grade', 'g')
                ->addSelect('u')
                ->addSelect('g');
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
    $query->addOrderBy('g.isActive', 'desc')
          ->addOrderBy('s.surname', 'asc');

    if ($search != null) {
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
            $query->join('s.grade', 'g')
                ->where('g.isActive = true');
        }

      if ($search != null) {
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
          ->andWhere('g.isActive = true')
          ->addOrderBy('g.rank', 'desc')
          ->addOrderBy('s.graduate', 'asc')
          ->addOrderBy('s.ranking', 'asc');

    return $query->getQuery()->getResult();
  }

  public function getWithPlacementNotIn($notInList)
  {
      $query = $this->getStudentQuery();
      $query->join('s.placements', 'p')
          ->addSelect('p')
          ->where('g.isActive = true')
          ->andWhere('p.id NOT IN (' . implode(',', $notInList) . ')');

      return $query->getQuery()->getResult();
  }

    public function getMailsByGrade($grade_id)
    {
        $query = $this->getStudentQuery();
        $query->where('s.grade = :grade_id')
            ->setParameter('grade_id', $grade_id);

        $result= $query->getQuery()->getResult();
        $list = "";

        foreach ($result as $student) {
            $list .= $student->getUser()->getEmail() . ", ";
        }

        return $list;
    }
}
