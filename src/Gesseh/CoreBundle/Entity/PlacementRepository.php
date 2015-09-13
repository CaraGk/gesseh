<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlacementRepository
 */
class PlacementRepository extends EntityRepository
{
  public function getPlacementQuery()
  {
    return $this->createQueryBuilder('p')
                ->join('p.period', 'q')
                ->join('p.student', 's')
                ->join('p.department', 'd')
                ->join('s.user', 'u')
                ->join('d.hospital', 'h')
                ->join('d.sector', 't')
                ->addSelect('q')
                ->addSelect('d')
                ->addSelect('h')
                ->addSelect('t');
  }

  public function getByUsername($user, $id = null)
  {
    $query = $this->getPlacementQuery();
    $query->where('u.username = :user')
            ->setParameter('user', $user)
          ->addOrderBy('q.begin', 'desc')
          ->addOrderBy('h.name', 'asc')
          ->addOrderBy('d.name', 'asc');

    if ($id) {
        $query->andWhere('p.id = :id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    return $query->getQuery()->getResult();
  }

  public function getAll($limit = null)
  {
    $query = $this->getPlacementQuery();
    $query->addOrderBy('q.begin', 'desc')
          ->addOrderBy('s.surname', 'asc')
          ->addOrderBy('s.name', 'asc')
          ->addOrderBy('h.name', 'asc')
          ->addOrderBy('d.name', 'asc')
          ->addSelect('s');

    if (null != $limit and preg_match('/^[p,q,s,t,h,d].id$/', $limit['type'])) {
      $query->where($limit['type'] . ' = :value')
               ->setParameter('value', $limit['value']);
    }

    return $query->getQuery();
  }

  public function getCountByStudentWithoutCurrentPeriod($student, $current_period)
  {
      $query = $this->createQueryBuilder('p')
                    ->select('COUNT(p)')
                    ->where('p.student = :student')
                    ->setParameter('student', $student);

    if ($current_period != null) {
        $query->andWhere('p.period != :current_period')
              ->setParameter('current_period', $current_period);
    }

    return $query->getQuery()->getSingleScalarResult();
  }
}
