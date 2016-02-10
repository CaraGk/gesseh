<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SimulPeriodRepository
 */
class SimulPeriodRepository extends EntityRepository
{
  public function isSimulationActive()
  {
    $query = $this->createQueryBuilder('s')
                  ->where('s.begin < :now')
                  ->andWhere('s.end > :now')
                    ->setParameter('now', new \DateTime('now'));

    if ($query->getQuery()->getResult())
      return true;
    else
      return false;
  }

  public function getLastActive()
  {
    $query = $this->createQueryBuilder('s')
                  ->join('s.period', 'p')
                  ->addSelect('p')
                  ->where('s.end < :now')
                  ->andWhere('s.begin < :now')
                    ->setParameter('now', new \DateTime('now'))
                  ->orderBy('s.end', 'desc')
                  ->setMaxResults(1);

    return $query->getQuery()->getSingleResult();
  }

  public function getActive()
  {
    $query = $this->createQueryBuilder('s')
                  ->where('s.begin < :now')
                  ->andWhere('s.end > :now')
                  ->setParameter('now', new \DateTime('now'))
                  ->setMaxResults(1);
    ;

    return $query->getQuery()
                 ->getOneOrNullResult()
    ;
  }

  public function getLast()
  {
    $query = $this->createQueryBuilder('s')
                  ->where('s.begin < :now')
                  ->setParameter('now', new \DateTime('now'))
                  ->orderBy('s.end', 'desc')
                  ->setMaxResults(1);
    ;

    return $query->getQuery()
                 ->getOneOrNullResult()
    ;
  }
}
