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
  public function getEvaluationQuery()
  {
    return $this->createQueryBuilder('e')
                ->join('e.placement', 'p')
                ->join('p.department', 'd')
                ->join('e.evalCriteria', 'c')
    ;
  }

  public function getTextByDepartment($id)
  {
    $query = $this->getEvaluationQuery();
    $query->where('d.id = :id')
            ->setParameter('id', $id)
          ->andWhere('c.type = 2')
          ->addOrderBy('c.rank', 'asc')
          ->addOrderBy('e.created_at', 'asc');

    return $query->getQuery()->getResult();
  }

  public function getNumByDepartment($id)
  {
    $query = $this->getEvaluationQuery();
    $query->where('d.id = :id')
            ->setParameter('id', $id)
          ->andWhere('c.type = 1')
          ->orderBy('c.name', 'asc');

    $results = $query->getQuery()->getResult();
    $calc = array();

    foreach ($results as $result)
    {
      if (!isset($calc[$result->getEvalCriteria()->getId()]['count'])) {
        $calc[$result->getEvalCriteria()->getId()]['total'] = 0;
        $calc[$result->getEvalCriteria()->getId()]['count'] = 0;
        $calc[$result->getEvalCriteria()->getId()]['name'] = $result->getEvalCriteria()->getName();
      }

      $calc[$result->getEvalCriteria()->getId()]['total'] += (int) $result->getValue();
      $calc[$result->getEvalCriteria()->getId()]['count'] ++;
      $calc[$result->getEvalCriteria()->getId()]['mean'] = round($calc[$result->getEvalCriteria()->getId()]['total'] / $calc[$result->getEvalCriteria()->getId()]['count'], 1);
//      $calc[$result->getEvalCriteria()->getId()]['ratio'] = $result->getEvalCriteria()->getRatio();
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

  public function getAllText()
  {
    $query = $this->getEvaluationQuery();
    $query->where('c.type = 2')
          ->addOrderBy('e.created_at', 'asc');

    return $query->getQuery();
  }
}
