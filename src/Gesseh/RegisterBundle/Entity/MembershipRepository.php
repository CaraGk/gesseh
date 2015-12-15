<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * MembershipRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembershipRepository extends EntityRepository
{
    public function getLastByUsername($username)
    {
        $query = $this->createQueryBuilder('m');
        $query->join('m.student', 's')
            ->join('s.user', 'u')
            ->addSelect('s')
            ->addSelect('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->orderBy('m.id', 'desc')
            ->setMaxResults(1)
        ;

        return $query->getQuery()->getSingleResult();
    }

    public function getCurrentForStudent($student, $payed = false)
    {
        $query = $this->createQueryBuilder('m');
        $query->where('m.student = :student')
            ->setParameter('student', $student)
            ->andWhere('m.expiredOn > :now')
            ->setParameter('now', new \DateTime('now'))
            ->setMaxResults(1)
        ;

        if ($payed)
            $query->andWhere('m.payedOn is not NULL');

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getCurrentForAll($filter = null)
    {
        $query = $this->createQueryBuilder('m')
            ->join('m.student', 's')
            ->addSelect('s')
            ->join('s.user', 'u')
            ->addSelect('u')
            ->where('m.expiredOn > :now')
            ->setParameter('now', new \DateTime('now'))
            ->orderBy('s.name', 'asc');

        if ($filter) {
            if ($filter['valid'])
                $query->andWhere('m.payedOn is not NULL');
            elseif ($filter['valid'] != null)
                $query->andWhere('m.payedOn is NULL');

            if ($filter['questions'] != null) {
                foreach ($filter['questions'] as $question_id => $value) {
                    $query->join('m.infos', 'i')
                        ->join('i.question', 'q')
                        ->andWhere('q.id = :question_id')
                        ->setParameter('question_id', $question_id)
                        ->andWhere('i.value = :info_value')
                        ->setParameter('info_value', $value)
                    ;
                }
            }
        }

        return $query->getQuery()->getResult();
    }

    public function getCurrentForAllComplete()
    {
        $query = $this->createQueryBuilder('m')
            ->join('m.student', 's')
            ->addSelect('s')
            ->join('s.user', 'u')
            ->addSelect('u')
            ->join('s.placements', 'p')
            ->addSelect('p')
            ->join('p.department', 'd')
            ->addSelect('d')
            ->join('d.sector', 't')
            ->addSelect('t')
            ->join('p.period', 'r')
            ->addSelect('r')
            ->join('m.infos', 'i')
            ->addSelect('i')
            ->join('i.question', 'q')
            ->addSelect('q')
            ->join('s.grade', 'g')
            ->addSelect('g')
            ->where('m.expiredOn > :now')
            ->setParameter('now', new \DateTime('now'))
            ->andWhere('m.payedOn is not NULL')
            ->addOrderBy('s.surname', 'asc')
            ->addOrderBy('s.name', 'asc')
        ;

        return $query->getQuery()->getResult();
    }
}
