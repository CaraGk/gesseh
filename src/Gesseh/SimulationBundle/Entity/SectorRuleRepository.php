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
 * SectorRuleRepository
 */
class SectorRuleRepository extends EntityRepository
{
  public function getAll()
  {
    $query = $this->createQueryBuilder('r')
                  ->join('r.sector', 's')
                  ->join('r.grade', 'g')
                  ->addSelect('s')
                  ->addSelect('g')
                  ->addOrderBy('g.rank', 'asc')
                  ->addOrderBy('r.relation', 'asc');

    return $query->getQuery()->getResult();
  }

  public function getForStudent($simstudent, $em)
  {
    $query = $this->createQueryBuilder('r')
                  ->join('r.sector', 's')
                  ->addSelect('s')
                  ->where('r.grade = :grade_id')
                    ->setParameter('grade_id', $simstudent->getStudent()->getGrade()->getId())
                  ->orderBy('r.relation', 'desc');

    $results = $query->getQuery()->getResult();
    $rules['sector']['NOT'] = $rules['department']['NOT'] = $rules['department']['IN'] = array();

    foreach ($results as $result) {
      if ($result->getRelation() == "NOT") {  /* sector forbidden for the student's prom' */
        array_push($rules['sector']['NOT'], $result->getSector()->getId());
      } elseif ($result->getRelation() == "FULL") {  /* sector must be complete after the student's prom' */
        $sector_id = $result->getSector()->getId();

        $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndSector($period->getId(), $sector_id);  /* repartitions and departments from sector */
        $departments = $em->getRepository('GessehCoreBundle:Department')->getBySector($sector_id);  /* departments from sector */
        $placements = $em->getRepository('GessehCoreBundle:Department')->getByStudent($simstudent->getStudent()->getId()); /* student's placements */

        if (array_intersect($placements, $departments)) { /* if student did allready go to a department from sector, she don't have to do it again */
          continue;
        }

        $not_grades_rule = $this->getNOTGradeBySector($sector_id);  /* prom's that can't do that sector */
        $count_student_after = $em->getRepository('GessehSimulationBundle:Simulation')->countValidStudentAfter($simstudent, $not_grades_rule);
        $total_extra = 0;
        $list = array();

        foreach ($departments as $department) {
          $simul_extra = $em->getRepository('GessehSimulationBundle:Simulation')->getDepartmentExtraForStudent($simstudent, $department);
          if (isset($simul_extra)) {
            $total_extra += $simul_extra->getExtra();
          } else {
              foreach($department->getRepartitions() as $repartition) {
                  $total_extra += $repartition->getNumber();
              }
          }
          array_push($list, $department->getId());
        }

        if ($total_extra > (int) $count_student_after) {
          $rules['department']['IN'] = array_merge($rules['department']['IN'], $list);
        }
      }
    }

    $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getStudentWishList($simstudent->getId()); /* student's wish list */

    foreach ($wishes as $wish) {
        array_push($rules['department']['NOT'], $wish->getDepartment()->getId()); /* don't choose again a department you've already chosen */
        if ($wish->getDepartment()->getCluster() != null) { /* don't choose a department linked to a department you've already chosen */
            $clusters = $em->getRepository('GessehCoreBundle:Department')->getAllCluster($wish->getDepartment()->getId());
            foreach ($clusters as $cluster) {
                array_push($rules['department']['NOT'], $cluster->getId());
            }
        }
    }

    return $rules;
  }

  public function getNOTGradeBySector($sector_id)
  {
    $query = $this->createQueryBuilder('r')
                  ->join('r.sector', 's')
                  ->where('s.id = :sector_id')
                    ->setParameter('sector_id', $sector_id)
                  ->andWhere('r.relation = \'NOT\'');

    return $query->getQuery()->getResult();
  }
}
