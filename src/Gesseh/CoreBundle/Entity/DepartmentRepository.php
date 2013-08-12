<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * DepartmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepartmentRepository extends EntityRepository
{
  public function getDepartmentQuery()
  {
    return $this->createQueryBuilder('d')
                ->join('d.hospital', 'h')
                ->join('d.sector', 's')
                ->addSelect('h')
                ->addSelect('s');
  }

  public function getById($id)
  {
    $query = $this->getDepartmentQuery();
    $query->where('d.id = :id')
          ->setParameter('id', $id);

    return $query->getQuery()
                 ->getSingleResult();
  }

  public function getByStudent($student_id)
  {
    $query = $this->getDepartmentQuery();
    $query->join('d.placements', 'p')
          ->join('p.student', 't')
          ->where('t.id = :student_id')
            ->setParameter('student_id', $student_id);

    return $query->getQuery()->getResult();
  }

  public function getBySector($sector_id)
  {
    $query = $this->getDepartmentQuery();
    $query->where('s.id = :sector_id')
            ->setParameter('sector_id', $sector_id);

    return $query->getQuery()->getResult();
  }

  public function getAll(array $orderBy = array('h' => 'asc', 's' => 'asc'))
  {
    $query = $this->getDepartmentQuery();
    foreach ( $orderBy as $col => $order ) {
      $query->addOrderBy($col . '.name', $order);
    }

    return $query->getQuery()
                 ->getResult();
  }

  public function getAllCluster($id)
  {
      $department = $this->getById($id);

      if(null != $department->getCluster()) {
        $query = $this->getDepartmentQuery();
        $query->where('d.cluster = :cluster')
              ->setParameter('cluster', $department->getCluster());

        return $query->getQuery()
                     ->getResult();
      } else {
        return false;
      }
  }

  public function getAdaptedUserList($rules)
  {
    $query = $this->getDepartmentQuery();
    $query->addOrderBy('h.name', 'asc')
          ->addOrderBy('d.name', 'asc')
          ->where('d.number > 0');

    if ($rules['department']['NOT'])
      $query->andWhere('d.id NOT IN (' . implode(',', $rules['department']['NOT']) . ')');
    if ($rules['sector']['NOT'])
      $query->andWhere('d.sector NOT IN (' . implode(',', $rules['sector']['NOT']) . ')');
    if ($rules['department']['IN'])
      $query->andWhere('d.id IN (' . implode(',', $rules['department']['IN']) . ')');

    return $query;
  }
}
