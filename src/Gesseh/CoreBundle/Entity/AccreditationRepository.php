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

    public function getActiveByDepartment($department_id)
    {
        $query = $this->getBaseQuery();
        $query->where('a.begin < :now')
              ->andWhere('a.end > :now')
              ->setParameter('now', new \DateTime('now'))
              ->andWhere('d.id = :department_id')
              ->setParameter('department_id', $department_id)
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }
}
