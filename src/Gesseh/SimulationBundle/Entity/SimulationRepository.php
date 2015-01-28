<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SimulationRepository
 */
class SimulationRepository extends EntityRepository
{
  public function getAll()
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
//                  ->join('t.department', 'd')
//                  ->join('d.hospital', 'h')
                  ->addSelect('s')
//                  ->addSelect('d')
                  ->orderBy('t.id', 'asc');

    return $query->getQuery();
  }

  public function getSimulationQuery()
  {
    return $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->join('s.grade', 'g')
                  ->join('s.user', 'u')
                  ->addSelect('s')
                  ->addSelect('g');
  }

  public function getByUsername($user)
  {
    $query = $this->getSimulationQuery();
    $query->where('u.username = :user')
            ->setParameter('user', $user);

    return $query->getQuery()->getSingleResult();
  }

  public function getSimStudent($id)
  {
    $query = $this->getSimulationQuery();
//    $query->join('t.wishes', 'w')
//          ->addSelect('w')
    $query->where('t.id = :id')
            ->setParameter('id', $id);

    return $query->getQuery()->getSingleResult();
  }

  public function setSimulationTable($students, $em)
  {
    $count = 1;

    foreach ($students as $student) {
      $simulation = new Simulation();
      $simulation->setId($count);
      $simulation->setStudent($student);
      $simulation->setActive(true);
      $simulation->setDepartment(null);
      $em->persist($simulation);
      $count++;
    }

    $em->flush();

    return --$count;
  }

  public function doSimulation($department_table, \Doctrine\ORM\EntityManager $em)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.wishes', 'w')
                  ->join('w.department', 'd')
                  ->addOrderBy('t.id', 'asc');

    $sims = $query->getQuery()->getResult();

    foreach ($sims as $sim) {
      $student = $sim->getStudent();
      $sim->setDepartment(null);
      $sim->setExtra(null);
      if(false == $sim->getActive())
        continue;
      foreach ($sim->getWishes() as $wish) {
        if (null === $sim->getDepartment() and $department_table[$wish->getDepartment()->getId()] > 0) {
          if (null != $wish->getDepartment()->getCluster()) {
              foreach ($department_table['cl_' . $wish->getDepartment()->getCluster()] as $department_id) {
                $department_table[$department_id]--;
              }
          } else {
              $department_table[$wish->getDepartment()->getId()]--;
          }
          $sim->setDepartment($wish->getDepartment());
          $sim->setExtra($department_table[$wish->getDepartment()->getId()]);
        }
      }
      $em->persist($sim);
    }
    $em->flush();
  }

  public function countTotal()
  {
      $query = $this->createQueryBuilder('t')
          ->select('COUNT(t.id)');

      return $query->getQuery()->getSingleScalarResult();
  }

  public function countMissing($simstudent = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->select('COUNT(t.id)')
                  ->where('t.department IS NULL');

    if ($simstudent !== null) {
      $query->andWhere('t.id < :id')
              ->setParameter('id', $simstudent->getId());
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countNotActive($simstudent = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->select('COUNT(t.id)')
                  ->where('t.active = false');

    if ($simstudent !== null) {
      $query->andWhere('t.id < :id')
              ->setParameter('id', $simstudent->getId());
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countFromGradeAfter($simstudent)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->select('COUNT(t.id)')
                  ->where('t.id > :simstudent_id')
                    ->setParameter('simstudent_id', $simstudent->getId())
                  ->andWhere('s.grade = :grade_id')
                    ->setParameter('grade_id', $simstudent->getStudent()->getGrade()->getId());

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countValidStudentAfter($simstudent, $not_grades_rule = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->select('COUNT(t.id)')
                  ->where('t.id > :simstudent_id')
                    ->setParameter('simstudent_id', $simstudent->getId())
                  ->andWhere('t.active = true');

    if (isset($not_grades_rule)) {
      foreach ($not_grades_rule as $rule) {
        $query->andWhere('s.grade != ' . $rule->getGrade()->getId());
      }
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function getDepartmentExtraForStudent($simstudent, $department)
  {
    $query = $this->createQueryBuilder('t')
                  ->where('t.id < :simstudent_id')
                    ->setParameter('simstudent_id', $simstudent->getId())
                  ->andWhere('t.department = :department_id')
                    ->setParameter('department_id', $department->getId())
                  ->orderBy('t.extra', 'asc')
                  ->setMaxResults(1);

    return $query->getQuery()->getOneOrNullResult();
  }

  public function checkNotFullInSector($simstudent, $sector)
  {
    $student_after = $this->countFromGradeAfterWithValidSector($simstudent, $sector);

    $query = $this->createQueryBuilder('t')
                  ->join('t.department', 'd')
                  ->where('d.sector = :sector_id')
                    ->setParameter('sector_id', $sector->getId())
                  ->andWhere('t.extra > :student_after')
                    ->setParameter('student_after', $student_after)
                  ->addSelect('d');

    return $query->getQuery()->getResult();
  }

  public function getAllValid()
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.student', 's')
                  ->join('t.department', 'd')
                  ->addSelect('s')
                  ->addSelect('d');

    return $query->getQuery()->getResult();
  }

  public function getNumberLeft($department_id, $rank)
  {
    $query = $this->createQueryBuilder('t')
                  ->where('t.id < :rank')
                    ->setParameter('rank', $rank)
                  ->andWhere('t.department = :department_id')
                    ->setParameter('department_id', $department_id)
                  ->orderBy('t.id', 'desc')
                  ->setMaxResults(1);

    return $query->getQuery()->getOneOrNullResult();
  }
}
