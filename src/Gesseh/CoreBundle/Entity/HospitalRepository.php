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
 * HospitalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HospitalRepository extends EntityRepository
{
  public function getHospitalQuery()
  {
    return $this->createQueryBuilder('h')
                ->join('h.departments', 'd')
                ->join('d.sector', 's')
                ->addSelect('d')
                ->addSelect('s');
  }

  public function getAllOrdered(array $orderBy = array ( 'h' => 'asc', 'd' => 'asc'))
  {
    $query = $this->getHospitalQuery();
    $query->addOrderBy('h.name', 'asc');
    foreach ($orderBy as $col => $order) {
      $query->addOrderBy($col . '.name', $order);
    }

    return $query->getQuery()
                 ->getResult();
  }

  public function getAll(array $limit = null)
  {
      $query = $this->getHospitalQuery();
      $query->addOrderBy('h.name', 'asc')
            ->addOrderBy('d.name', 'asc');

    if (null != $limit and (preg_match('/^[s,h,d].id$/', $limit['type']) or $limit['type'] == "d.cluster")) {
      $query->where($limit['type'] . ' = :value')
            ->setParameter('value', $limit['value']);
    }

      return $query->getQuery()
                   ->getResult();
  }
}
