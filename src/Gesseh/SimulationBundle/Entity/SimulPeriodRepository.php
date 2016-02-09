<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
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
    $query = $this->createQueryBuilder('p')
                  ->where('p.begin < :now')
                  ->andWhere('p.end > :now')
                    ->setParameter('now', new \DateTime('now'));

    if ($query->getQuery()->getResult())
      return true;
    else
      return false;
  }

  public function getLastActive()
  {
    $query = $this->createQueryBuilder('p')
                  ->join('p.period', 'q')
                  ->addSelect('q')
                  ->where('p.end < :now')
                  ->andWhere('q.begin < :now')
                    ->setParameter('now', new \DateTime('now'))
                  ->orderBy('p.end', 'desc')
                  ->setMaxResults(1);

    return $query->getQuery()->getSingleResult();
  }

  public function getActive()
  {
    $query = $this->createQueryBuilder('p')
                  ->where('p.begin < :now')
                  ->andWhere('p.end > :now')
                  ->setParameter('now', new \DateTime('now'))
                  ->setMaxResults(1);
    ;

    return $query->getQuery()
                 ->getOneOrNullResult()
    ;
  }

  public function getLast()
  {
    $query = $this->createQueryBuilder('p')
                  ->where('p.begin < :now')
                  ->setParameter('now', new \DateTime('now'))
                  ->orderBy('p.end', 'desc')
                  ->setMaxResults(1);
    ;

    return $query->getQuery()
                 ->getOneOrNullResult()
    ;
  }
}
