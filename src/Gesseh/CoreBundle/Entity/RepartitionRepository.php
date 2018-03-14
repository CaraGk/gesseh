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
                    ->leftJoin('d.accreditations', 'a')
                    ->leftJoin('a.sector', 's')
                    ->addSelect('d')
                    ->addSelect('h')
                    ->addSelect('a')
                    ->addSelect('s')
        ;
    }

    public function getByPeriodQuery($period)
    {
        $query = $this->getBaseQuery();
        return $query->where('p.id = :period')
            ->andWhere('a.begin <= :period_begin')
            ->andWhere('a.end >= :period_end')
            ->setParameter('period', $period->getId())
            ->setParameter('period_begin', $period->getBegin())
            ->setParameter('period_end', $period->getEnd())
            ->addOrderBy('h.name', 'asc')
            ->addOrderBy('d.name', 'asc')
        ;
    }

    public function getAvailableQuery($period)
    {
        $query = $this->getByPeriodQuery($period);
        $query->andWhere('r.number > 0');
        return $query;
    }

    public function getAvailable($period)
    {
        $query = $this->getAvailableQuery($period);

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getAvailableForSector($period, $sector_id)
    {
        $query = $this->getAvailableQuery($period);
        $query->andWhere('s.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
        ;
        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriod($period, $hospital_id = null)
    {
        $query = $this->getByPeriodQuery($period);

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

    public function getByPeriodAndDepartmentSector($period, $sector_id)
    {
        $query = $this->getByPeriodQuery($period);
        $query->andWhere('a.end > :now')
              ->setParameter('now', new \DateTime('now'))
              ->andWhere('s.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriodAndCluster($period, $cluster)
    {
        $query = $this->getByPeriodQuery($period);
        $query->andWhere('r.cluster = :cluster')
              ->setParameter('cluster', $cluster)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriodAndDepartment($period, $department_id)
    {
        $query = $this->getByPeriodQuery($period);
        $query->andWhere('d.id = :department_id')
            ->setParameter('department_id', $department_id)
            ->setMaxResults(1)
        ;

        return $query->getQuery()
                     ->getOneOrNullResult()
        ;
    }

    public function getFirstBeforeDateByDepartment($department_id, \DateTime $date)
    {
        $query = $this->getBaseQuery();
        $query->where('d.id = :department_id')
            ->setParameter('department_id', $department_id)
            ->andWhere('p.end < :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('p.end', 'desc')
            ->setMaxResults(1)
        ;

        return $query->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
