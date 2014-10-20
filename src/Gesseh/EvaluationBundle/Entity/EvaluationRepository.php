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
     * Récupère les évaluations textuelles (texte long) d'un terrain de stage
     *
     * returns QueryResult
     */
    public function getTextByDepartment($id, $date = null)
    {
        $query = $this->getEvaluationQuery();
        $query->where('d.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.type = 2')
            ->andWhere('not(c.moderate = true and e.moderated = false)')
            ->addOrderBy('c.rank', 'asc')
            ->addOrderBy('e.created_at', 'asc')
        ;

        if ($date != null) {
            $query->andWhere('e.created_at > :date')
                ->setParameter('date', $date)
            ;
        }

        return $query->getQuery()->getResult();
    }

    /*
     * Récupère les évaluations numériques d'un terrain de stage
     *
     * @returns array
     */
    public function getNumByDepartment($id, $date = null)
    {
        $query = $this->getEvaluationQuery();
        $query->join('p.period', 'q')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.type = 1')
            ->addOrderBy('c.name', 'asc')
            ->addOrderBy('q.begin', 'asc')
        ;

        if ($date != null) {
            $query->andWhere('e.created_at > :date')
                ->setParameter('date', $date)
            ;
        }

        $results = $query->getQuery()->getResult();
        $calc = array();

        foreach ($results as $result) {
            $criteria_id = $result->getEvalCriteria()->getId();
            $period_id = $result->getPlacement()->getPeriod()->getId();
            if (!isset($calc[$criteria_id]['count'][0])) {
                $calc[$criteria_id]['total'][0] = 0;
                $calc[$criteria_id]['count'][0] = 0;
                $calc[$criteria_id]['name'] = $result->getEvalCriteria()->getName();
            }

            if (!isset($calc[$criteria_id]['count'][$period_id])) {
                $calc[$criteria_id]['total'][$period_id] = 0;
                $calc[$criteria_id]['count'][$period_id] = 0;
            }

            $calc[$criteria_id]['total'][0] += (int) $result->getValue();
            $calc[$criteria_id]['count'][0] ++;
            $calc[$criteria_id]['mean'][0] = round($calc[$criteria_id]['total'][0] / $calc[$criteria_id]['count'][0], 1);
//            $calc[$criteria_id]['ratio'][0] = $result->getEvalCriteria()->getRatio();
            $calc[$criteria_id]['total'][$period_id] += (int) $result->getValue();
            $calc[$criteria_id]['count'][$period_id] ++;
            $calc[$criteria_id]['mean'][$period_id] = round($calc[$criteria_id]['total'][$period_id] / $calc[$criteria_id]['count'][$period_id], 1);
        }

        return $calc;
    }

    /*
     * Récupère les évaluations multiples d'un terrain de stage
     *
     * return @array
     */
    public function getMultByDepartment($id, $date = null)
    {
        $query = $this->getEvaluationQuery();
        $query->join('p.period', 'q')
            ->where('d.id = :id')
            ->setParameter('id', $id)
            ->andWhere('c.type = 3')
            ->addOrderBy('c.name', 'asc')
            ->addOrderBy('q.begin', 'asc')
        ;

        if ($date != null) {
            $query->andWhere('e.created_at > :date')
                ->setParameter('date', $date)
            ;
        }

        $results = $query->getQuery()->getResult();
        $calc = array();

        if($results) {
            foreach ($results as $result) {
                $criteria = $result->getEvalCriteria();
                $criteria_id = $criteria->getId();
                $criteria_item = $result->getValue();
                if (!isset($calc[$criteria_id]['total'])) {
                    $calc[$criteria_id]['total'] = 0;
                    $calc[$criteria_id]['name'] = $result->getEvalCriteria()->getName();
                }
                if (!isset($calc[$criteria_id]['count'][$criteria_item])) {
                    $calc[$criteria_id]['count'][$criteria_item] = 0;
                }
                $calc[$criteria_id]['count'][$criteria_item] ++;
                $calc[$criteria_id]['total'] ++;
            }

            foreach(explode('|', $criteria->getMore()) as $item) {
                if (isset($calc[$criteria->getId()]['count'][$item])) {
                    $calc[$criteria->getId()]['size'][$item] = 0.5 + round(($calc[$criteria->getId()]['count'][$item] - min($calc[$criteria->getId()]['count'])) * (2 - 0.5) / (max($calc[$criteria->getId()]['count']) - min($calc[$criteria->getId()]['count'])), 1);
                }
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
