<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
    public function getBaseQuery()
    {
        return $this->createQueryBuilder('e')
            ->join('e.placement', 'p')
            ->join('p.repartition', 'r')
            ->join('r.department', 'd')
            ->join('e.evalCriteria', 'c')
        ;
    }

    /**
     * Récupère les évaluations d'un terrain de stage
     *
     * @return query
     */
    public function getByDepartmentQuery($id, $limit = null)
    {
        $query = $this->getBaseQuery();
        $query->join('r.period', 'q')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->andWhere('not(c.moderate = true and e.moderated = false)')
            ->addOrderBy('c.rank', 'asc')
            ->addOrderBy('q.begin', 'asc')
        ;

        if ($limit != null) {
            if ($limit['date']) {
                $query->andWhere('e.created_at > :date')
                      ->setParameter('date', $limit['date'])
                ;
            }
            if ($limit['role']) {
                $query->andWhere('c.private = false');
            }
        }

        return $query;
    }

    /**
     * Met en forme les évaluations d'un department
     *
     * @return array
     */
    public function getByDepartment($id, $limit = null)
    {
        $query = $this->getByDepartmentQuery($id, $limit);
        $results = $query->getQuery()
                         ->getResult()
        ;
        $calc = array();
        $crits = array();

        foreach ($results as $result) {
            $criteria = $result->getEvalCriteria();
            $period_id = $result->getPlacement()
                                ->getRepartition()
                                ->getPeriod()
                                ->getId()
            ;
            $value = $result->getValue();
            $criteria_id = $criteria->getId();

            if (!isset($calc[$criteria_id]['name'][0])) {
                $calc[$criteria_id]['total'][0] = 0;
                $calc[$criteria_id]['name'] = $criteria->getName();
                $calc[$criteria_id]['type'] = $criteria->getType();
            }
            if (!isset($calc[$criteria_id]['total'][$period_id])) {
                $calc[$criteria_id]['total'][$period_id] = 0;
            }

            $calc[$criteria_id]['total'][0] ++;
            $calc[$criteria_id]['total'][$period_id] ++;

            if ($criteria->getType() == 2) {
                $calc[$criteria_id]['text'][$period_id] = $value;

            } elseif ($criteria->getType() == 1 or $criteria->getType() == 4) {
                if (!isset($calc[$criteria_id]['count'][0])) {
                    $calc[$criteria_id]['count'][0] = 0;
                }
                if (!isset($calc[$criteria_id]['count'][$period_id])) {
                    $calc[$criteria_id]['count'][$period_id] = 0;
                }

                $calc[$criteria_id]['count'][0] += (int) $value;
                $calc[$criteria_id]['mean'][0] = round($calc[$criteria_id]['count'][0] / $calc[$criteria_id]['total'][0], 1);
                $calc[$criteria_id]['count'][$period_id] += (int) $value;
                $calc[$criteria_id]['mean'][$period_id] = round($calc[$criteria_id]['count'][$period_id] / $calc[$criteria_id]['total'][$period_id], 1);
            } elseif ($criteria->getType() == 3 or $criteria->getType() == 5 or $criteria->getType() == 6) {
                if (!in_array($criteria, $crits)) {
                    $crits[] = $criteria;
                }
                if (!isset($calc[$criteria_id]['count'][0][$value])) {
                    $calc[$criteria_id]['count'][0][$value] = 0;
                }
                if (!isset($calc[$criteria_id]['count'][$period_id][$value])) {
                    $calc[$criteria_id]['count'][$period_id][$value] = 0;
                }
                $calc[$criteria_id]['count'][0][$value] ++;
                $calc[$criteria_id]['count'][$period_id][$value] ++;
            }
        }

        foreach ($crits as $criteria) {
            if ($criteria->getType() == 3) {
                foreach (explode('|', $criteria->getMore()) as $item) {
                    if (isset($calc[$criteria->getId()]['count'][0][$item])) {
                        $divisor = max($calc[$criteria->getId()]['count'][0]) - min($calc[$criteria->getId()]['count'][0]);
                        if ($divisor <= 0) {
                            $divisor = 1;
                        }
                        $calc[$criteria->getId()]['size'][0][$item] = 0.5 + round(($calc[$criteria->getId()]['count'][0][$item] - min($calc[$criteria->getId()]['count'][0])) * (2 - 0.5) / $divisor, 1);
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

    /**
     * Compte le nombre d'évaluation pour un department
     *
     * return int
     */
    public function countByDepartment($id, $limit = null)
    {
        $query = $this->getByDepartmentQuery($id, $limit);
        $query->select('COUNT(DISTINCT p.id)')
              ->groupBy('p.id')
        ;
        return $query->getQuery()
                     ->getSingleScalarResult()
        ;
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
    $query = $this->getBaseQuery();
    $query->where('e.moderated = false')
          ->addOrderBy('e.created_at', 'asc');

    return $query->getQuery();
  }

    public function studentHasNonEvaluated($student, $current_period, $count_placements)
    {
        $query = $this->getBaseQuery();
        $query->select('COUNT(DISTINCT p.id)')
              ->where('p.student = :student')
              ->setParameter('student', $student);

        if ($current_period != null) {
            $query->andWhere('r.period != :current_period')
                  ->setParameter('current_period', $current_period);
        }

        $count_evaluations = $query->getQuery()->getSingleScalarResult();

        if($count_evaluations < $count_placements)

            return true;
        else
            return false;
    }

    /**
     * Récupère les évaluations d'un étudiant pour un terrain de stage
     *
     * @return ArrayCollection
     */
    public function getByPlacement($id, $limit = null)
    {
        $query = $this->getBaseQuery();
        $query->join('r.period', 'q')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->addOrderBy('c.rank', 'asc')
            ->addOrderBy('q.begin', 'asc')
        ;

        if ($limit != null) {
            if ($limit['role']) {
                $query->andWhere('c.private = false');
            }
        }

        return $query->getQuery()
                     ->getResult()
        ;
    }
}
