<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RepartitionRepository
 */
class RepartitionRepository extends EntityRepository
{
    public function getBaseQuery()
    {
        return $this->createQueryBuilder('r')
                    ->join('r.department', 'd')
                    ->join('r.period', 'p')
                    ->join('d.hospital', 'h')
                    ->join('d.accreditations', 'a')
                    ->join('a.sector', 's')
                    ->addSelect('d')
                    ->addSelect('h')
                    ->addSelect('a')
                    ->addSelect('s')
        ;
    }

    public function getByPeriodQuery($period_id)
    {
        $query = $this->getBaseQuery();
        return $query->where('p.id = :period_id')
                     ->setParameter('period_id', $period_id)
                     ->addOrderBy('h.name', 'asc')
                     ->addOrderBy('d.name', 'asc')
        ;
    }

    public function getAvailableQuery($period_id)
    {
        $query = $this->getByPeriodQuery($period_id);
        $query->andWhere('r.number > 0');
        return $query;
    }

    public function getAvailable($period_id)
    {
        $query = $this->getAvailableQuery($period_id);

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getAvailableForSector($period_id, $sector_id)
    {
        $query = $this->getAvailableQuery($period_id);
        $query->andWhere('s.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
        ;
        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriod($period_id, $hospital_id = null)
    {
        $query = $this->getByPeriodQuery($period_id);

        if ($hospital_id != null) {
            $query->andWhere('h.id = :hospital_id')
                  ->setParameter('hospital_id', $hospital_id)
            ;
        }

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByDepartment($department_id)
    {
        return $this->getBaseQuery()
                    ->where('d.id = :department_id')
                    ->setParameter('department_id', $department_id)
                    ->orderBy('p.begin', 'desc')
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function getByPeriodAndDepartmentSector($period_id, $sector_id)
    {
        $query = $this->getByPeriodQuery($period_id);
        $query->andWhere('a.end > :now')
              ->setParameter('now', new \DateTime('now'))
              ->andWhere('s.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriodAndCluster($period_id, $cluster)
    {
        $query = $this->getByPeriodQuery($period_id);
        $query->andWhere('r.cluster = :cluster')
              ->setParameter('cluster', $cluster)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriodAndDepartment($period_id, $department_id)
    {
        $query = $this->getByPeriodQuery($period_id);
        $query->andWhere('d.id = :department_id')
              ->setParameter('department_id', $department_id)
        ;

        return $query->getQuery()
                     ->getOneOrNullResult()
        ;
    }
}
