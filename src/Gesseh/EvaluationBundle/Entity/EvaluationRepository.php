<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EvaluationRepository
 */
class EvaluationRepository extends EntityRepository
{
    /*
     * Generic query with joins
     *
     * @return QueryBuilder
     */
    public function getEvaluationQuery()
    {
        return $this->createQueryBuilder('e')
            ->join('e.placement', 'p')
            ->join('p.department', 'd')
            ->join('e.evalCriteria', 'c')
        ;
    }

    /*
     * Récupère les évaluations d'un terrain de stage
     *
     * @return array
     */
    public function getEvalByDepartment($id, $date = null)
    {
        $query = $this->getEvaluationQuery();
        $query->join('p.period', 'q')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->andWhere('not(c.moderate = true and e.moderated = false)')
            ->addOrderBy('c.rank', 'asc')
            ->addOrderBy('q.begin', 'asc')
        ;

        if ($date != null) {
            $query->andWhere('e.created_at > :date')
                ->setParameter('date', $date)
            ;
        }

        $results = $query->getQuery()->getResult();
        $calc = array();
        $crits = array();

        foreach ($results as $result) {
            $criteria = $result->getEvalCriteria();
            $period_id = $result->getPlacement()->getPeriod()->getId();
            $value = $result->getValue();

            if (!isset($calc[$criteria->getId()]['name'][0])) {
                $calc[$criteria->getId()]['total'][0] = 0;
                $calc[$criteria->getId()]['name'] = $criteria->getName();
                $calc[$criteria->getId()]['type'] = $criteria->getType();
            }
            if (!isset($calc[$criteria->getId()]['total'][$period_id])) {
                $calc[$criteria->getId()]['total'][$period_id] = 0;
            }

            $calc[$criteria->getId()]['total'][0] ++;
            $calc[$criteria->getId()]['total'][$period_id] ++;

            if ($criteria->getType() == 2) {
                $calc[$criteria->getId()]['text'][$period_id] = $value;

            } elseif ($criteria->getType() == 1 or $criteria->getType() == 4) {
                if (!isset($calc[$criteria->getId()]['count'][0])) {
                    $calc[$criteria->getId()]['count'][0] = 0;
                }
                if (!isset($calc[$criteria->getId()]['count'][$period_id])) {
                    $calc[$criteria->getId()]['count'][$period_id] = 0;
                }

                $calc[$criteria->getId()]['count'][0] += (int) $value;
                $calc[$criteria->getId()]['mean'][0] = round($calc[$criteria->getId()]['count'][0] / $calc[$criteria->getId()]['total'][0], 1);
//                $calc[$criteria->getId()]['ratio'][0] = $result->getEvalCriteria()->getRatio();
                $calc[$criteria->getId()]['count'][$period_id] += (int) $value;
                $calc[$criteria->getId()]['mean'][$period_id] = round($calc[$criteria->getId()]['count'][$period_id] / $calc[$criteria->getId()]['total'][$period_id], 1);
            } elseif ($criteria->getType() == 3 or $criteria->getType() == 5 or $criteria->getType() == 6) {
                if (!in_array($criteria, $crits)) {
                    $crits[] = $criteria;
                }
                if (!isset($calc[$criteria->getId()]['count'][0][$value])) {
                    $calc[$criteria->getId()]['count'][0][$value] = 0;
                }
                if (!isset($calc[$criteria->getId()]['count'][$period_id][$value])) {
                    $calc[$criteria->getId()]['count'][$period_id][$value] = 0;
                }
                $calc[$criteria->getId()]['count'][0][$value] ++;
                $calc[$criteria->getId()]['count'][$period_id][$value] ++;
            }
        }

        foreach ($crits as $criteria) {
            if ($criteria->getType() == 3) {
                foreach (explode('|', $criteria->getMore()) as $item) {
                    if (isset($calc[$criteria->getId()]['count'][0][$item])) {
                        $calc[$criteria->getId()]['size'][0][$item] = 0.5 + round(($calc[$criteria->getId()]['count'][0][$item] - min($calc[$criteria->getId()]['count'][0])) * (2 - 0.5) / (max($calc[$criteria->getId()]['count'][0]) - min($calc[$criteria->getId()]['count'][0])), 1);
                    }
                }
            } elseif ($criteria->getType() == 5 or $criteria->getType() == 6) {
                $max = 1;
                if ($criteria->getMore()) {
                    $explode = explode('|', $criteria->getMore());
                    $max = (int) $explode[0];
                }
                asort($calc[$criteria->getId()]['count'][0]);
                $calc[$criteria->getId()]['max'] = $max;
            }
        }

        return $calc;
    }

  public function getEvaluatedList($type = 'array', $username = null)
  {
    $query = $this->createQueryBuilder('e')
                  ->join('e.placement', 'p')
                  ->groupBy('e.placement')
                  ->addSelect('p');

    if ($username) {
      $query->join('p.student', 's')
            ->join('s.user', 'u')
            ->where('u.username = :username')
              ->setParameter('username', $username);
    }

    $results = $query->getQuery()->getResult();
    $list = array();

    foreach ($results as $result) {
      array_push($list, $result->getPlacement()->getId());
    }

    if ($type = 'array')
      return $list;
    elseif ($type = 'list')
      return implode(',', $list);
    else
      return null;
  }

  public function getAllToModerate()
  {
    $query = $this->getEvaluationQuery();
    $query->where('e.moderated = false')
          ->addOrderBy('e.created_at', 'asc');

    return $query->getQuery();
  }

    public function studentHasNonEvaluated($student, $current_period, $count_placements)
    {
        $query = $this->getEvaluationQuery();
        $query->select('COUNT(DISTINCT p.id)')
              ->where('p.student = :student')
              ->setParameter('student', $student);

        if ($current_period != null) {
            $query->andWhere('p.period != :current_period')
                  ->setParameter('current_period', $current_period);
        }

        $count_evaluations = $query->getQuery()->getSingleScalarResult();

        if($count_evaluations < $count_placements)

            return true;
        else
            return false;
    }
}
