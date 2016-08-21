<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EvalFormRepository
 */
class EvalFormRepository extends EntityRepository
{
    public function getByPlacement($placement_id)
    {
        $query = $this->createQueryBuilder('f')
            ->join('f.sectors', 't')
            ->join('t.sector', 's')
            ->join('s.accreditations', 'a')
            ->join('a.department', 'd')
            ->join('d.repartitions', 'r')
            ->join('r.period', 'q')
            ->join('r.placements', 'p')
            ->where('p.id = :placement_id')
            ->setParameter('placement_id', $placement_id)
            ->andWhere('a.begin <= q.begin')
            ->andWhere('a.end >= q.end')
        ;

        return $query->getQuery()
            ->getResult();
    }
}
