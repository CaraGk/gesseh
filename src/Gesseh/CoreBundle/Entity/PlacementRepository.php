<?php

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlacementRepository
 */
class PlacementRepository extends EntityRepository
{
  public function getPlacementQuery()
  {
    return $this->createQueryBuilder('p')
                ->join('p.period', 'q')
                ->join('p.student', 's')
                ->join('p.department', 'd')
                ->join('s.user', 'u')
                ->join('d.hospital', 'h')
                ->join('d.sector', 't')
                ->addSelect('q')
                ->addSelect('d')
                ->addSelect('h')
                ->addSelect('t');
  }

  public function getByUsername($user)
  {
    $query = $this->getPlacementQuery();
    $query->where('u.username = :user')
            ->setParameter('user', $user)
          ->addOrderBy('q.begin', 'desc')
          ->addOrderBy('h.name', 'asc')
          ->addOrderBy('d.name', 'asc');

    return $query->getQuery()->getResult();
  }

  public function getAll()
  {
    $query = $this->getPlacementQuery();
    $query->addOrderBy('q.begin', 'desc')
          ->addOrderBy('s.surname', 'asc')
          ->addOrderBy('s.name', 'asc')
          ->addOrderBy('h.name', 'asc')
          ->addOrderBy('d.name', 'asc')
          ->addSelect('s');

    return $query->getQuery();
  }
}
