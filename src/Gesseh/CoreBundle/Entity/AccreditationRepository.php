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
 * Accreditation Repository
 */
class AccreditationRepository extends EntityRepository
{
    public function getBaseQuery()
    {
        return $this->createQueryBuilder('a')
                    ->join('a.department', 'd')
                    ->join('a.sector', 's')
                    ->addSelect('d')
                    ->addSelect('s')
        ;
    }

    public function getByDepartmentQuery($department_id)
    {
        $query = $this->getBaseQuery();
        return $query->where('d.id = :department_id')
                     ->setParameter('department_id', $department_id)
        ;
    }

    public function getActiveByDepartmentQuery($department_id)
    {
        $query = $this->getByDepartmentQuery($department_id);
        return $query->andWhere('a.begin < :now')
                     ->andWhere('a.end > :now')
                     ->setParameter('now', new \DateTime('now'))
        ;
    }

    public function getActiveByDepartment($department_id)
    {
        $query = $this->getActiveByDepartmentQuery($department_id);
        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByDepartmentAndPeriod($department_id, $period)
    {
        $query = $this->getByDepartmentQuery($department_id);
        $query->andWhere('a.begin < :period_end')
              ->setParameter('period_end', $period->getEnd())
              ->andWhere('a.end > :period_begin')
              ->setParameter('period_begin', $period->getBegin())
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getByDepartmentAndUser($department_id, $user_id)
    {
        $query = $this->getActiveByDepartmentQuery($department_id);
        $query->join('a.user', 'u')
              ->andWhere('u.id = :user_id')
              ->setParameter('user_id', $user_id)
        ;
        return $query->getQuery()
                     ->getResult()
        ;
    }
}
