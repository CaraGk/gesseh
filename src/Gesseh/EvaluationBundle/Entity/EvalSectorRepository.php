<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\EvaluationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EvalSectorRepository
 */
class EvalSectorRepository extends EntityRepository
{
  public function getSimpleEvalSectorQuery()
  {
    return $this->createQueryBuilder('s')
                ->join('s.form', 'f')
    ;
  }

  public function getEvalSectorQuery()
  {
    $query = $this->getSimpleEvalSectorQuery();
    return $query->join('f.criterias', 'c')
                 ->join('s.sector', 't')
                 ->addSelect('f')
                 ->addSelect('c')
    ;
  }

  public function getEvalSector($id)
  {
    $query = $this->getEvalSectorQuery();
    $query->where('t.id = :id')
            ->setParameter('id', $id)
          ->orderBy('c.rank', 'asc');

    return $query->getQuery()->getOneOrNullResult();
  }

  public function getAll()
  {
    $query = $this->getEvalSectorQuery();

    return $query->getQuery()->getResult();
  }

  public function getAllByForm($forms)
  {
    $result = array();
    foreach($forms as $form) {
        $query = $this->getSimpleEvalSectorQuery();
        $query->where('f.id = :id')
              ->setParameter('id', $form->getId());
        $result[$form->getId()] = $query->getQuery()->getResult();
    }

    return $result;
  }

  public function getAssignedSectors()
  {
    $eval_sectors = $this->getSimpleEvalSectorQuery()->getQuery()->getResult();
    foreach($eval_sectors as $eval_sector) {
        $assigned_sectors[] = $eval_sector->getSector()->getId();
    }

    return $assigned_sectors;
  }
}
