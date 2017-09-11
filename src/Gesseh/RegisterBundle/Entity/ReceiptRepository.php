<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReceiptRepository
 */
class ReceiptRepository extends EntityRepository
{
    public function getBaseQuery()
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.end', 'desc')
        ;
    }

    public function getAll()
    {
        return $this->getBaseQuery()
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOneByDate(\DateTime $date)
    {
        $query = $this->getBaseQuery();
        $query->andWhere('r.begin <= :date')
            ->andWhere('r.end >= :date')
            ->setParameter('date', $date)
            ->setMaxResults(1)
            ;

        return $query->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
