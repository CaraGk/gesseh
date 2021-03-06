<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * HospitalRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class HospitalRepository extends EntityRepository
{
    public function getHospitalQuery()
    {
        return $this->createQueryBuilder('h')
                    ->leftJoin('h.departments', 'd')
                    ->leftJoin('d.accreditations', 'a')
                    ->leftJoin('a.sector', 's')
                    ->addSelect('d')
                    ->addSelect('a')
                    ->addSelect('s')
        ;
    }

    public function getAllOrdered(array $orderBy = array ( 'h' => 'asc', 'd' => 'asc'))
    {
        $query = $this->getHospitalQuery();
        $query->addOrderBy('h.name', 'asc');
        foreach ($orderBy as $col => $order) {
            $query->addOrderBy($col . '.name', $order);
        }
        $query->andWhere('a.end > :now')
              ->setParameter('now', new \DateTime('now'))
        ;

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getAllWithDepartments(array $arg)
    {
        $query = $this->getHospitalQuery();

        if(null != $arg['period']) {
            $query->leftJoin('d.repartitions', 'r')
                  ->leftJoin('r.period', 'p')
                  ->addSelect('r')
                  ->andWhere('p.id = :id')
                  ->setParameter('id', $arg['period'])
            ;
        }

        $query->addOrderBy('h.name', 'asc')
              ->addOrderBy('d.name', 'asc')
        ;

        if (null != $arg['limit'] and (preg_match('/^[s,h,d,u]\.id$/', $arg['limit']['type']) or $arg['limit']['type'] == "r.cluster")) {
            $query->leftJoin('a.user', 'u')
                  ->addSelect('u')
                  ->andWhere($arg['limit']['type'] . ' = :value')
                  ->setParameter('value', $arg['limit']['value'])
            ;
        }

        if (!isset($arg['admin'])) {
            $query->andWhere('a.end > :now')
                  ->setParameter('now', new \DateTime('now'))
            ;
        }

        return $query->getQuery()
                     ->getResult()
        ;
    }

    public function getNext($id)
    {
        $query = $this->createQueryBuilder('h');
        $query->where('h.id > :id')
              ->setParameter('id', $id)
              ->setMaxResults(1)
        ;
        return $query->getQuery()
                     ->getOneOrNullResult()
        ;
    }

    public function countAll()
    {
        return $this->createQueryBuilder('h')
                    ->select('COUNT(h.id)')
                    ->getQuery()
                    ->getSingleScalarResult()
        ;
    }

    public function getAll()
    {
        return $this->createQueryBuilder('h')
            ->leftJoin('h.departments', 'd')
            ->addSelect('d')
            ->getQuery()
            ->getResult()
        ;
    }
}
