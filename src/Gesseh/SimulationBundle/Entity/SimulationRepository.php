<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
                  ->join('t.person', 's')
                  ->addSelect('s')
                  ->orderBy('t.rank', 'asc');

    return $query->getQuery();
  }

  public function getSimulationQuery()
  {
    return $this->createQueryBuilder('t')
                  ->join('t.person', 's')
                  ->join('s.grade', 'g')
                  ->join('s.user', 'u')
                  ->addSelect('s')
                  ->addSelect('g');
  }

  public function getByUser($user)
  {
    $query = $this->getSimulationQuery();
    $query->where('u.id = :user')
            ->setParameter('user', $user->getId());

    return $query->getQuery()->getOneOrNullResult();
  }

  public function getSimPerson($id)
  {
    $query = $this->getSimulationQuery();
    $query->where('t.id = :id')
            ->setParameter('id', $id);

    return $query->getQuery()->getSingleResult();
  }

  public function setSimulationTable($persons, $em)
  {
    $count = 1;

    foreach ($persons as $person) {
      $simulation = new Simulation();
      $simulation->setId($count);
      $simulation->setRank($count);
      $simulation->setPerson($person);
      $simulation->setActive(true);
      $simulation->setDepartment(null);
      $em->persist($simulation);
      $count++;
    }

    $em->flush();

    return --$count;
  }

  public function doSimulation($department_table, \Doctrine\ORM\EntityManager $em, $period)
  {
      $query = $this->createQueryBuilder('t')
          ->join('t.wishes', 'w')
          ->join('w.department', 'd')
          ->addOrderBy('t.rank', 'asc');

    $sims = $query->getQuery()->getResult();

    foreach ($sims as $sim) {
        $person = $sim->getPerson();
        if(false == $sim->getActive())
            continue;
        if (null == $sim->isValidated()) {
            $sim->setDepartment(null);
            $sim->setExtra(null);
            foreach ($sim->getWishes() as $wish) {
                if (null === $sim->getDepartment() and $department_table[$wish->getDepartment()->getId()] > 0) {
                    if($current_repartition = $wish->getDepartment()->findRepartition($period)) {
                        if ($cluster_name = $current_repartition->getCluster()) {
                            foreach ($department_table['cl_' . $cluster_name] as $department_id) {
                                $department_table[$department_id]--;
                            }
                        } else {
                            $department_table[$wish->getDepartment()->getId()]--;
                        }
                    }
                    $sim->setDepartment($wish->getDepartment());
                    $sim->setExtra($department_table[$wish->getDepartment()->getId()]);
                }
            }
        } else {
            if($current_repartition = $sim->getDepartment()->findRepartition($period)) {
                if ($cluster_name = $current_repartition->getCluster()) {
                    foreach ($department_table['cl_' . $cluster_name] as $department_id) {
                        $department_table[$department_id]--;
                    }
                } else {
                    if (isset($department_table[$sim->getDepartment()->getId()]))
                        $department_table[$sim->getDepartment()->getId()]--;
                }
            }
            if (isset($department_table[$sim->getDepartment()->getId()]))
                $sim->setExtra($department_table[$sim->getDepartment()->getId()]);
            else
                $sim->setExtra(0);
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

  public function countMissing($simperson = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->select('COUNT(t.id)')
                  ->where('t.department IS NULL');

    if ($simperson !== null) {
      $query->andWhere('t.rank < :rank')
              ->setParameter('rank', $simperson->getRank());
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countNotActive($simperson = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->select('COUNT(t.id)')
                  ->where('t.active = false');

    if ($simperson !== null) {
      $query->andWhere('t.rank < :rank')
              ->setParameter('rank', $simperson->getRank());
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countFromGradeAfter($simperson)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.person', 's')
                  ->select('COUNT(t.id)')
                  ->where('t.rank > :simperson_rank')
                    ->setParameter('simperson_rank', $simperson->getRank())
                  ->andWhere('s.grade = :grade_id')
                    ->setParameter('grade_id', $simperson->getPerson()->getGrade()->getId());

    return $query->getQuery()->getSingleScalarResult();
  }

  public function countValidPersonAfter($simperson, $not_grades_rule = null)
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.person', 's')
                  ->select('COUNT(t.id)')
                  ->where('t.rank > :simperson_rank')
                    ->setParameter('simperson_rank', $simperson->getRank())
                  ->andWhere('t.active = true');

    if (isset($not_grades_rule)) {
      foreach ($not_grades_rule as $rule) {
        $query->andWhere('s.grade != ' . $rule->getGrade()->getId());
      }
    }

    return $query->getQuery()->getSingleScalarResult();
  }

  public function getDepartmentExtraForPerson($simperson, $department)
  {
    $query = $this->createQueryBuilder('t')
                  ->where('t.rank < :simperson_rank')
                    ->setParameter('simperson_rank', $simperson->getRank())
                  ->andWhere('t.department = :department_id')
                    ->setParameter('department_id', $department->getId())
                  ->orderBy('t.extra', 'asc')
                  ->setMaxResults(1);

    return $query->getQuery()->getOneOrNullResult();
  }

  public function checkNotFullInSector($simperson, $sector)
  {
    $person_after = $this->countFromGradeAfterWithValidSector($simperson, $sector);

    $query = $this->createQueryBuilder('t')
                  ->join('t.department', 'd')
                  ->join('d.accreditations', 'a')
                  ->join('a.sector', 's')
                  ->where('a.end > :now')
                  ->setParameter('now', new \DateTime('now'))
                  ->andWhere('s.id = :sector_id')
                    ->setParameter('sector_id', $sector->getId())
                  ->andWhere('t.extra > :person_after')
                    ->setParameter('person_after', $person_after)
                  ->addSelect('d');

    return $query->getQuery()->getResult();
  }

  public function getAllValid()
  {
    $query = $this->createQueryBuilder('t')
                  ->join('t.person', 's')
                  ->join('t.department', 'd')
                  ->addSelect('s')
                  ->addSelect('d');

    return $query->getQuery()->getResult();
  }

  public function getNumberLeft($department_id, $rank)
  {
    $query = $this->createQueryBuilder('t')
                  ->where('t.rank < :rank')
                    ->setParameter('rank', $rank)
                  ->andWhere('t.department = :department_id')
                    ->setParameter('department_id', $department_id)
                  ->orderBy('t.rank', 'desc')
                  ->setMaxResults(1);

    return $query->getQuery()->getOneOrNullResult();
  }

    public function getDepartmentLeftQuery($period_id)
    {
        $query = $this->createQueryBuilder('t')
                      ->join('t.department', 'd')
                      ->join('d.repartitions', 'r')
                      ->join('r.period', 'p')
                      ->addSelect('d')
                      ->addSelect('r')
                      ->andWhere('p.id = :period_id')
                      ->setParameter('period_id', $period_id)
                      ->orderBy('t.rank', 'desc')
        ;
        return $query;
    }

    public function getDepartmentLeftForRank($rank, $period_id)
    {
        $query = $this->getDepartmentLeftQuery($period_id);
        $query->andWhere('t.rank < :rank')
            ->setParameter('rank', $rank)
            ->andWhere('t.extra is not null')
            ->orderBy('t.rank', 'asc')
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getDepartmentLeftForSector($sector_id, $period_id)
    {
        $query = $this->getDepartmentLeftQuery($period_id);
        $query->join('d.accreditations', 'a')
              ->join('a.sector', 'u')
              ->andWhere('t.extra is not null')
              ->andWhere('u.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
              ->andWhere('t.is_validated = true')
              ->orderBy('t.rank', 'asc')
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getLast()
    {
        $query = $this->createQueryBuilder('s')
            ->orderBy('s.rank', 'desc')
            ->setMaxResults(1)
        ;
        return $query->getQuery()
                     ->getOneOrNullResult()
        ;
    }
}
