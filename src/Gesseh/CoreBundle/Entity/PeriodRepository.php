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
 * PeriodRepository
 */
class PeriodRepository extends EntityRepository
{
    public function getCurrent()
    {
        return $this->createQueryBuilder('p')
                    ->where('p.begin > :now')
                    ->andWhere('p.end < :now')
                    ->setParameter('now', date("Y-m-d"))
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
