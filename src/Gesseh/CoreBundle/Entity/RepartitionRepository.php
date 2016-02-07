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
    public function getQuery()
    {
        return $this->createQueryBuilder('r')
                    ->join('r.department', 'd')
                    ->join('r.period', 'p')
                    ->join('d.hospital', 'h')
                    ->join('d.sector', 's')
                    ->addSelect('d')
                    ->addSelect('h')
                    ->addSelect('s')
        ;
    }

    public function getAvailable($period_id)
    {
        $query = $this->getQuery();
        $query->where('r.number > 0')
              ->andWhere('p.id = :period_id')
              ->setParameter('period_id', $period_id)
        ;

        return $query->getQuery()
            ->getResult();
    }

    public function getByPeriod($period_id)
    {
        $query = $this->getQuery();
        $query->where('p.id = :period_id')
              ->setParameter('period_id', $period_id)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByPeriodAndDepartmentSector($period_id, $sector_id)
    {
        $query = $this->getQuery();
        $query->where('p.id = :period_id')
              ->setParameter('period_id', $period_id)
              ->andWhere('s.id = :sector_id')
              ->setParameter('sector_id', $sector_id)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

}
